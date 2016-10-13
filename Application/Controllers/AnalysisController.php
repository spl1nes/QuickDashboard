<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\Location;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Math\Finance\Lorenzkurve;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use phpOMS\Localization\ISO3166TwoEnum;
use QuickDashboard\Application\Models\Customer;
use QuickDashboard\Application\Models\StructureDefinitions;

class AnalysisController extends DashboardController
{
    private function loopOverview(array $resultset, array &$totalSales)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
            }

            $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
        }
    }

    private function loopCustomer(string $period, array $resultset, array &$salesCustomers)
    {
        foreach ($resultset as $line) {
            $customer = trim($line['customer']);
            if (!isset($salesCustomers[$period][$customer])) {
                $salesCustomers[$period][$customer] = 0.0;
            }

            $salesCustomers[$period][$customer] += $line['sales'];
        }
    }

    private function loopCustomerCount(array $resultset, array &$customerCount)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($customerCount[$fiscalYear][$fiscalMonth])) {
                $customerCount[$fiscalYear][$fiscalMonth] = 0;
            }

            $customerCount[$fiscalYear][$fiscalMonth] += $line['customers'];
        }
    }

    private function loopLocation(string $period, array $resultset, array &$salesRegion, array &$salesDevUndev, array &$salesCountry)
    {
        foreach ($resultset as $line) {
            if (!isset($line['countryChar'])) {
                continue;
            }

            $region = StructureDefinitions::getRegion($line['countryChar']);
            if (!isset($salesRegion[$period][$region])) {
                $salesRegion[$period][$region] = 0.0;
            }

            $salesRegion[$period][$region] += $line['sales'];

            $devundev = StructureDefinitions::getDevelopedUndeveloped($line['countryChar']);
            if (!isset($salesDevUndev[$period][$devundev])) {
                $salesDevUndev[$period][$devundev] = 0.0;
            }

            $salesDevUndev[$period][$devundev] += $line['sales'];

            $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
            if (!isset($salesCountry[$period][$iso3166Char3])) {
                $salesCountry[$period][$iso3166Char3] = 0.0;
            }

            $salesCountry[$period][$iso3166Char3] += $line['sales'];
        }
    }
    
    public function showAnalysisCustomer(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-customer');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        if (($request->getData('customer') ?? 0) != 0) {
            if ($request->getData('u') === 'gdf') {
                $company = 'gdf';
            } else {
                $company = 'sd';
            }

            $customerInfo = $this->selectCustomerInformation($company, (int) $request->getData('customer') ?? 0);

            if ($customerInfo !== false) {
                $location = new Location();
                $location->setPostal($customerInfo['PLZ']);
                $location->setCity($customerInfo['ORT']);
                $location->setAddress($customerInfo['STRASSE']);
                $location->setCountry($customerInfo['LAENDERKUERZEL']);

                $customer = new Customer(
                    (int) $request->getData('customer'),
                    $customerInfo['NAME1'],
                    $location,
                    $customerInfo['Name'],
                    new \DateTime($customerInfo['ROW_CREATE_TIME'] ?? 'now'),
                    StructureDefinitions::CUSTOMER_GROUP[$company][$customerInfo['_KUNDENGRUPPE']]
                );

                $accounts   = StructureDefinitions::getEBITAccounts();
                $accounts[] = 8591;

                $salesCustomer      = [];
                $groupSales         = [];
                $accGroupSales      = [];
                $accGroupSalesTotal = [];
                $accSalesCustomer   = [];

                $sales = $this->selectAddon('selectGroupsByCustomer', $start, $current, $company, $accounts, (int) $request->getData('customer'));
                $this->loopSalesCustomer($sales, $salesCustomer, $groupSales);

                $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
                $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
                $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                foreach ($salesCustomer as $year => $months) {
                    ksort($salesCustomer[$year]);
                    ksort($groupSales[$year]);

                    for ($month = 1; $month < 13; $month++) {
                        $prev                            = $accSalesCustomer[$year][$month - 1] ?? 0.0;
                        $accSalesCustomer[$year][$month] = $prev + ($salesCustomer[$year][$month] ?? 0);

                        foreach ($groupSales[$year][$month] ?? [] as $group => $value2) {
                            if (!isset($accGroupSalesTotal[$year][$group])) {
                                $accGroupSales[$year][$group]      = 0.0;
                                $accGroupSalesTotal[$year][$group] = 0.0;
                            }

                            if ($month <= $currentMonth) {
                                $accGroupSales[$year][$group] += $value2;
                            }

                            $accGroupSalesTotal[$year][$group] += $value2;
                        }
                    }
                }

                $view->setData('currentFiscalYear', $currentYear);
                $view->setData('currentMonth', $currentMonth);
                $view->setData('sales', $salesCustomer);
                $view->setData('salesAcc', $accSalesCustomer);
                $view->setData('salesGroups', $accGroupSales);
                $view->setData('salesGroupsTotal', $accGroupSalesTotal);
                $view->setData('date', $current);
                $view->setData('customer', $customer);
            }
        }

        return $view;
    }

    private function loopSalesCustomer(array $resultset, array &$totalSales, array &$groupSales)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
            }

            $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);
            if (!isset(StructureDefinitions::NAMING[$group])) {
                continue;
            }

            /** @noinspection PhpUnreachableStatementInspection */
            $group = StructureDefinitions::NAMING[$group];

            if (!isset($groupSales[$fiscalYear][$fiscalMonth][$group])) {
                $groupSales[$fiscalYear][$fiscalMonth][$group] = 0.0;
            }

            $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
            $groupSales[$fiscalYear][$fiscalMonth][$group] += $line['sales'];
        }
    }

    public function showAnalysisSegmentation(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-segmentation');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        if ($request->getData('segment') !== null) {
            $totalSales    = [];
            $accTotalSales = [];

            $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
            if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                $accounts[] = 8591;
            }

            $groups = StructureDefinitions::getSalesGroups((int) $request->getData('segment'));

            if ($request->getData('u') !== 'gdf') {
                $salesSD = $this->selectAddon('selectSalesGroupYearMonth', $start, $current, 'sd', $accounts, $groups);
                $this->loopOverview($salesSD, $totalSales);
            }

            if ($request->getData('u') !== 'sd') {
                $salesGDF = $this->selectAddon('selectSalesGroupYearMonth', $start, $current, 'gdf', $accounts, $groups);
                $this->loopOverview($salesGDF, $totalSales);
            }

            foreach ($totalSales as $year => $months) {
                ksort($totalSales[$year]);

                for ($month = 1; $month < 13; $month++) {
                    $prev                         = $accTotalSales[$year][$month - 1] ?? 0.0;
                    $accTotalSales[$year][$month] = $prev + ($totalSales[$year][$month] ?? 0);
                }
            }

            $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
            $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
            $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            // CUSTOMERS
            $salesCustomers = [];
            $customerCount  = [];

            if ($request->getData('u') !== 'gdf') {
                $customersSD     = $this->selectAddon('selectGroupCustomer', $startCurrent, $endCurrent, 'sd', $accounts, $groups);
                $customersSDLast = $this->selectAddon('selectGroupCustomer', $startLast, $endLast, 'sd', $accounts, $groups);

                $this->loopCustomer('now', $customersSD, $salesCustomers);
                $this->loopCustomer('old', $customersSDLast, $salesCustomers);

                $customerSD = $this->selectAddon('selectGroupCustomerCount', $start, $current, 'sd', $accounts, $groups);
                $this->loopCustomerCount($customerSD, $customerCount);
            }

            if ($request->getData('u') !== 'sd') {
                $customersGDF     = $this->selectAddon('selectGroupCustomer', $startCurrent, $endCurrent, 'gdf', $accounts, $groups);
                $customersGDFLast = $this->selectAddon('selectGroupCustomer', $startLast, $endLast, 'gdf', $accounts, $groups);

                $this->loopCustomer('now', $customersGDF, $salesCustomers);
                $this->loopCustomer('old', $customersGDFLast, $salesCustomers);

                $customerGDF = $this->selectAddon('selectGroupCustomerCount', $start, $current, 'gdf', $accounts, $groups);
                $this->loopCustomerCount($customerGDF, $customerCount);
            }

            $gini = [];
            if (isset($salesCustomers['now'])) {
                arsort($salesCustomers['now']);
                $gini['now'] = Lorenzkurve::getGiniCoefficient($salesCustomers['now']);
            }

            if (isset($salesCustomers['old'])) {
                arsort($salesCustomers['old']);
                $gini['old'] = Lorenzkurve::getGiniCoefficient($salesCustomers['old']);
            }

            foreach ($customerCount as $year => $months) {
                ksort($customerCount[$year]);
            }

            // LOCATION
            $salesRegion         = [];
            $salesDevUndev       = [];
            $salesExportDomestic = [];
            $salesCountry        = [];

            $domesticSD      = [];
            $domesticGDF     = [];
            $domesticSDLast  = [];
            $domesticGDFLast = [];
            $allGDF          = [];
            $allSD           = [];
            $allGDFLast      = [];
            $allSDLast       = [];

            $accounts_DOMESTIC = StructureDefinitions::ACCOUNTS_DOMESTIC;
            if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                $accounts_DOMESTIC[] = 8591;
            }

            if ($request->getData('u') !== 'gdf') {
                $countrySD     = $this->selectAddon('selectGroupSalesByCountry', $startCurrent, $endCurrent, 'sd', $accounts, $groups);
                $countrySDLast = $this->selectAddon('selectGroupSalesByCountry', $startLast, $endLast, 'sd', $accounts, $groups);

                $this->loopLocation('now', $countrySD, $salesRegion, $salesDevUndev, $salesCountry);
                $this->loopLocation('old', $countrySDLast, $salesRegion, $salesDevUndev, $salesCountry);

                $domesticSDLast = $this->selectAddon('selectGroupAccounts', $startLast, $endLast, 'sd', $accounts_DOMESTIC, $groups);
                $domesticSD     = $this->selectAddon('selectGroupAccounts', $startCurrent, $endCurrent, 'sd', $accounts_DOMESTIC, $groups);

                $allSD     = $this->selectAddon('selectGroupAccounts', $startCurrent, $endCurrent, 'sd', $accounts, $groups);
                $allSDLast = $this->selectAddon('selectGroupAccounts', $startLast, $endLast, 'sd', $accounts, $groups);
            }

            if ($request->getData('u') !== 'sd') {
                $countryGDF     = $this->selectAddon('selectGroupSalesByCountry', $startCurrent, $endCurrent, 'gdf', $accounts, $groups);
                $countryGDFLast = $this->selectAddon('selectGroupSalesByCountry', $startLast, $endLast, 'gdf', $accounts, $groups);

                $this->loopLocation('now', $countryGDF, $salesRegion, $salesDevUndev, $salesCountry);
                $this->loopLocation('old', $countryGDFLast, $salesRegion, $salesDevUndev, $salesCountry);

                $domesticGDFLast = $this->selectAddon('selectGroupAccounts', $startLast, $endLast, 'gdf', $accounts_DOMESTIC, $groups);
                $domesticGDF     = $this->selectAddon('selectGroupAccounts', $startCurrent, $endCurrent, 'gdf', $accounts_DOMESTIC, $groups);

                $allGDF     = $this->selectAddon('selectGroupAccounts', $startCurrent, $endCurrent, 'gdf', $accounts, $groups);
                $allGDFLast = $this->selectAddon('selectGroupAccounts', $startLast, $endLast, 'gdf', $accounts, $groups);
            }

            $salesExportDomestic['now']['Domestic'] = ($domesticSD[0]['sales'] ?? 0) + ($domesticGDF[0]['sales'] ?? 0);
            $salesExportDomestic['old']['Domestic'] = ($domesticSDLast[0]['sales'] ?? 0) + ($domesticGDFLast[0]['sales'] ?? 0);
            $salesExportDomestic['now']['Export']   = ($allGDF[0]['sales'] ?? 0) + ($allSD[0]['sales'] ?? 0) - $salesExportDomestic['now']['Domestic'];
            $salesExportDomestic['old']['Export']   = ($allGDFLast[0]['sales'] ?? 0) + ($allSDLast[0]['sales'] ?? 0) - $salesExportDomestic['old']['Domestic'];
            $salesCountry['now']['DEU']             = $salesExportDomestic['now']['Domestic'];
            $salesCountry['old']['DEU']             = $salesExportDomestic['old']['Domestic'];

            if (isset($salesCountry['now'])) {
                arsort($salesCountry['now']);
            }

            $salesDevUndev['now']['Developed'] = ($salesDevUndev['now']['Developed'] ?? 0) + array_sum($salesExportDomestic['now'] ?? []) - array_sum($salesDevUndev['now'] ?? []);
            $salesDevUndev['old']['Developed'] = ($salesDevUndev['old']['Developed'] ?? 0) + array_sum($salesExportDomestic['old'] ?? []) - array_sum($salesDevUndev['old'] ?? []);

            $salesRegion['now']['Europe'] = ($salesRegion['now']['Europe'] ?? 0) + array_sum($salesExportDomestic['now'] ?? []) - array_sum($salesRegion['now'] ?? []);
            $salesRegion['old']['Europe'] = ($salesRegion['old']['Europe'] ?? 0) + array_sum($salesExportDomestic['old'] ?? []) - array_sum($salesRegion['old'] ?? []);

            $view->setData('salesCountry', $salesCountry);
            $view->setData('salesRegion', $salesRegion);
            $view->setData('salesDevUndev', $salesDevUndev);
            $view->setData('salesExportDomestic', $salesExportDomestic);
            $view->setData('currentFiscalYear', $currentYear);
            $view->setData('currentMonth', $currentMonth);
            $view->setData('sales', $totalSales);
            $view->setData('salesAcc', $accTotalSales);
            $view->setData('customer', $salesCustomers);
            $view->setData('customerCount', $customerCount);
            $view->setData('gini', $gini);
            $view->setData('date', $current);
        }

        return $view;
    }

    public function showAnalysisLocation(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-location');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $countries = null;

        if ($request->getData('location') !== null) {
            $totalSales    = [];
            $accTotalSales = [];

            $salesGroups    = [];
            $totalGroups    = ['now' => 0.0, 'old' => 0.0];
            $salesCustomers = [];
            $customerCount  = [];

            $accounts = array_diff(StructureDefinitions::PL_ACCOUNTS['Sales'], StructureDefinitions::ACCOUNTS_DOMESTIC);

            if($request->getData('location') !== 'Domestic' && $request->getData('location') !== 'DE' && $request->getData('location') !== 'Export') {
                $countries = StructureDefinitions::getLocations($request->getData('location'));
            }

            if($request->getData('location') === 'Developed' || $request->getData('location') === 'Europe') {
                $countries = array_diff($countries, ['DE']);
            }

            if ($request->getData('u') !== 'gdf') {
                if($request->getData('location') === 'Domestic' || $request->getData('location') === 'DE') {
                    $accounts = StructureDefinitions::ACCOUNTS_DOMESTIC;

                    if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                        $accounts[] = 8591;
                    }
                }

                if($request->getData('location') === 'Developed' || $request->getData('location') === 'Europe') {
                    $accounts_DOMESTIC = StructureDefinitions::ACCOUNTS_DOMESTIC;
                    if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                        $accounts_DOMESTIC[] = 8591;
                    }

                    $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts_DOMESTIC);
                    $this->loopOverview($salesSD, $totalSales);

                    $customersSD     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'sd', $accounts_DOMESTIC);
                    $customersSDLast = $this->select('selectCustomer', $startLast, $endLast, 'sd', $accounts_DOMESTIC);
                    $customerSD = $this->select('selectCustomerCount', $start, $current, 'sd', $accounts_DOMESTIC);
                    $this->loopCustomer('now', $customersSD, $salesCustomers);
                    $this->loopCustomer('old', $customersSDLast, $salesCustomers);
                    $this->loopCustomerCount($customerSD, $customerCount);
                }

                if (!isset($countries)) {
                    $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
                    $customersSD     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'sd', $accounts);
                    $customersSDLast = $this->select('selectCustomer', $startLast, $endLast, 'sd', $accounts);
                    $customerSD = $this->select('selectCustomerCount', $start, $current, 'sd', $accounts);
                } else {
                    $salesSD = $this->selectAddon('selectCountrySalesYearMonth', $start, $current, 'sd', $accounts, $countries);
                    $customersSD     = $this->selectAddon('selectCountryCustomer', $startCurrent, $endCurrent, 'sd', $accounts, $countries);
                    $customersSDLast = $this->selectAddon('selectCountryCustomer', $startLast, $endLast, 'sd', $accounts, $countries);
                    $customerSD = $this->selectAddon('selectCountryCustomerCount', $start, $current, 'sd', $accounts, $countries);
                }

                $this->loopOverview($salesSD, $totalSales);
                $this->loopCustomer('now', $customersSD, $salesCustomers);
                $this->loopCustomer('old', $customersSDLast, $salesCustomers);
                $this->loopCustomerCount($customerSD, $customerCount);
            }

            if ($request->getData('u') !== 'sd') {
                if($request->getData('location') === 'Domestic' || $request->getData('location') === 'DE') {
                    $accounts = StructureDefinitions::ACCOUNTS_DOMESTIC;

                    if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                        $accounts[] = 8591;
                    }
                }

                if($request->getData('location') === 'Developed' || $request->getData('location') === 'Europe') {
                    $accounts_DOMESTIC = StructureDefinitions::ACCOUNTS_DOMESTIC;
                    if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                        $accounts_DOMESTIC[] = 8591;
                    }

                    $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts_DOMESTIC);
                    $this->loopOverview($salesGDF, $totalSales);

                    $customersGDF     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'gdf', $accounts_DOMESTIC);
                    $customersGDFLast = $this->select('selectCustomer', $startLast, $endLast, 'gdf', $accounts_DOMESTIC);
                    $customerGDF = $this->select('selectCustomerCount', $start, $current, 'gdf', $accounts_DOMESTIC);
                    $this->loopCustomer('now', $customersGDF, $salesCustomers);
                    $this->loopCustomer('old', $customersGDFLast, $salesCustomers);
                    $this->loopCustomerCount($customerGDF, $customerCount);
                }

                if (!isset($countries)) {
                    $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
                    $customersGDF     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'gdf', $accounts);
                    $customersGDFLast = $this->select('selectCustomer', $startLast, $endLast, 'gdf', $accounts);
                    $customerGDF = $this->select('selectCustomerCount', $start, $current, 'gdf', $accounts);
                } else {
                    $salesGDF = $this->selectAddon('selectCountrySalesYearMonth', $start, $current, 'gdf', $accounts, $countries);
                    $customersGDF     = $this->selectAddon('selectCountryCustomer', $startCurrent, $endCurrent, 'gdf', $accounts, $countries);
                    $customersGDFLast = $this->selectAddon('selectCountryCustomer', $startLast, $endLast, 'gdf', $accounts, $countries);
                    $customerGDF = $this->selectAddon('selectCountryCustomerCount', $start, $current, 'gdf', $accounts, $countries);
                }

                $this->loopOverview($salesGDF, $totalSales);
                $this->loopCustomer('now', $customersGDF, $salesCustomers);
                $this->loopCustomer('old', $customersGDFLast, $salesCustomers);
                $this->loopCustomerCount($customerGDF, $customerCount);
            }

            arsort($salesCustomers['now']);
            arsort($salesCustomers['old']);

            $gini = [
                'now' => Lorenzkurve::getGiniCoefficient($salesCustomers['now']),
                'old' => Lorenzkurve::getGiniCoefficient($salesCustomers['old']),
            ];

            foreach ($customerCount as $year => $months) {
                ksort($customerCount[$year]);
            }

            foreach ($totalSales as $year => $months) {
                ksort($totalSales[$year]);

                for ($month = 1; $month < 13; $month++) {
                    $prev                         = $accTotalSales[$year][$month - 1] ?? 0.0;
                    $accTotalSales[$year][$month] = $prev + ($totalSales[$year][$month] ?? 0);
                }
            }

            $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
            $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
            $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            $view->setData('currentFiscalYear', $currentYear);
            $view->setData('currentMonth', $currentMonth);
            $view->setData('sales', $totalSales);
            $view->setData('salesAcc', $accTotalSales);
            $view->setData('date', $current);
            $view->setData('customer', $salesCustomers);
            $view->setData('customerCount', $customerCount);
            $view->setData('gini', $gini);
        }

        return $view;
    }
}
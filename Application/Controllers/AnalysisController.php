<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\Location;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Math\Finance\Lorenzkurve;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\Customer;
use QuickDashboard\Application\Models\StructureDefinitions;
use phpOMS\DataStorage\Database\Query\Builder;
use QuickDashboard\Application\Models\Queries;

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
            $customer = substr(trim($line['customer']), 0, 20);
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

    private function loopArticleGroups(string $period, array $resultset, array &$salesGroups, array &$segmentGroups, array &$totalGroups)
    {
        foreach ($resultset as $line) {
            $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);

            if ($group === 0) {
                continue;
            }

            $segment = StructureDefinitions::getSegmentOfArticle($line['costcenter']);

            if (!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                continue;
            }

            /** @noinspection PhpUnreachableStatementInspection */
            if (!isset($salesGroups['All'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period])) {
                $salesGroups['All'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period]      = 0.0;
            }

            if (!isset($segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period])) {
                $segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period]      = 0.0;
            }

            $salesGroups['All'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];
            $totalGroups['All'][$period] += $line['sales'];
        }
    }

    public function showAnalysisCustomer(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-customer');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $days = ($current->getTimestamp() - $start->getTimestamp()) / (60 * 60 * 24);
        $start->modify('-2 year');
        $old = clone $current;
        $old->modify('-2 year');

        if (($request->getData('customer') ?? 0) != 0) {
            if ($request->getData('u') === 'gdf') {
                $company = 'gdf';
            } else {
                $company = 'sd';
            }

            $customerInfo = $this->selectCustomerInformation($company, (int) $request->getData('customer') ?? 0);

            if ($customerInfo !== false) {
                $location = new Location();
                $location->setPostal($customerInfo['PLZ'] ?? 'n/a');
                $location->setCity($customerInfo['ORT'] ?? 'n/a');
                $location->setAddress($customerInfo['STRASSE'] ?? 'n/a');
                $location->setCountry($customerInfo['LAENDERKUERZEL'] ?? 'n/a');

                $customer = new Customer(
                    (int) $request->getData('customer'),
                    $customerInfo['NAME1'] ?? 'n/a',
                    $location,
                    $customerInfo['Name'] ?? 'n/a',
                    new \DateTime($customerInfo['ROW_CREATE_TIME'] ?? 'now'),
                    StructureDefinitions::CUSTOMER_GROUP[$company][$customerInfo['_KUNDENGRUPPE']] ?? 'n/a'
                );

                $accounts   = StructureDefinitions::getEBITAccounts();
                $accounts[] = 8591;

                $salesCustomer      = [];
                $groupSales         = [];
                $accGroupSales      = [];
                $accGroupSalesTotal = [];
                $accSalesCustomer   = [];

                $customerDSO = ['old' => 0.0, 'now' => 0.0];
                $customerOP = ['old' => 0.0, 'now' => 0.0];
                $customerDue = ['old' => 0.0, 'now' => 0.0];

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

                $salesGroups   = [];
                $segmentGroups = [];
                $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

                foreach ($temp as $segment) {
                    $salesGroups[$segment]   = [];
                    $segmentGroups[$segment] = [];
                }

                $totalGroups = [];

                $this->loopArticleGroups2($currentMonth, $sales, $salesGroups, $segmentGroups, $totalGroups);

                $salesGroups2   = [];
                $segmentGroups2 = [];

                foreach ($temp as $segment) {
                    $salesGroups2[$segment]   = [];
                    $segmentGroups2[$segment] = [];
                }

                $totalGroups2 = [];

                $this->loopArticleGroups2(12, $sales, $salesGroups2, $segmentGroups2, $totalGroups2);

                $dso = $this->selectDSO('selectOPByAccountDebit', $current, $company, (int) $request->getData('customer')) ?? 0;
                $dso -= $this->selectDSO('selectOPByAccountCredit', $current, $company, (int) $request->getData('customer')) ?? 0;
                $customerDSO['now'] = (int) $accSalesCustomer[$currentYear][$currentMonth] === 0 ? 0 : round(!isset($accSalesCustomer[$currentYear][$currentMonth]) ? 0 : $dso / ($accSalesCustomer[$currentYear][$currentMonth] / $days));
                $customerOP['now'] = $dso;

                $due = $this->selectDSO('selectOPByAccountDebitDue', $current, $company, (int) $request->getData('customer')) ?? 0;
                $due -= $this->selectDSO('selectOPByAccountCreditDue', $current, $company, (int) $request->getData('customer')) ?? 0;
                $customerDue['now'] = $due;

                $dso = $this->selectDSO('selectOPByAccountDebit', $old, $company, (int) $request->getData('customer')) ?? 0;
                $dso -= $this->selectDSO('selectOPByAccountCredit', $old, $company, (int) $request->getData('customer')) ?? 0;
                $customerDSO['old'] = (int) round(!isset($accSalesCustomer[$currentYear-1][$currentMonth]) ? 0 : $dso / ($accSalesCustomer[$currentYear-1][$currentMonth] / $days));
                $customerOP['old'] = $dso;

                $due = $this->selectDSO('selectOPByAccountDebitDue', $old, $company, (int) $request->getData('customer')) ?? 0;
                $due -= $this->selectDSO('selectOPByAccountCreditDue', $old, $company, (int) $request->getData('customer')) ?? 0;
                $customerDue['old'] = $due;

                $view->setData('currentFiscalYear', $currentYear);
                $view->setData('currentMonth', $currentMonth);
                $view->setData('sales', $salesCustomer);
                $view->setData('salesAcc', $accSalesCustomer);
                $view->setData('salesGroups', $accGroupSales);
                $view->setData('salesGroupsTotal', $accGroupSalesTotal);
                $view->setData('gTemp', $salesGroups);
                $view->setData('sTemp', $segmentGroups);
                $view->setData('tTemp', $totalGroups);
                $view->setData('gTemp2', $salesGroups2);
                $view->setData('sTemp2', $segmentGroups2);
                $view->setData('tTemp2', $totalGroups2);
                $view->setData('date', $current);
                $view->setData('dso', $customerDSO);
                $view->setData('op', $customerOP);
                $view->setData('due', $customerDue);
                $view->setData('customer', $customer);
            }
        }

        return $view;
    }

    private function loopArticleGroups2(int $limit, array $resultset, array &$salesGroups, array &$segmentGroups, array &$totalGroups)
    {
        foreach ($resultset as $line) {
            $month = $this->getFiscalMonth($line['months']);

            if($month > $limit) {
                continue;
            }

            $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);

            if ($group === 0) {
                continue;
            }

            $segment = StructureDefinitions::getSegmentOfArticle($line['costcenter']);

            if (!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                continue;
            }

            $period = $this->getFiscalYear($line['years'], $line['months'], $this->app->config['fiscal_year']);

            /** @noinspection PhpUnreachableStatementInspection */
            if (!isset($salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period])) {
                $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period]      = 0.0;
            }

            if (!isset($segmentGroups[StructureDefinitions::NAMING[$segment]][$period])) {
                $segmentGroups[StructureDefinitions::NAMING[$segment]][$period]      = 0.0;
            }

            $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups[StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];

            if(!isset($totalGroups[$period])) {
                $totalGroups[$period] = 0.0;
            }

            $totalGroups[$period] += $line['sales'];
        }
    }

    protected function selectDSO(string $selectQuery, \DateTime $end, string $company, int $account)
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::{$selectQuery}($end, $account));
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? 0 : $result[0][0];

        return (float) $result;
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

        if ($request->getData('segment') !== null && $request->getData('segment') !== 'All') {
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

            $newCustomers = 0;
            $lostCustomers = 0;

            $monthlyNewCustomer = [];

            if ($request->getData('u') !== 'gdf') {
                $customersSD     = $this->selectAddon('selectGroupCustomer', $startCurrent, $endCurrent, 'sd', $accounts, $groups);
                $customersSDLast = $this->selectAddon('selectGroupCustomer', $startLast, $endLast, 'sd', $accounts, $groups);

                $this->loopCustomer('now', $customersSD, $salesCustomers);
                $this->loopCustomer('old', $customersSDLast, $salesCustomers);

                $customerSD = $this->selectAddon('selectGroupCustomerCount', $start, $current, 'sd', $accounts, $groups);
                $this->loopCustomerCount($customerSD, $customerCount);

                $newCustomersSD = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'sd', $accounts, null, $groups, null);
                $lostCustomersSD = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'sd', $accounts, null, $groups, null);

                $newCustomers += count($newCustomersSD);
                $lostCustomers += count($lostCustomersSD);

                $newCustomersSD = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $startCurrent->createModify(-2), $endCurrent, 'sd', $accounts, null, $groups, null);
                $this->loopNewCustomerMonthly($newCustomersSD, $monthlyNewCustomer);
            }

            if ($request->getData('u') !== 'sd') {
                $customersGDF     = $this->selectAddon('selectGroupCustomer', $startCurrent, $endCurrent, 'gdf', $accounts, $groups);
                $customersGDFLast = $this->selectAddon('selectGroupCustomer', $startLast, $endLast, 'gdf', $accounts, $groups);

                $this->loopCustomer('now', $customersGDF, $salesCustomers);
                $this->loopCustomer('old', $customersGDFLast, $salesCustomers);

                $customerGDF = $this->selectAddon('selectGroupCustomerCount', $start, $current, 'gdf', $accounts, $groups);
                $this->loopCustomerCount($customerGDF, $customerCount);

                $newCustomersGDF = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'gdf', $accounts, null, $groups, null);
                $lostCustomersGDF = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'gdf', $accounts, null, $groups, null);

                $newCustomers += count($newCustomersGDF);
                $lostCustomers += count($lostCustomersGDF);

                $newCustomersGDF = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $startCurrent->createModify(-2), $endCurrent, 'gdf', $accounts, null, $groups, null);
                $this->loopNewCustomerMonthly($newCustomersGDF, $monthlyNewCustomer);
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

            // Ranking
            $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
            if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                $accounts[] = 8591;
            }

            $repsSales = [];

            if ($request->getData('u') !== 'gdf') {
                $repsSD     = $this->selectAddon('selectRepGroupSales', $startCurrent, $endCurrent, 'sd', $accounts, $groups);
                $repsSDLast = $this->selectAddon('selectRepGroupSales', $startLast, $endLast, 'sd', $accounts, $groups);

                $this->loopReps('now', $repsSD, $repsSales);
                $this->loopReps('old', $repsSDLast, $repsSales);
            }

            if ($request->getData('u') !== 'sd') {
                $repsGDF     = $this->selectAddon('selectRepGroupSales', $startCurrent, $endCurrent, 'gdf', $accounts, $groups);
                $repsGDFLast = $this->selectAddon('selectRepGroupSales', $startLast, $endLast, 'gdf', $accounts, $groups);

                $this->loopReps('now', $repsGDF, $repsSales);
                $this->loopReps('old', $repsGDFLast, $repsSales);
            }

            $repsSales = $repsSales ?? [];
            uasort($repsSales, function($a, $b) { return -1*(($a['now'] ?? 0) <=> ($b['now'] ?? 0)); });

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
            $view->setData('newCustomers', $newCustomers);
            $view->setData('lostCustomers', $lostCustomers);
            $view->setData('repsSales', $repsSales);
            $view->setData('monthlyNewCustomer', $monthlyNewCustomer);
        }

        return $view;
    }

    private function loopReps($period, array $reps, &$repsSales)
    {
        foreach ($reps as $line) {
            if(!isset($repsSales[$line['rep']][$period])) {
                $repsSales[$line['rep']][$period] = 0.0;
            }

            $repsSales[$line['rep']][$period] += $line['sales'];
        }
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

        if ($request->getData('location') !== null && $request->getData('location') !== 'All') {
            $totalSales    = [];
            $accTotalSales = [];

            $salesCustomers = [];
            $customerCount  = [];

            $newCustomers = 0;
            $lostCustomers = 0;

            $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

            $salesGroups   = [];
            $segmentGroups = [];

            foreach ($temp as $segment) {
                $salesGroups['All'][$segment]   = null;
                $segmentGroups['All'][$segment] = null;
            }

            $totalGroups = [
                'All'      => ['now' => 0.0, 'old' => 0.0],
            ];

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

                $newCustomersSD = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'sd', $accounts, null, null, null);
                $lostCustomersSD = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'sd', $accounts, null, null, null);

                $newCustomers += count($newCustomersSD);
                $lostCustomers += count($lostCustomersSD);

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

                    $groupsSD     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts);
                    $groupsSDLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accounts);
                    $this->loopArticleGroups('now', $groupsSD, $salesGroups, $segmentGroups, $totalGroups);
                    $this->loopArticleGroups('old', $groupsSDLast, $salesGroups, $segmentGroups, $totalGroups);
                }

                if (!isset($countries)) {
                    $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
                    $customersSD     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'sd', $accounts);
                    $customersSDLast = $this->select('selectCustomer', $startLast, $endLast, 'sd', $accounts);
                    $customerSD = $this->select('selectCustomerCount', $start, $current, 'sd', $accounts);
                    $groupsSD     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts);
                    $groupsSDLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accounts);
                } else {
                    $salesSD = $this->selectAddon('selectCountrySalesYearMonth', $start, $current, 'sd', $accounts, $countries);
                    $customersSD     = $this->selectAddon('selectCountryCustomer', $startCurrent, $endCurrent, 'sd', $accounts, $countries);
                    $customersSDLast = $this->selectAddon('selectCountryCustomer', $startLast, $endLast, 'sd', $accounts, $countries);
                    $customerSD = $this->selectAddon('selectCountryCustomerCount', $start, $current, 'sd', $accounts, $countries);
                    $groupsSD     = $this->selectAddon('selectCountrySalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts, $countries);
                    $groupsSDLast = $this->selectAddon('selectCountrySalesArticleGroups', $startLast, $endLast, 'sd', $accounts, $countries);
                }

                $this->loopOverview($salesSD, $totalSales);
                $this->loopCustomer('now', $customersSD, $salesCustomers);
                $this->loopCustomer('old', $customersSDLast, $salesCustomers);
                $this->loopCustomerCount($customerSD, $customerCount);
                $this->loopArticleGroups('now', $groupsSD, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopArticleGroups('old', $groupsSDLast, $salesGroups, $segmentGroups, $totalGroups);
            }

            if ($request->getData('u') !== 'sd') {
                if($request->getData('location') === 'Domestic' || $request->getData('location') === 'DE') {
                    $accounts = StructureDefinitions::ACCOUNTS_DOMESTIC;

                    if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                        $accounts[] = 8591;
                    }
                }

                $newCustomersGDF = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'gdf', $accounts, null, null, null);
                $lostCustomersGDF = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'gdf', $accounts, null, null, null);

                $newCustomers += count($newCustomersGDF);
                $lostCustomers += count($lostCustomersGDF);

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

                    $groupsGDF     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts);
                    $groupsGDFLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts);
                    $this->loopArticleGroups('now', $groupsGDF, $salesGroups, $segmentGroups, $totalGroups);
                    $this->loopArticleGroups('old', $groupsGDFLast, $salesGroups, $segmentGroups, $totalGroups);
                }

                if (!isset($countries)) {
                    $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
                    $customersGDF     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'gdf', $accounts);
                    $customersGDFLast = $this->select('selectCustomer', $startLast, $endLast, 'gdf', $accounts);
                    $customerGDF = $this->select('selectCustomerCount', $start, $current, 'gdf', $accounts);
                    $groupsGDF     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts);
                    $groupsGDFLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts);
                } else {
                    $salesGDF = $this->selectAddon('selectCountrySalesYearMonth', $start, $current, 'gdf', $accounts, $countries);
                    $customersGDF     = $this->selectAddon('selectCountryCustomer', $startCurrent, $endCurrent, 'gdf', $accounts, $countries);
                    $customersGDFLast = $this->selectAddon('selectCountryCustomer', $startLast, $endLast, 'gdf', $accounts, $countries);
                    $customerGDF = $this->selectAddon('selectCountryCustomerCount', $start, $current, 'gdf', $accounts, $countries);
                    $groupsGDF     = $this->selectAddon('selectCountrySalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts, $countries);
                    $groupsGDFLast = $this->selectAddon('selectCountrySalesArticleGroups', $startLast, $endLast, 'gdf', $accounts, $countries);
                }

                $this->loopOverview($salesGDF, $totalSales);
                $this->loopCustomer('now', $customersGDF, $salesCustomers);
                $this->loopCustomer('old', $customersGDFLast, $salesCustomers);
                $this->loopCustomerCount($customerGDF, $customerCount);
                $this->loopArticleGroups('now', $groupsGDF, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopArticleGroups('old', $groupsGDFLast, $salesGroups, $segmentGroups, $totalGroups);
            }

            $gini = null;

            if(isset($salesCustomers['now'])) {
                arsort($salesCustomers['now']);
                $gini['now'] = Lorenzkurve::getGiniCoefficient($salesCustomers['now']);
            }

            if(isset($salesCustomers['old'])) {
                arsort($salesCustomers['old']);
                $gini['old'] = Lorenzkurve::getGiniCoefficient($salesCustomers['old']);
            }

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
            $view->setData('salesGroups', $salesGroups);
            $view->setData('segmentGroups', $segmentGroups);
            $view->setData('totalGroups', $totalGroups);
            $view->setData('title', 'Analysis ' . $request->getData('location'));
            $view->setData('newCustomers', $newCustomers);
            $view->setData('lostCustomers', $lostCustomers);
        }

        return $view;
    }

    public function showAnalysisOPEX(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-opex');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        if (isset(StructureDefinitions::PL_ACCOUNTS[$request->getData('opex')])) {
            $accounts   = StructureDefinitions::PL_ACCOUNTS[$request->getData('opex')];

            $opexCosts      = [];
            $accOpexCosts   = [];

            $groupOpex         = [];
            $accGroupOpex      = [];

            if($request->getData('u') !== 'sd' && $request->getData('u') !== 'gdf') {
                unset($accounts[array_search(4992, $accounts)]);
                unset($accounts[array_search(2992, $accounts)]);
            }

            if ($request->getData('u') !== 'gdf') {
                $costs = $this->select('selectGroupsByDay', $start, $current, 'sd', $accounts);
                $this->loopOPEX($costs, 'sd', $opexCosts, $groupOpex);
            }

            if ($request->getData('u') !== 'sd') {
                $costs = $this->select('selectGroupsByDay', $start, $current, 'gdf', $accounts);
                $this->loopOPEX($costs, 'gdf', $opexCosts, $groupOpex);
            }

            $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
            $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
            $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            foreach ($opexCosts as $year => $months) {
                ksort($opexCosts[$year]);

                for ($month = 1; $month < 13; $month++) {
                    $prev                        = $accOpexCosts[$year][$month - 1] ?? 0.0;
                    $accOpexCosts[$year][$month] = $prev + ($opexCosts[$year][$month] ?? 0);

                    foreach ($groupOpex[$year][$month] ?? [] as $group => $value2) {
                        if (!isset($accGroupOpex[$year][$group])) {
                            $accGroupOpex[$year][$group]      = 0.0;
                        }

                        if ($month <= $currentMonth) {
                            $accGroupOpex[$year][$group] += $value2;
                        }
                    }
                }
            }

            $view->setData('currentFiscalYear', $currentYear);
            $view->setData('currentMonth', $currentMonth);
            $view->setData('opex', $opexCosts);
            $view->setData('opexAcc', $accOpexCosts);
            $view->setData('opexGroups', $accGroupOpex);
            $view->setData('date', $current);
        }

        return $view;
    }

    public function showAnalysisAccount(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-account');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        if ($request->getData('account') !== null) {
            $accounts   = [(int) $request->getData('account')];

            $opexCosts      = [];
            $accOpexCosts   = [];

            $groupOpex         = [];
            $accGroupOpex      = [];

            if ($request->getData('u') !== 'gdf') {
                $costs = $this->select('selectGroupsByDay', $start, $current, 'sd', $accounts);
                $this->loopOPEX($costs, 'sd', $opexCosts, $groupOpex);
            }

            if ($request->getData('u') !== 'sd') {
                $costs = $this->select('selectGroupsByDay', $start, $current, 'gdf', $accounts);
                $this->loopOPEX($costs, 'gdf', $opexCosts, $groupOpex);
            }

            $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
            $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
            $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            foreach ($opexCosts as $year => $months) {
                ksort($opexCosts[$year]);

                for ($month = 1; $month < 13; $month++) {
                    $prev                        = $accOpexCosts[$year][$month - 1] ?? 0.0;
                    $accOpexCosts[$year][$month] = $prev + ($opexCosts[$year][$month] ?? 0);

                    foreach ($groupOpex[$year][$month] ?? [] as $group => $value2) {
                        if (!isset($accGroupOpex[$year][$group])) {
                            $accGroupOpex[$year][$group]      = 0.0;
                        }

                        if ($month <= $currentMonth) {
                            $accGroupOpex[$year][$group] += $value2;
                        }
                    }
                }
            }

            $view->setData('currentFiscalYear', $currentYear);
            $view->setData('currentMonth', $currentMonth);
            $view->setData('opex', $opexCosts);
            $view->setData('opexAcc', $accOpexCosts);
            $view->setData('opexGroups', $accGroupOpex);
            $view->setData('date', $current);
        }

        return $view;
    }

    private function loopOPEX(array $resultset, string $company, array &$totalSales, array &$totalGroup)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
            }

            $department = StructureDefinitions::getDepartmentByCostCenter((int) ($line['costcenter'] ?? 0), $company);
            if (!isset($totalGroup[$fiscalYear][$fiscalMonth][$department])) {
                $totalGroup[$fiscalYear][$fiscalMonth][$department] = 0.0;
            }

            $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
            $totalGroup[$fiscalYear][$fiscalMonth][$department] += $line['sales'];
        }
    }

    public function showAnalysisDepartment(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-department');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        if (isset(StructureDefinitions::DEPARTMENTS_SD[$request->getData('department')])) {
            $accounts   = StructureDefinitions::getOPEXAccounts();

            if($request->getData('u') !== 'sd' && $request->getData('u') !== 'gdf') {
                unset($accounts[array_search(4992, $accounts)]);
                unset($accounts[array_search(2992, $accounts)]);
            }

            $opexCosts      = [];
            $accOpexCosts   = [];

            $groupOpex         = [];
            $accGroupOpex      = [];

            if ($request->getData('u') !== 'gdf') {
                $costcenters = StructureDefinitions::DEPARTMENTS_SD[$request->getData('department')];
                $costs = $this->selectAddon('selectAccountsByCostCenter', $start, $current, 'sd', $accounts, $costcenters);
                $this->loopDepartment($costs, $opexCosts, $groupOpex);
            }

            if ($request->getData('u') !== 'sd') {
                $costcenters = StructureDefinitions::DEPARTMENTS_GDF[$request->getData('department')];
                $costs = $this->selectAddon('selectAccountsByCostCenter', $start, $current, 'gdf', $accounts, $costcenters);
                $this->loopDepartment($costs, $opexCosts, $groupOpex);
            }

            $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
            $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
            $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            foreach ($opexCosts as $year => $months) {
                ksort($opexCosts[$year]);

                for ($month = 1; $month < 13; $month++) {
                    $prev                        = $accOpexCosts[$year][$month - 1] ?? 0.0;
                    $accOpexCosts[$year][$month] = $prev + ($opexCosts[$year][$month] ?? 0);

                    foreach ($groupOpex[$year][$month] ?? [] as $group => $value2) {
                        if (!isset($accGroupOpex[$year][$group])) {
                            $accGroupOpex[$year][$group]      = 0.0;
                        }

                        if ($month <= $currentMonth) {
                            $accGroupOpex[$year][$group] += $value2;
                        }
                    }
                }
            }

            $view->setData('currentFiscalYear', $currentYear);
            $view->setData('currentMonth', $currentMonth);
            $view->setData('department', $opexCosts);
            $view->setData('departmentAcc', $accOpexCosts);
            $view->setData('departmentGroups', $accGroupOpex);
            $view->setData('date', $current);
        }

        return $view;
    }

    private function loopDepartment(array $resultset, array &$totalSales, array &$totalGroup)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
            }

            $department = StructureDefinitions::getAccountPLPosition((int) ($line['account'] ?? 0));
            if (!isset($totalGroup[$fiscalYear][$fiscalMonth][$department])) {
                $totalGroup[$fiscalYear][$fiscalMonth][$department] = 0.0;
            }

            $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
            $totalGroup[$fiscalYear][$fiscalMonth][$department] += $line['sales'];
        }
    }

    public function showAnalysisReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-reps');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $repNames = []; 
        if ($request->getData('u') !== 'gdf') {
            $repNames = $this->getSalesReps('sd', $repNames);
        }

        if ($request->getData('u') !== 'sd') {
            $repNames = array_merge($repNames ?? [], $this->getSalesReps('gdf', $repNames));
        }

        asort($repNames);
        $monthlyNewCustomer = [];

        if ($request->getData('rep') !== null && array_key_exists($request->getData('rep'), $repNames)) {
            $totalSales    = [];
            $accTotalSales = [];

            $salesCustomers = [];
            $customerCount  = [];

            $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

            $salesGroups   = [];
            $segmentGroups = [];

            foreach ($temp as $segment) {
                $salesGroups['All'][$segment]   = null;
                $segmentGroups['All'][$segment] = null;
            }

            $totalGroups = [
                'All'      => ['now' => 0.0, 'old' => 0.0],
            ];

            $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
            if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                $accounts[] = 8591;
            }

            if ($request->getData('u') !== 'gdf') {
                $salesSD = $this->selectAddon('selectRepSalesYearMonth', $start, $current, 'sd', $accounts, array ($request->getData('rep')));
                $customersSD     = $this->selectAddon('selectRepCustomer', $startCurrent, $endCurrent, 'sd', $accounts, array ($request->getData('rep')));
                $customersSDLast = $this->selectAddon('selectRepCustomer', $startLast, $endLast, 'sd', $accounts, array ($request->getData('rep')));
                $customerSD = $this->selectAddon('selectRepCustomerCount', $start, $current, 'sd', $accounts, array ($request->getData('rep')));
                $groupsSD     = $this->selectAddon('selectRepSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts, array ($request->getData('rep')));
                $groupsSDLast = $this->selectAddon('selectRepSalesArticleGroups', $startLast, $endLast, 'sd', $accounts, array ($request->getData('rep')));

                $newCustomersSD = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $startCurrent->createModify(-2), $endCurrent, 'sd', $accounts, null, null, array ($request->getData('rep')));
                $this->loopNewCustomerMonthly($newCustomersSD, $monthlyNewCustomer);

                $this->loopOverview($salesSD, $totalSales);
                $this->loopCustomer('now', $customersSD, $salesCustomers);
                $this->loopCustomer('old', $customersSDLast, $salesCustomers);
                $this->loopCustomerCount($customerSD, $customerCount);
                $this->loopArticleGroups('now', $groupsSD, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopArticleGroups('old', $groupsSDLast, $salesGroups, $segmentGroups, $totalGroups);
            }

            if ($request->getData('u') !== 'sd') {
                $salesGDF = $this->selectAddon('selectRepSalesYearMonth', $start, $current, 'gdf', $accounts, array ($request->getData('rep')));
                $customersGDF     = $this->selectAddon('selectRepCustomer', $startCurrent, $endCurrent, 'gdf', $accounts, array ($request->getData('rep')));
                $customersGDFLast = $this->selectAddon('selectRepCustomer', $startLast, $endLast, 'gdf', $accounts, array ($request->getData('rep')));
                $customerGDF = $this->selectAddon('selectRepCustomerCount', $start, $current, 'gdf', $accounts, array ($request->getData('rep')));
                $groupsGDF     = $this->selectAddon('selectRepSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts, array ($request->getData('rep')));
                $groupsGDFLast = $this->selectAddon('selectRepSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts, array ($request->getData('rep')));

                $newCustomersGDF = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $startCurrent->createModify(-2), $endCurrent, 'gdf', $accounts, null, null, array ($request->getData('rep')));
                $this->loopNewCustomerMonthly($newCustomersGDF, $monthlyNewCustomer);

                $this->loopOverview($salesGDF, $totalSales);
                $this->loopCustomer('now', $customersGDF, $salesCustomers);
                $this->loopCustomer('old', $customersGDFLast, $salesCustomers);
                $this->loopCustomerCount($customerGDF, $customerCount);
                $this->loopArticleGroups('now', $groupsGDF, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopArticleGroups('old', $groupsGDFLast, $salesGroups, $segmentGroups, $totalGroups);
            }

            $gini = null;

            if(isset($salesCustomers['now'])) {
                arsort($salesCustomers['now']);
                $gini['now'] = Lorenzkurve::getGiniCoefficient($salesCustomers['now']);
            }

            if(isset($salesCustomers['old'])) {
                arsort($salesCustomers['old']);
                $gini['old'] = Lorenzkurve::getGiniCoefficient($salesCustomers['old']);
            }

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
            $view->setData('salesGroups', $salesGroups);
            $view->setData('segmentGroups', $segmentGroups);
            $view->setData('totalGroups', $totalGroups);
            $view->setData('monthlyNewCustomer', $monthlyNewCustomer);
        }

        $view->setData('repNames', $repNames);

        return $view;
    }

    private function getSalesReps(string $company, array &$reps) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::selectSalesRepNames());
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        foreach($result as $line) {
            if(!in_array($line['name'], $reps)) {
                $reps[trim($line['id'])] = trim($line['name']);
            }
        }

        return $reps;
    }

    public function showAnalysisSales(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-sales');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $repNames = []; 
        if ($request->getData('u') !== 'gdf') {
            $repNames = $this->getSalesReps('sd', $repNames);
        }

        if ($request->getData('u') !== 'sd') {
            $repNames = array_merge($repNames ?? [], $this->getSalesReps('gdf', $repNames));
        }

        asort($repNames);

        $countries = null;

        if ($request->getData('location') !== null) {
            $totalSales    = [];
            $accTotalSales = [];

            $salesCustomers = [];
            $customerCount  = [];

            $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

            $salesGroups   = [];
            $segmentGroups = [];

            foreach ($temp as $segment) {
                $salesGroups['All'][$segment]   = null;
                $segmentGroups['All'][$segment] = null;
            }

            $totalGroups = [
                'All'      => ['now' => 0.0, 'old' => 0.0],
            ];

            $newCustomers = 0;
            $lostCustomers = 0;

            $countries = null;

            // Set accounts
            $accounts = array_diff(StructureDefinitions::PL_ACCOUNTS['Sales'], StructureDefinitions::ACCOUNTS_DOMESTIC);
            if($request->getData('location') === 'Domestic' || $request->getData('location') === 'DE' || $request->getData('location')  === 'All') {
                if($request->getData('location')  === 'All') {
                    $accounts = array_merge($accounts, StructureDefinitions::ACCOUNTS_DOMESTIC);
                } else {
                    $accounts = StructureDefinitions::ACCOUNTS_DOMESTIC;
                }

                if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
                    $accounts[] = 8591;
                }
            } elseif($request->getData('location') !== 'Domestic' && $request->getData('location') !== 'DE' && $request->getData('location') !== 'Export' && $request->getData('location') !== 'All' &&$request->getData('location') !== null) {
                $countries = StructureDefinitions::getLocations($request->getData('location'));
            }

            // Set groups
            if($request->getData('segment') === null || $request->getData('segment') === 'All') {
                $groups = null;
            } else {
                $groups = StructureDefinitions::getSalesGroups((int) $request->getData('segment'));
            }

            // Set sales reps
            $reps = $request->getData('rep') === null || $request->getData('rep') === 'All' || !array_key_exists($request->getData('rep'), $repNames) ? null : [$request->getData('rep')];
            $repsSales = [];

            $monthlyNewCustomer = [];

            if ($request->getData('u') !== 'gdf') {
                $salesSD = $this->selectSalesAnalysis('showCustomOverviewAnalysis', $start, $current, 'sd', $accounts, $countries, $groups, $reps);
                $customersSD     = $this->selectSalesAnalysis('showCustomCustomerAnalysis', $startCurrent, $endCurrent, 'sd', $accounts, $countries, $groups, $reps);
                $customersSDLast = $this->selectSalesAnalysis('showCustomCustomerAnalysis', $startLast, $endLast, 'sd', $accounts, $countries, $groups, $reps);
                $customerSD = $this->selectSalesAnalysis('showCustomCustomerCountAnalysis', $start, $current, 'sd', $accounts, $countries, $groups, $reps);
                $groupsSD     = $this->selectSalesAnalysis('showCustomGroupsAnalysis', $startCurrent, $endCurrent, 'sd', $accounts, $countries, $groups, $reps);
                $groupsSDLast = $this->selectSalesAnalysis('showCustomGroupsAnalysis', $startLast, $endLast, 'sd', $accounts, $countries, $groups, $reps);

                $newCustomersSD = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'sd', $accounts, $countries, $groups, $reps);
                $lostCustomersSD = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'sd', $accounts, $countries, $groups, $reps);

                $newCustomers += count($newCustomersSD);
                $lostCustomers += count($lostCustomersSD);

                $newCustomersSD = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $startCurrent->createModify(-2), $endCurrent, 'sd', $accounts, $countries, $groups, $reps);
                $this->loopNewCustomerMonthly($newCustomersSD, $monthlyNewCustomer);

                $repsSD = $this->selectSalesAnalysis('showCustomRepsAnalysis', $startCurrent, $endCurrent, 'sd', $accounts, $countries, $groups, $reps);
                $repsSDLast = $this->selectSalesAnalysis('showCustomRepsAnalysis', $startLast, $endLast, 'sd', $accounts, $countries, $groups, $reps);

                $this->loopOverview($salesSD, $totalSales);
                $this->loopCustomer('now', $customersSD, $salesCustomers);
                $this->loopCustomer('old', $customersSDLast, $salesCustomers);
                $this->loopCustomerCount($customerSD, $customerCount);
                $this->loopArticleGroups('now', $groupsSD, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopArticleGroups('old', $groupsSDLast, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopReps('now', $repsSD, $repsSales);
                $this->loopReps('old', $repsSDLast, $repsSales);
            }

            if ($request->getData('u') !== 'sd') {
                $salesGDF = $this->selectSalesAnalysis('showCustomOverviewAnalysis', $start, $current, 'gdf', $accounts, $countries, $groups, $reps);
                $customersGDF     = $this->selectSalesAnalysis('showCustomCustomerAnalysis', $startCurrent, $endCurrent, 'gdf', $accounts, $countries, $groups, $reps);
                $customersGDFLast = $this->selectSalesAnalysis('showCustomCustomerAnalysis', $startLast, $endLast, 'gdf', $accounts, $countries, $groups, $reps);
                $customerGDF = $this->selectSalesAnalysis('showCustomCustomerCountAnalysis', $start, $current, 'gdf', $accounts, $countries, $groups, $reps);
                $groupsGDF     = $this->selectSalesAnalysis('showCustomGroupsAnalysis', $startCurrent, $endCurrent, 'gdf', $accounts, $countries, $groups, $reps);
                $groupsGDFLast = $this->selectSalesAnalysis('showCustomGroupsAnalysis', $startLast, $endLast, 'gdf', $accounts, $countries, $groups, $reps);

                $newCustomersGDF = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'gdf', $accounts, $countries, $groups, $reps);
                $lostCustomersGDF = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'gdf', $accounts, $countries, $groups, $reps);

                $newCustomers += count($newCustomersGDF);
                $lostCustomers += count($lostCustomersGDF);

                $newCustomersGDF = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $startCurrent->createModify(-2), $endCurrent, 'gdf', $accounts, $countries, $groups, $reps);
                $this->loopNewCustomerMonthly($newCustomersGDF, $monthlyNewCustomer);

                $repsGDF = $this->selectSalesAnalysis('showCustomRepsAnalysis', $startCurrent, $endCurrent, 'gdf', $accounts, $countries, $groups, $reps);
                $repsGDFLast = $this->selectSalesAnalysis('showCustomRepsAnalysis', $startLast, $endLast, 'gdf', $accounts, $countries, $groups, $reps);

                $this->loopOverview($salesGDF, $totalSales);
                $this->loopCustomer('now', $customersGDF, $salesCustomers);
                $this->loopCustomer('old', $customersGDFLast, $salesCustomers);
                $this->loopCustomerCount($customerGDF, $customerCount);
                $this->loopArticleGroups('now', $groupsGDF, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopArticleGroups('old', $groupsGDFLast, $salesGroups, $segmentGroups, $totalGroups);
                $this->loopReps('now', $repsGDF, $repsSales);
                $this->loopReps('old', $repsGDFLast, $repsSales);
            }

            uasort($repsSales, function($a, $b) { return -1*(($a['now'] ?? 0) <=> ($b['now'] ?? 0)); });

            $gini = null;

            if(isset($salesCustomers['now'])) {
                arsort($salesCustomers['now']);
                $gini['now'] = Lorenzkurve::getGiniCoefficient($salesCustomers['now']);
            }

            if(isset($salesCustomers['old'])) {
                arsort($salesCustomers['old']);
                $gini['old'] = Lorenzkurve::getGiniCoefficient($salesCustomers['old']);
            }

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
            $view->setData('monthlyNewCustomer', $monthlyNewCustomer);
            $view->setData('gini', $gini);
            $view->setData('salesGroups', $salesGroups);
            $view->setData('segmentGroups', $segmentGroups);
            $view->setData('totalGroups', $totalGroups);
            $view->setData('newCustomers', $newCustomers);
            $view->setData('lostCustomers', $lostCustomers);
            $view->setData('repsSales', $repsSales);
        }

        $view->setData('repNames', $repNames);

        return $view;
    }

    public function loopNewCustomerMonthly(array $result, array &$data) {
        foreach($result as $line) {
            $split = explode('.', $line['first']);

            $year = self::getFiscalYear((int) $split[0], (int) $split[1], (int) $this->app->config['fiscal_year']);
            $month = self::getFiscalMonth((int) $split[1]);

            if(!isset($data[$year])) {
                $data[$year] = [];
            }

            if(!isset($data[$year][$month])) {
                $data[$year][$month] = 0;
            }

            $data[$year][$month]++;
        }
    }
}
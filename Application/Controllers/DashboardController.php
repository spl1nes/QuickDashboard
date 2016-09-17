<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\Queries;
use QuickDashboard\Application\Models\StructureDefinitions;
use QuickDashboard\Application\WebApplication;

class DashboardController
{
    private $app = null;

    const MAX_PAST = 10;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
    }

    public function showOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/overview');

        $current = new SmartDateTime('now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $totalSales    = [];
        $accTotalSales = [];

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', StructureDefinitions::ACCOUNTS);
            foreach ($salesSD as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = $line['months'] - $this->app->config['fiscal_year'];
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                $totalSales[$fiscalYear][$fiscalMonth] = $line['sales'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', StructureDefinitions::ACCOUNTS);
            foreach ($salesGDF as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = ($line['months'] - $this->app->config['fiscal_year']);
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                    $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
                }

                $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
            }
        }

        foreach ($totalSales as $year => $months) {
            ksort($totalSales[$year]);

            foreach ($totalSales[$year] as $month => $value) {
                $prev                         = $accTotalSales[$year][$month - 1] ?? 0.0;
                $accTotalSales[$year][$month] = $prev + $value;
            }
        }

        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        unset($totalSales[$currentYear][$currentMonth]);
        unset($accTotalSales[$currentYear][$currentMonth]);

        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);
        $view->setData('sales', $totalSales);
        $view->setData('salesAcc', $accTotalSales);

        return $view;
    }

    public function showSalesOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-history');

        return $view;
    }

    public function showListMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-month');

        $current = new SmartDateTime('now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $totalSales        = [];
        $totalSalesLast    = [];
        $accTotalSales     = [];
        $accTotalSalesLast = [];

        if ($request->getData('u') !== 'gdf') {
            $salesSD     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'sd', StructureDefinitions::ACCOUNTS);
            $salesSDLast = $this->select('selectSalesDaily', $startLast, $endLast, 'sd', StructureDefinitions::ACCOUNTS);

            foreach ($salesSD as $line) {
                $totalSales[$line['days']] = $line['sales'];
            }

            foreach ($salesSDLast as $line) {
                $totalSalesLast[$line['days']] = $line['sales'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDFLast = $this->select('selectSalesDaily', $startLast, $endLast, 'gdf', StructureDefinitions::ACCOUNTS);
            $salesGDF     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'gdf', StructureDefinitions::ACCOUNTS);

            foreach ($salesGDF as $line) {
                if (!isset($totalSales[$line['days']])) {
                    $totalSales[$line['days']] = 0.0;
                }

                $totalSales[$line['days']] += $line['sales'];
            }

            foreach ($salesGDFLast as $line) {
                if (!isset($totalSalesLast[$line['days']])) {
                    $totalSalesLast[$line['days']] = 0.0;
                }

                $totalSalesLast[$line['days']] += $line['sales'];
            }
        }

        ksort($totalSales);
        ksort($totalSalesLast);

        $days = $endCurrent->format('d');
        for ($i = 1; $i <= $days; $i++) {
            $prev              = $accTotalSales[$i - 1] ?? 0;
            $accTotalSales[$i] = $prev + ($totalSales[$i] ?? 0);
        }

        $days = $endLast->format('d');
        for ($i = 1; $i <= $days; $i++) {
            $prev                  = $accTotalSalesLast[$i - 1] ?? 0;
            $accTotalSalesLast[$i] = $prev + ($totalSalesLast[$i] ?? 0);
        }

        $view->setData('sales', $totalSales);
        $view->setData('salesAcc', $accTotalSales);
        $view->setData('salesLast', $totalSalesLast);
        $view->setData('salesAccLast', $accTotalSalesLast);
        $view->setData('maxDays', max($endCurrent->format('d'), $endLast->format('d')));
        $view->setData('today', $current->format('d') - 1);

        return $view;
    }

    public function showListYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-year');

        $current = new SmartDateTime('now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-1 year');

        $totalSales    = [];
        $accTotalSales = [];

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', StructureDefinitions::ACCOUNTS);
            foreach ($salesSD as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = $line['months'] - $this->app->config['fiscal_year'];
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                $totalSales[$fiscalYear][$fiscalMonth] = $line['sales'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', StructureDefinitions::ACCOUNTS);
            foreach ($salesGDF as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = ($line['months'] - $this->app->config['fiscal_year']);
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                if (!isset($totalSales[$fiscalYear][$fiscalMonth])) {
                    $totalSales[$fiscalYear][$fiscalMonth] = 0.0;
                }

                $totalSales[$fiscalYear][$fiscalMonth] += $line['sales'];
            }
        }

        foreach ($totalSales as $year => $months) {
            ksort($totalSales[$year]);

            for ($i = 1; $i <= 12; $i++) {
                $prev                     = $accTotalSales[$year][$i - 1] ?? 0.0;
                $accTotalSales[$year][$i] = $prev + ($totalSales[$year][$i] ?? 0);
            }
        }

        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        $view->setData('sales', $totalSales[$currentYear]);
        $view->setData('salesAcc', $accTotalSales[$currentYear]);
        $view->setData('salesLast', $totalSales[$currentYear - 1]);
        $view->setData('salesAccLast', $accTotalSales[$currentYear - 1]);
        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);

        return $view;
    }

    public function showLocationMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime('now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        return $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showLocationYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime('now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        return $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showLocation(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-location');

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

        if ($request->getData('u') !== 'gdf') {
            $countrySD     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'sd', StructureDefinitions::ACCOUNTS);
            $countrySDLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'sd', StructureDefinitions::ACCOUNTS);

            foreach ($countrySD as $line) {
                $region = StructureDefinitions::getRegion($line['countryChar']);
                if (!isset($salesRegion['now'][$region])) {
                    $salesRegion['now'][$region] = 0.0;
                }

                $salesRegion['now'][$region] += $line['sales'];

                $devundev = StructureDefinitions::getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['now'][$devundev])) {
                    $salesDevUndev['now'][$devundev] = 0.0;
                }

                $salesDevUndev['now'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['now'][$iso3166Char3])) {
                    $salesCountry['now'][$iso3166Char3] = 0.0;
                }

                $salesCountry['now'][$iso3166Char3] += $line['sales'];
            }

            foreach ($countrySDLast as $line) {
                $region = StructureDefinitions::getRegion($line['countryChar']);
                if (!isset($salesRegion['old'][$region])) {
                    $salesRegion['old'][$region] = 0.0;
                }

                $salesRegion['old'][$region] += $line['sales'];

                $devundev = StructureDefinitions::getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['old'][$devundev])) {
                    $salesDevUndev['old'][$devundev] = 0.0;
                }

                $salesDevUndev['old'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['old'][$iso3166Char3])) {
                    $salesCountry['old'][$iso3166Char3] = 0.0;
                }

                $salesCountry['old'][$iso3166Char3] += $line['sales'];
            }

            $domesticSDLast = $this->select('selectSales', $startLast, $endLast, 'sd', StructureDefinitions::ACCOUNTS_DOMESTIC);
            $domesticSD     = $this->select('selectSales', $startCurrent, $endCurrent, 'sd', StructureDefinitions::ACCOUNTS_DOMESTIC);

            $allSD     = $this->select('selectSales', $startCurrent, $endCurrent, 'sd', StructureDefinitions::ACCOUNTS);
            $allSDLast = $this->select('selectSales', $startLast, $endLast, 'sd', StructureDefinitions::ACCOUNTS);
        }

        if ($request->getData('u') !== 'sd') {
            $countryGDF     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'gdf', StructureDefinitions::ACCOUNTS);
            $countryGDFLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'gdf', StructureDefinitions::ACCOUNTS);

            foreach ($countryGDF as $line) {
                $region = StructureDefinitions::getRegion($line['countryChar']);
                if (!isset($salesRegion['now'][$region])) {
                    $salesRegion['now'][$region] = 0.0;
                }

                $salesRegion['now'][$region] += $line['sales'];

                $devundev = StructureDefinitions::getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['now'][$devundev])) {
                    $salesDevUndev['now'][$devundev] = 0.0;
                }

                $salesDevUndev['now'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['now'][$iso3166Char3])) {
                    $salesCountry['now'][$iso3166Char3] = 0.0;
                }

                $salesCountry['now'][$iso3166Char3] += $line['sales'];
            }

            foreach ($countryGDFLast as $line) {
                $region = StructureDefinitions::getRegion($line['countryChar']);
                if (!isset($salesRegion['old'][$region])) {
                    $salesRegion['old'][$region] = 0.0;
                }

                $salesRegion['old'][$region] += $line['sales'];

                $devundev = StructureDefinitions::getDevelopedUndeveloped($line['countryChar']);
                if (!isset($salesDevUndev['old'][$devundev])) {
                    $salesDevUndev['old'][$devundev] = 0.0;
                }

                $salesDevUndev['old'][$devundev] += $line['sales'];

                $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
                if (!isset($salesCountry['old'][$iso3166Char3])) {
                    $salesCountry['old'][$iso3166Char3] = 0.0;
                }

                $salesCountry['old'][$iso3166Char3] += $line['sales'];
            }

            $domesticGDFLast = $this->select('selectSales', $startLast, $endLast, 'gdf', StructureDefinitions::ACCOUNTS_DOMESTIC);
            $domesticGDF     = $this->select('selectSales', $startCurrent, $endCurrent, 'gdf', StructureDefinitions::ACCOUNTS_DOMESTIC);

            $allGDF     = $this->select('selectSales', $startCurrent, $endCurrent, 'gdf', StructureDefinitions::ACCOUNTS);
            $allGDFLast = $this->select('selectSales', $startLast, $endLast, 'gdf', StructureDefinitions::ACCOUNTS);
        }

        $salesExportDomestic['now']['Domestic'] = ($domesticSD[0]['sales'] ?? 0) + ($domesticGDF[0]['sales'] ?? 0);
        $salesExportDomestic['old']['Domestic'] = ($domesticSDLast[0]['sales'] ?? 0) + ($domesticGDFLast[0]['sales'] ?? 0);
        $salesExportDomestic['now']['Export']   = ($allGDF[0]['sales'] ?? 0) + ($allSD[0]['sales'] ?? 0) - $salesExportDomestic['now']['Domestic'];
        $salesExportDomestic['old']['Export']   = ($allGDFLast[0]['sales'] ?? 0) + ($allSDLast[0]['sales'] ?? 0) - $salesExportDomestic['old']['Domestic'];
        $salesCountry['now']['DEU']             = $salesExportDomestic['now']['Domestic'];
        $salesCountry['old']['DEU']             = $salesExportDomestic['old']['Domestic'];

        arsort($salesCountry['now']);

        $salesDevUndev['now']['Developed'] += array_sum($salesExportDomestic['now']) - array_sum($salesDevUndev['now']);
        $salesDevUndev['old']['Developed'] += array_sum($salesExportDomestic['old']) - array_sum($salesDevUndev['old']);

        $salesRegion['now']['Europe'] += array_sum($salesExportDomestic['now']) - array_sum($salesRegion['now']);
        $salesRegion['old']['Europe'] += array_sum($salesExportDomestic['old']) - array_sum($salesRegion['old']);

        $view->setData('salesCountry', $salesCountry);
        $view->setData('salesRegion', $salesRegion);
        $view->setData('salesDevUndev', $salesDevUndev);
        $view->setData('salesExportDomestic', $salesExportDomestic);

        return $view;
    }

    public function showArticle(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-article');

        return $view;
    }

    public function showCustomers(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-customer');

        return $view;
    }

    public function showReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-reps');

        return $view;
    }

    public function showCosts(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Costs/costs-positions');

        return $view;
    }

    public function showAnalysisReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-reps');

        return $view;
    }

    private function calcCurrentMonth(\DateTime $date) : int
    {
        return ((int) $date->format('m') - $this->app->config['fiscal_year'] - 1) % 12 + 1;
    }

    private function getFiscalYearStart(SmartDateTime $date) : SmartDateTime
    {
        $newDate = new SmartDateTime($date->format('Y') . '-' . $date->format('m') . '-01');

        return $newDate->modify('-' . $this->calcCurrentMonth($date) . ' month');
    }

    private function select(string $selectQuery, \DateTime $start, \DateTime $end, string $company, array $accounts) : array
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(Queries::{$selectQuery}($start, $end, $company, $accounts));
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }
}
<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\ArrayUtils;
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

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
            foreach ($salesSD as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = $line['months'] - $this->app->config['fiscal_year'];
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                $totalSales[$fiscalYear][$fiscalMonth] = $line['sales'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
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

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'sd', $accounts);
            $salesSDLast = $this->select('selectSalesDaily', $startLast, $endLast, 'sd', $accounts);

            foreach ($salesSD as $line) {
                $totalSales[$line['days']] = $line['sales'];
            }

            foreach ($salesSDLast as $line) {
                $totalSalesLast[$line['days']] = $line['sales'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDFLast = $this->select('selectSalesDaily', $startLast, $endLast, 'gdf', $accounts);
            $salesGDF     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'gdf', $accounts);

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

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
            foreach ($salesSD as $line) {
                $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
                $mod         = $line['months'] - $this->app->config['fiscal_year'];
                $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

                $totalSales[$fiscalYear][$fiscalMonth] = $line['sales'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
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

        $accounts          = StructureDefinitions::ACCOUNTS;
        $accounts_DOMESTIC = StructureDefinitions::ACCOUNTS_DOMESTIC;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[]          = 8591;
            $accounts_DOMESTIC[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $countrySD     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'sd', $accounts);
            $countrySDLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'sd', $accounts);

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

            $domesticSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts_DOMESTIC);
            $domesticSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts_DOMESTIC);

            $allSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts);
            $allSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts);
        }

        if ($request->getData('u') !== 'sd') {
            $countryGDF     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'gdf', $accounts);
            $countryGDFLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'gdf', $accounts);

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

            $domesticGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts_DOMESTIC);
            $domesticGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts_DOMESTIC);

            $allGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts);
            $allGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts);
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

    public function showArticleMonth(RequestAbstract $request, ResponseAbstract $response)
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

        return $this->showArticle($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showArticleYear(RequestAbstract $request, ResponseAbstract $response)
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

        return $this->showArticle($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showArticle(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-article');

        $salesGroups   = [];
        $segmentGroups = [];
        $totalGroups   = ['now' => 0.0, 'old' => 0.0];

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsSD     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accounts);

            foreach ($groupsSD as $line) {
                $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);

                if ($group === 0) {
                    continue;
                }

                $segment = StructureDefinitions::getSegmentOfArticle($line['costcenter']);

                if(!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                    continue;
                }

                if (!isset($salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['now'])) {
                    $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['now'] = 0.0;
                }

                if (!isset($segmentGroups[StructureDefinitions::NAMING[$segment]]['now'])) {
                    $segmentGroups[StructureDefinitions::NAMING[$segment]]['now'] = 0.0;
                }

                $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['now'] += $line['sales'];
                $segmentGroups[StructureDefinitions::NAMING[$segment]]['now'] += $line['sales'];
                $totalGroups['now'] += $line['sales'];
            }

            foreach ($groupsSDLast as $line) {
                $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);

                if ($group === 0) {
                    continue;
                }

                $segment = StructureDefinitions::getSegmentOfArticle($line['costcenter']);

                if(!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                    continue;
                }

                if (!isset($salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['old'])) {
                    $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['old'] = 0.0;
                }

                if (!isset($segmentGroups[StructureDefinitions::NAMING[$segment]]['old'])) {
                    $segmentGroups[StructureDefinitions::NAMING[$segment]]['old'] = 0.0;
                }

                $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['old'] += $line['sales'];
                $segmentGroups[StructureDefinitions::NAMING[$segment]]['old'] += $line['sales'];
                $totalGroups['old'] += $line['sales'];
            }
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsGDF     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts);

            foreach ($groupsGDF as $line) {
                $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);

                if ($group === 0) {
                    continue;
                }

                $segment = StructureDefinitions::getSegmentOfArticle($line['costcenter']);

                if(!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                    continue;
                }

                if (!isset($salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['now'])) {
                    $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['now'] = 0.0;
                }

                if (!isset($segmentGroups[StructureDefinitions::NAMING[$segment]]['now'])) {
                    $segmentGroups[StructureDefinitions::NAMING[$segment]]['now'] = 0.0;
                }

                $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['now'] += $line['sales'];
                $segmentGroups[StructureDefinitions::NAMING[$segment]]['now'] += $line['sales'];
                $totalGroups['now'] += $line['sales'];
            }

            foreach ($groupsGDFLast as $line) {
                $group = StructureDefinitions::getGroupOfArticle($line['costcenter']);

                if ($group === 0) {
                    continue;
                }

                $segment = StructureDefinitions::getSegmentOfArticle($line['costcenter']);

                if(!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                    continue;
                }

                if (!isset($salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['old'])) {
                    $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['old'] = 0.0;
                }

                if (!isset($segmentGroups[StructureDefinitions::NAMING[$segment]]['old'])) {
                    $segmentGroups[StructureDefinitions::NAMING[$segment]]['old'] = 0.0;
                }

                $salesGroups[StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]]['old'] += $line['sales'];
                $segmentGroups[StructureDefinitions::NAMING[$segment]]['old'] += $line['sales'];
                $totalGroups['old'] += $line['sales'];
            }
        }

        $view->setData('salesGroups', $salesGroups);
        $view->setData('segmentGroups', $segmentGroups);
        $view->setData('totalGroups', $totalGroups);

        return $view;
    }

    public function showCustomersMonth(RequestAbstract $request, ResponseAbstract $response)
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

        return $this->showCustomers($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showCustomersYear(RequestAbstract $request, ResponseAbstract $response)
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

        return $this->showCustomers($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showCustomers(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-customer');

        $salesGroups = [];
        $totalGroups = ['now' => 0.0, 'old' => 0.0];

        $accounts = StructureDefinitions::ACCOUNTS;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsSD     = $this->select('selectCustomerGroup', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast = $this->select('selectCustomerGroup', $startLast, $endLast, 'sd', $accounts);

            foreach ($groupsSD as $line) {
                $customerGroup = StructureDefinitions::CUSTOMER_GROUP[$line['cgroup']];
                if (!isset($salesGroups[$customerGroup]['now'])) {
                    $salesGroups[$customerGroup]['now'] = 0.0;
                }

                $salesGroups[$customerGroup]['now'] += $line['sales'];
                $totalGroups['now'] += $line['sales'];
            }

            foreach ($groupsSDLast as $line) {
                $customerGroup = StructureDefinitions::CUSTOMER_GROUP[$line['cgroup']];
                if (!isset($salesGroups[$customerGroup]['old'])) {
                    $salesGroups[$customerGroup]['old'] = 0.0;
                }

                $salesGroups[$customerGroup]['old'] += $line['sales'];
                $totalGroups['old'] += $line['sales'];
            }
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsGDF     = $this->select('selectCustomerGroup', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast = $this->select('selectCustomerGroup', $startLast, $endLast, 'gdf', $accounts);

            foreach ($groupsGDF as $line) {
                $customerGroup = StructureDefinitions::CUSTOMER_GROUP[$line['cgroup']];
                if (!isset($salesGroups[$customerGroup]['now'])) {
                    $salesGroups[$customerGroup]['now'] = 0.0;
                }

                $salesGroups[$customerGroup]['now'] += $line['sales'];
                $totalGroups['now'] += $line['sales'];
            }

            foreach ($groupsGDFLast as $line) {
                $customerGroup = StructureDefinitions::CUSTOMER_GROUP[$line['cgroup']];
                if (!isset($salesGroups[$customerGroup]['old'])) {
                    $salesGroups[$customerGroup]['old'] = 0.0;
                }

                $salesGroups[$customerGroup]['old'] += $line['sales'];
                $totalGroups['old'] += $line['sales'];
            }
        }

        $view->setData('salesGroups', $salesGroups);
        $view->setData('totalGroups', $totalGroups);

        return $view;
    }

    public function showReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-reps');

        return $view;
    }

    public function showAnalysisReps(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Analysis/analysis-reps');

        return $view;
    }

    public function showPLMonth(RequestAbstract $request, ResponseAbstract $response)
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

        return $this->showPL($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showPLYear(RequestAbstract $request, ResponseAbstract $response)
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

        return $this->showPL($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
    }

    public function showPL(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Reporting/pl.tpl.php');

        $accountPositions = [];
        $accounts         = ArrayUtils::arrayFlatten(StructureDefinitions::PL_ACCOUNTS);

        if ($request->getData('u') !== 'gdf') {
            $accountsSD     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', $accounts);
            $accountsSDLast = $this->select('selectEntries', $startLast, $endLast, 'sd', $accounts);

            foreach ($accountsSD as $line) {
                $position = StructureDefinitions::getAccountPLPosition($line['Konto']);
                if (!isset($accountPositions[$position]['now'])) {
                    $accountPositions[$position]['now'] = 0.0;
                }

                $accountPositions[$position]['now'] += $line['entries'];
            }

            foreach ($accountsSDLast as $line) {
                $position = StructureDefinitions::getAccountPLPosition($line['Konto']);
                if (!isset($accountPositions[$position]['old'])) {
                    $accountPositions[$position]['old'] = 0.0;
                }

                $accountPositions[$position]['old'] += $line['entries'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $accountsGDF     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', $accounts);
            $accountsGDFLast = $this->select('selectEntries', $startLast, $endLast, 'sd', $accounts);

            foreach ($accountsGDF as $line) {
                $position = StructureDefinitions::getAccountPLPosition($line['Konto']);
                if (!isset($accountPositions[$position]['now'])) {
                    $accountPositions[$position]['now'] = 0.0;
                }

                $accountPositions[$position]['now'] += $line['entries'];
            }

            foreach ($accountsGDFLast as $line) {
                $position = StructureDefinitions::getAccountPLPosition($line['Konto']);
                if (!isset($accountPositions[$position]['old'])) {
                    $accountPositions[$position]['old'] = 0.0;
                }

                $accountPositions[$position]['old'] += $line['entries'];
            }
        }

        $view->setData('accountPositions', $accountPositions);

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
        $query->raw(Queries::{$selectQuery}($start, $end, $accounts));
        $result = $query->execute()->fetchAll();
        $result = empty($result) ? [] : $result;

        return $result;
    }
}
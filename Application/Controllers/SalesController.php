<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Math\Finance\Lorenzkurve;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\StructureDefinitions;

class SalesController extends DashboardController
{
    public function showListMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-month');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
            $today   = (int) $current->format('d');
        } else {
            $today = (int) $current->format('d') - 1;
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

        $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'sd', $accounts);
            $salesSDLast = $this->select('selectSalesDaily', $startLast, $endLast, 'sd', $accounts);

            $this->loopListMonth($salesSD, $totalSales);
            $this->loopListMonth($salesSDLast, $totalSalesLast);
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDFLast = $this->select('selectSalesDaily', $startLast, $endLast, 'gdf', $accounts);
            $salesGDF     = $this->select('selectSalesDaily', $startCurrent, $endCurrent, 'gdf', $accounts);

            $this->loopListMonth($salesGDF, $totalSales);
            $this->loopListMonth($salesGDFLast, $totalSalesLast);
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
        $view->setData('today', $today);
        $view->setData('date', $current);
        $view->setData('type', 'isolated');
        $view->setData('nowDate', $startCurrent);
        $view->setData('oldDate', $startLast);
        $view->setData('title', 'Sales Month Isolated');

        return $view;
    }

    private function loopListMonth(array $resultset, array &$totalSales)
    {
        foreach ($resultset as $line) {
            if (!isset($totalSales[$line['days']])) {
                $totalSales[$line['days']] = 0.0;
            }

            $totalSales[$line['days']] += $line['sales'];
        }
    }

    public function showListYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-list-year');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-1 year');

        $totalSales    = [
            'All'      => [],
            'Domestic' => [],
            'Export'   => [],
        ];
        $accTotalSales = [
            'All'      => [],
            'Domestic' => [],
            'Export'   => [],
        ];

        $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
            $this->loopListYear($salesSD, $totalSales);
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
            $this->loopListYear($salesGDF, $totalSales);
        }

        foreach ($totalSales['All'] as $year => $months) {
            ksort($totalSales['All'][$year]);
            ksort($totalSales['Domestic'][$year]);
            ksort($totalSales['Export'][$year]);

            for ($i = 1; $i <= 12; $i++) {
                $prev                                 = $accTotalSales['All'][$year][$i - 1] ?? 0.0;
                $accTotalSales['All'][$year][$i]      = $prev + ($totalSales['All'][$year][$i] ?? 0);
                $prev                                 = $accTotalSales['Domestic'][$year][$i - 1] ?? 0.0;
                $accTotalSales['Domestic'][$year][$i] = $prev + ($totalSales['Domestic'][$year][$i] ?? 0);
                $prev                                 = $accTotalSales['Export'][$year][$i - 1] ?? 0.0;
                $accTotalSales['Export'][$year][$i]   = $prev + ($totalSales['Export'][$year][$i] ?? 0);
            }
        }

        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

        $view->setData('sales', $totalSales['All'][$currentYear]);
        $view->setData('salesAcc', $accTotalSales['All'][$currentYear]);
        $view->setData('salesLast', $totalSales['All'][$currentYear - 1]);
        $view->setData('salesAccLast', $accTotalSales['All'][$currentYear - 1]);
        $view->setData('salesDomestic', $totalSales['Domestic'][$currentYear]);
        $view->setData('salesAccDomestic', $accTotalSales['Domestic'][$currentYear]);
        $view->setData('salesLastDomestic', $totalSales['Domestic'][$currentYear - 1]);
        $view->setData('salesAccLastDomestic', $accTotalSales['Domestic'][$currentYear - 1]);
        $view->setData('salesExport', $totalSales['Export'][$currentYear]);
        $view->setData('salesAccExport', $accTotalSales['Export'][$currentYear]);
        $view->setData('salesLastExport', $totalSales['Export'][$currentYear - 1]);
        $view->setData('salesAccLastExport', $accTotalSales['Export'][$currentYear - 1]);
        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);
        $view->setData('date', $current);
        $view->setData('type', 'accumulated');
        $view->setData('title', 'Sales Year Isolated');

        return $view;
    }

    private function loopListYear(array $resultset, array &$totalSales)
    {
        foreach ($resultset as $line) {
            $fiscalYear  = $line['months'] - $this->app->config['fiscal_year'] < 0 ? $line['years'] - 1 : $line['years'];
            $mod         = ($line['months'] - $this->app->config['fiscal_year']);
            $fiscalMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;

            if (!isset($totalSales['All'][$fiscalYear][$fiscalMonth])) {
                $totalSales['All'][$fiscalYear][$fiscalMonth]      = 0.0;
                $totalSales['Domestic'][$fiscalYear][$fiscalMonth] = 0.0;
                $totalSales['Export'][$fiscalYear][$fiscalMonth]   = 0.0;
            }

            $totalSales['All'][$fiscalYear][$fiscalMonth] += $line['sales'];

            $region = StructureDefinitions::getDomesticExportAccount($line['account']);
            $totalSales[$region][$fiscalYear][$fiscalMonth] += $line['sales'];
        }
    }

    public function showLocationMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $view = $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'isolated');
        $view->setData('title', 'Sales Location Isolated');

        return $view;
    }

    public function showLocationYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $view = $this->showLocation($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'accumulated');
        $view->setData('title', 'Sales Location Accumulated');

        return $view;
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

        $accounts          = array_diff(StructureDefinitions::PL_ACCOUNTS['Sales'], StructureDefinitions::ACCOUNTS_DOMESTIC);
        $accounts_DOMESTIC = StructureDefinitions::ACCOUNTS_DOMESTIC;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts_DOMESTIC[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $countrySD     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'sd', $accounts);
            $countrySDLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'sd', $accounts);

            $this->loopLocation('now', $countrySD, $salesRegion, $salesDevUndev, $salesCountry);
            $this->loopLocation('old', $countrySDLast, $salesRegion, $salesDevUndev, $salesCountry);

            $domesticSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts_DOMESTIC);
            $domesticSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts_DOMESTIC);

            $allSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts);
            $allSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts);
        }

        if ($request->getData('u') !== 'sd') {
            $countryGDF     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'gdf', $accounts);
            $countryGDFLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'gdf', $accounts);

            $this->loopLocation('now', $countryGDF, $salesRegion, $salesDevUndev, $salesCountry);
            $this->loopLocation('old', $countryGDFLast, $salesRegion, $salesDevUndev, $salesCountry);

            $domesticGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts_DOMESTIC);
            $domesticGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts_DOMESTIC);

            $allGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts);
            $allGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts);
        }

        $salesExportDomestic['now']['Domestic'] = ($domesticSD[0]['sales'] ?? 0) + ($domesticGDF[0]['sales'] ?? 0);
        $salesExportDomestic['old']['Domestic'] = ($domesticSDLast[0]['sales'] ?? 0) + ($domesticGDFLast[0]['sales'] ?? 0);
        $salesExportDomestic['now']['Export']   = ($allGDF[0]['sales'] ?? 0) + ($allSD[0]['sales'] ?? 0);
        $salesExportDomestic['old']['Export']   = ($allGDFLast[0]['sales'] ?? 0) + ($allSDLast[0]['sales'] ?? 0);
        $salesCountry['now']['DEU']             = $salesExportDomestic['now']['Domestic'];
        $salesCountry['old']['DEU']             = $salesExportDomestic['old']['Domestic'];

        arsort($salesCountry['now']);

        $salesDevUndev['now']['Developed'] = $salesExportDomestic['now']['Domestic'] + $salesDevUndev['now']['Developed'];
        $salesDevUndev['old']['Developed'] = $salesExportDomestic['old']['Domestic'] + $salesDevUndev['old']['Developed'];
        $salesDevUndev['now']['Undeveloped'] = $salesExportDomestic['now']['Domestic'] + $salesExportDomestic['now']['Export'] - $salesDevUndev['now']['Developed'];
        $salesDevUndev['old']['Undeveloped'] = $salesExportDomestic['old']['Domestic'] + $salesExportDomestic['old']['Export'] - $salesDevUndev['old']['Developed'];

        $salesRegion['now']['Europe'] = $salesExportDomestic['now']['Domestic'] + $salesRegion['now']['Europe'];
        $salesRegion['old']['Europe'] = $salesExportDomestic['old']['Domestic'] + $salesRegion['old']['Europe'];
        $salesRegion['now']['Europe'] -= array_sum($salesRegion['now']) - ($salesExportDomestic['now']['Domestic'] + $salesExportDomestic['now']['Export']);
        $salesRegion['old']['Europe'] -= array_sum($salesRegion['old']) - ($salesExportDomestic['old']['Domestic'] + $salesExportDomestic['old']['Export']);

        $view->setData('salesCountry', $salesCountry);
        $view->setData('salesRegion', $salesRegion);
        $view->setData('salesDevUndev', $salesDevUndev);
        $view->setData('salesExportDomestic', $salesExportDomestic);
        $view->setData('date', $endCurrent);

        return $view;
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

    public function showCountriesMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $view = $this->showCountry($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'isolated');
        $view->setData('title', 'Sales Country Isolated');

        return $view;
    }

    public function showCountriesYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $view = $this->showCountry($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'accumulated');
        $view->setData('title', 'Sales Country Accumulated');

        return $view;
    }

    public function showCountry(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-country');

        $salesCountry        = [];

        $domesticSD      = [];
        $domesticGDF     = [];
        $domesticSDLast  = [];
        $domesticGDFLast = [];
        $allGDF          = [];
        $allSD           = [];
        $allGDFLast      = [];
        $allSDLast       = [];

        $sum = ['old' => 0, 'now' => 0];

        $accounts          = array_diff(StructureDefinitions::PL_ACCOUNTS['Sales'], StructureDefinitions::ACCOUNTS_DOMESTIC);
        $accounts_DOMESTIC = StructureDefinitions::ACCOUNTS_DOMESTIC;
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts_DOMESTIC[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $countrySD     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'sd', $accounts);
            $countrySDLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'sd', $accounts);

            $this->loopCountry('now', $countrySD, $salesCountry, $sum);
            $this->loopCountry('old', $countrySDLast, $salesCountry, $sum);

            $domesticSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts_DOMESTIC);
            $domesticSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts_DOMESTIC);

            $allSD     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'sd', $accounts);
            $allSDLast = $this->select('selectAccounts', $startLast, $endLast, 'sd', $accounts);
        }

        if ($request->getData('u') !== 'sd') {
            $countryGDF     = $this->select('selectSalesByCountry', $startCurrent, $endCurrent, 'gdf', $accounts);
            $countryGDFLast = $this->select('selectSalesByCountry', $startLast, $endLast, 'gdf', $accounts);

            $this->loopCountry('now', $countryGDF, $salesCountry, $sum);
            $this->loopCountry('old', $countryGDFLast, $salesCountry, $sum);

            $domesticGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts_DOMESTIC);
            $domesticGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts_DOMESTIC);

            $allGDF     = $this->select('selectAccounts', $startCurrent, $endCurrent, 'gdf', $accounts);
            $allGDFLast = $this->select('selectAccounts', $startLast, $endLast, 'gdf', $accounts);
        }

        $deu_wrong_now = $salesCountry['DEU']['now'] ?? 0;
        $deu_wrong_old = $salesCountry['DEU']['old'] ?? 0;

        $salesCountry['DEU']['now'] = ($domesticSD[0]['sales'] ?? 0) + ($domesticGDF[0]['sales'] ?? 0);
        $salesCountry['DEU']['old'] = ($domesticSDLast[0]['sales'] ?? 0) + ($domesticGDFLast[0]['sales'] ?? 0);

        if(!isset($salesCountry['???'])) {
            $salesCountry['???'] = [];
        }

        $salesCountry['???']['now'] = ($allGDF[0]['sales'] ?? 0) + ($allSD[0]['sales'] ?? 0) - ($sum['now'] - $deu_wrong_now - ($salesCountry['???']['now'] ?? 0));
        $salesCountry['???']['old'] = ($allGDFLast[0]['sales'] ?? 0) + ($allSDLast[0]['sales'] ?? 0) - ($sum['old'] - $deu_wrong_old - ($salesCountry['???']['old'] ?? 0));

        $view->setData('salesCountry', $salesCountry);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopCountry(string $period, array $resultset, array &$salesCountry, array &$sum)
    {
        foreach ($resultset as $line) {
            if (!isset($line['countryChar'])) {
                continue;
            }

            $iso3166Char3 = ltrim(ISO3166TwoEnum::getName(trim(strtoupper($line['countryChar']))), '_');
            $iso3166Char3 = trim($iso3166Char3) === '' ? '???' : $iso3166Char3;
            if (!isset($salesCountry[$iso3166Char3][$period])) {
                $salesCountry[$iso3166Char3][$period] = 0.0;
            }

            $salesCountry[$iso3166Char3][$period] += $line['sales'];
            $sum[$period] += $line['sales'];
        }
    }

    public function showArticleMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $view = $this->showArticle($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'isolated');
        $view->setData('title', 'Sales Segments Isolated');

        return $view;
    }

    public function showArticleYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $view = $this->showArticle($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'accumulated');
        $view->setData('title', 'Sales Segments Accumulated');

        return $view;
    }

    public function showArticle(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-segmentation');

        $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

        $salesGroups   = [];
        $segmentGroups = [];

        foreach ($temp as $segment) {
            $salesGroups['All'][$segment]   = null;
            $segmentGroups['All'][$segment] = null;

            $salesGroups['Domestic'][$segment]   = null;
            $segmentGroups['Domestic'][$segment] = null;

            $salesGroups['Export'][$segment]   = null;
            $segmentGroups['Export'][$segment] = null;
        }

        $totalGroups = [
            'All'      => ['now' => 0.0, 'old' => 0.0],
            'Domestic' => ['now' => 0.0, 'old' => 0.0],
            'Export'   => ['now' => 0.0, 'old' => 0.0],
        ];

        $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsSD     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accounts);

            $this->loopArticleGroups('now', $groupsSD, $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroups('old', $groupsSDLast, $salesGroups, $segmentGroups, $totalGroups);
        }

        if ($request->getData('u') !== 'sd') {
            $groupsGDF     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts);

            $this->loopArticleGroups('now', $groupsGDF, $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroups('old', $groupsGDFLast, $salesGroups, $segmentGroups, $totalGroups);
        }

        $view->setData('salesGroups', $salesGroups);
        $view->setData('segmentGroups', $segmentGroups);
        $view->setData('totalGroups', $totalGroups);
        $view->setData('date', $endCurrent);

        return $view;
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
                $salesGroups['Domestic'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] = 0.0;
                $salesGroups['Export'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period]   = 0.0;
            }

            if (!isset($segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period])) {
                $segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period]      = 0.0;
                $segmentGroups['Domestic'][StructureDefinitions::NAMING[$segment]][$period] = 0.0;
                $segmentGroups['Export'][StructureDefinitions::NAMING[$segment]][$period]   = 0.0;
            }

            $salesGroups['All'][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups['All'][StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];
            $totalGroups['All'][$period] += $line['sales'];

            $region = StructureDefinitions::getDomesticExportAccount($line['account']);
            $salesGroups[$region][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups[$region][StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];
            $totalGroups[$region][$period] += $line['sales'];
        }
    }

    public function showRepsMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $view = $this->showReps($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'isolated');
        $view->setData('title', 'Sales Reps Isolated');

        return $view;
    }

    public function showRepsYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $view = $this->showReps($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'accumulated');
        $view->setData('title', 'Sales Reps Accumulated');

        return $view;
    }

    public function showReps(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-reps');

        $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $repsSD     = $this->select('selectSalesRep', $startCurrent, $endCurrent, 'sd', $accounts);
            $repsSDLast = $this->select('selectSalesRep', $startLast, $endLast, 'sd', $accounts);

            foreach ($repsSD as $line) {
                if(!isset($repsSales[$line['rep']]['now'])) {
                    $repsSales[$line['rep']]['now'] = 0.0;
                }

                $repsSales[$line['rep']]['now'] += $line['sales'];
            }

            foreach ($repsSDLast as $line) {
                if(!isset($repsSales[$line['rep']]['old'])) {
                    $repsSales[$line['rep']]['old'] = 0.0;
                }

                $repsSales[$line['rep']]['old'] += $line['sales'];
            }
        }

        if ($request->getData('u') !== 'sd') {
            $repsGDF     = $this->select('selectSalesRep', $startCurrent, $endCurrent, 'gdf', $accounts);
            $repsGDFLast = $this->select('selectSalesRep', $startLast, $endLast, 'gdf', $accounts);

            foreach ($repsGDF as $line) {
                if(!isset($repsSales[$line['rep']]['now'])) {
                    $repsSales[$line['rep']]['now'] = 0.0;
                }

                $repsSales[$line['rep']]['now'] += $line['sales'];
            }

            foreach ($repsGDFLast as $line) {
                if(!isset($repsSales[$line['rep']]['old'])) {
                    $repsSales[$line['rep']]['old'] = 0.0;
                }

                $repsSales[$line['rep']]['old'] += $line['sales'];
            }
        }

        $repsSales = $repsSales ?? [];
        uasort($repsSales, function($a, $b) { return -1*(($a['now'] ?? 0) <=> ($b['now'] ?? 0)); });

        $view->setData('repsSales', $repsSales);
        $view->setData('date', $endCurrent);

        return $view;
    }

    public function showCustomersMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $current->getStartOfMonth();
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $startLast->getEndOfMonth();

        $view = $this->showCustomers($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'isolated');
        $view->setData('title', 'Sales Customers Isolated');

        return $view;
    }

    public function showCustomersYear(RequestAbstract $request, ResponseAbstract $response)
    {
        $current = new SmartDateTime($request->getData('t') ?? 'now');
        if ($current->format('d') < self::MAX_PAST) {
            $current->modify('-' . self::MAX_PAST . ' day');
            $current = $current->getEndOfMonth();
        }

        $startCurrent = $this->getFiscalYearStart($current);
        $endCurrent   = $current->getEndOfMonth();
        $startLast    = clone $startCurrent;
        $startLast    = $startLast->modify('-1 year');
        $endLast      = $endCurrent->createModify(-1);

        $view = $this->showCustomers($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'accumulated');
        $view->setData('title', 'Sales Customers Accumulated');

        return $view;
    }

    public function showCustomers(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-customer');

        $current      = new SmartDateTime($request->getData('t') ?? 'now');
        $currentYear  = $current->format('m') - $this->app->config['fiscal_year'] < 0 ? $current->format('Y') - 1 : $current->format('Y');
        $mod          = (int) $current->format('m') - $this->app->config['fiscal_year'];
        $currentMonth = (($mod < 0 ? 12 + $mod : $mod) % 12) + 1;
        $start        = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $salesGroups    = [];
        $totalGroups    = ['now' => 0.0, 'old' => 0.0];
        $salesCustomers = [];
        $customerCount  = [];

        $newCustomers = 0;
        $lostCustomers = 0;

        $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $groupsSD        = $this->select('selectCustomerGroup', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast    = $this->select('selectCustomerGroup', $startLast, $endLast, 'sd', $accounts);
            $customersSD     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'sd', $accounts);
            $customersSDLast = $this->select('selectCustomer', $startLast, $endLast, 'sd', $accounts);

            $newCustomersSD = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'sd', $accounts, null, null, null);
            $lostCustomersSD = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'sd', $accounts, null, null, null);

            $newCustomers += count($newCustomersSD);
            $lostCustomers += count($lostCustomersSD);

            $this->loopCustomerGroups('now', $groupsSD, 'sd', $salesGroups, $totalGroups);
            $this->loopCustomer('now', $customersSD, $salesCustomers);
            $this->loopCustomerGroups('old', $groupsSDLast, 'sd', $salesGroups, $totalGroups);
            $this->loopCustomer('old', $customersSDLast, $salesCustomers);

            $customerSD = $this->select('selectCustomerCount', $start, $current, 'sd', $accounts);
            $this->loopCustomerCount($customerSD, $customerCount);
        }

        if ($request->getData('u') !== 'sd') {
            $groupsGDF        = $this->select('selectCustomerGroup', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast    = $this->select('selectCustomerGroup', $startLast, $endLast, 'gdf', $accounts);
            $customersGDF     = $this->select('selectCustomer', $startCurrent, $endCurrent, 'gdf', $accounts);
            $customersGDFLast = $this->select('selectCustomer', $startLast, $endLast, 'gdf', $accounts);

            $newCustomersGDF = $this->selectSalesAnalysis('selectCustomNewCustomerAnalysis', $endCurrent->createModify(-1), $endCurrent, 'gdf', $accounts, null, null, null);
            $lostCustomersGDF = $this->selectSalesAnalysis('selectCustomLostCustomerAnalysis', $endCurrent->createModify(-2), $endCurrent->createModify(-1), 'gdf', $accounts, null, null, null);

            $newCustomers += count($newCustomersGDF);
            $lostCustomers += count($lostCustomersGDF);

            $this->loopCustomerGroups('now', $groupsGDF, 'gdf', $salesGroups, $totalGroups);
            $this->loopCustomer('now', $customersGDF, $salesCustomers);
            $this->loopCustomerGroups('old', $groupsGDFLast, 'gdf', $salesGroups, $totalGroups);
            $this->loopCustomer('old', $customersGDFLast, $salesCustomers);

            $customerGDF = $this->select('selectCustomerCount', $start, $current, 'gdf', $accounts);
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

        $view->setData('salesGroups', $salesGroups);
        $view->setData('totalGroups', $totalGroups);
        $view->setData('customer', $salesCustomers);
        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('customerCount', $customerCount);
        $view->setData('gini', $gini);
        $view->setData('date', $endCurrent);
        $view->setData('newCustomers', $newCustomers);
        $view->setData('lostCustomers', $lostCustomers);

        return $view;
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

    private function loopCustomerGroups(string $period, array $resultset, string $company, array &$salesGroups, array &$totalGroups)
    {
        foreach ($resultset as $line) {
            if (!isset(StructureDefinitions::CUSTOMER_GROUP[$company][$line['cgroup']])) {
                continue;
            }

            /** @noinspection PhpUnreachableStatementInspection */
            $customerGroup = StructureDefinitions::CUSTOMER_GROUP[$company][$line['cgroup']];
            if (!isset($salesGroups[$customerGroup][$period])) {
                $salesGroups[$customerGroup][$period] = 0.0;
            }

            $salesGroups[$customerGroup][$period] += $line['sales'];
            $totalGroups[$period] += $line['sales'];
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

}
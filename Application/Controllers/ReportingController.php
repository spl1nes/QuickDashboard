<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\StructureDefinitions;

class ReportingController extends DashboardController
{
    public function showArticleProfitMonth(RequestAbstract $request, ResponseAbstract $response)
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

        $view = $this->showArticleProfit($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'isolated');

        return $view;
    }

    public function showArticleProfitYear(RequestAbstract $request, ResponseAbstract $response)
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

        $view = $this->showArticleProfit($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'accumulated');

        return $view;
    }

    public function showArticleProfit(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Reporting/reporting-profit');

        $temp = array_slice(StructureDefinitions::NAMING, 0, 6);

        $salesGroups   = [];
        $segmentGroups = [];

        foreach ($temp as $segment) {
            $salesGroups['Sales'][$segment]   = null;
            $segmentGroups['Sales'][$segment] = null;

            $salesGroups['Costs'][$segment]   = null;
            $segmentGroups['Costs'][$segment] = null;
        }

        $totalGroups = [
            'Sales' => ['now' => 0.0, 'old' => 0.0],
            'Costs' => ['now' => 0.0, 'old' => 0.0],
        ];

        $accounts      = StructureDefinitions::PL_ACCOUNTS['Sales'];
        $accountsCosts = StructureDefinitions::getCOGSAccounts();

        $accounts[]      = 8591;
        $accountsCosts[] = 3491;

        if ($request->getData('u') !== 'gdf') {
            $groupsSD     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accounts);
            $groupsSDLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accounts);

            $groupsSDCosts     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'sd', $accountsCosts);
            $groupsSDCostsLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'sd', $accountsCosts);

            $this->loopArticleGroupsProfit('now', $groupsSD, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsSDLast, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('now', $groupsSDCosts, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsSDCostsLast, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
        }

        if ($request->getData('u') !== 'sd') {
            $groupsGDF     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accounts);
            $groupsGDFLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accounts);

            $groupsGDFCosts     = $this->select('selectSalesArticleGroups', $startCurrent, $endCurrent, 'gdf', $accountsCosts);
            $groupsGDFCostsLast = $this->select('selectSalesArticleGroups', $startLast, $endLast, 'gdf', $accountsCosts);

            $this->loopArticleGroupsProfit('now', $groupsGDF, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsGDFLast, 'Sales', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('now', $groupsGDFCosts, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
            $this->loopArticleGroupsProfit('old', $groupsGDFCostsLast, 'Costs', $salesGroups, $segmentGroups, $totalGroups);
        }

        $view->setData('salesGroups', $salesGroups);
        $view->setData('segmentGroups', $segmentGroups);
        $view->setData('totalGroups', $totalGroups);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopArticleGroupsProfit(string $period, array $resultset, string $type, array &$salesGroups, array &$segmentGroups, array &$totalGroups)
    {
        foreach ($resultset as $line) {
            $group = StructureDefinitions::getGroupOfArticle((int) ($line['costcenter'] ?? 0));

            if ($group === 0) {
                continue;
            }

            $segment = StructureDefinitions::getSegmentOfArticle((int) ($line['costcenter'] ?? 0));

            if (!isset(StructureDefinitions::NAMING[$segment]) || !isset(StructureDefinitions::NAMING[$group])) {
                continue;
            }

            /** @noinspection PhpUnreachableStatementInspection */
            if (!isset($salesGroups[$type][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period])) {
                $salesGroups[$type][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] = 0.0;
            }

            if (!isset($segmentGroups[$type][StructureDefinitions::NAMING[$segment]][$period])) {
                $segmentGroups[$type][StructureDefinitions::NAMING[$segment]][$period] = 0.0;
            }

            $salesGroups[$type][StructureDefinitions::NAMING[$segment]][StructureDefinitions::NAMING[$group]][$period] += $line['sales'];
            $segmentGroups[$type][StructureDefinitions::NAMING[$segment]][$period] += $line['sales'];
            $totalGroups[$type][$period] += $line['sales'];
        }

    }

    public function showPLMonth(RequestAbstract $request, ResponseAbstract $response)
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

        $view = $this->showPL($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'isolated');

        return $view;
    }

    public function showPLYear(RequestAbstract $request, ResponseAbstract $response)
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

        $view = $this->showPL($request, $response, $startCurrent, $endCurrent, $startLast, $endLast);
        $view->setData('type', 'accumulated');

        return $view;
    }

    public function showPL(RequestAbstract $request, ResponseAbstract $response, \DateTime $startCurrent, \DateTime $endCurrent, \DateTime $startLast, \DateTime $endLast)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Reporting/pl');

        $accountPositions = [];
        $accounts         = ArrayUtils::arrayFlatten(StructureDefinitions::PL_ACCOUNTS);
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
            $accounts[] = 3491;
        }

        if ($request->getData('u') !== 'gdf') {
            $accountsSD     = $this->select('selectEntries', $startCurrent, $endCurrent, 'sd', $accounts);
            $accountsSDLast = $this->select('selectEntries', $startLast, $endLast, 'sd', $accounts);

            $this->loopPL('now', $accountsSD, $accountPositions);
            $this->loopPL('old', $accountsSDLast, $accountPositions);
        }

        if ($request->getData('u') !== 'sd') {
            $accountsGDF     = $this->select('selectEntries', $startCurrent, $endCurrent, 'gdf', $accounts);
            $accountsGDFLast = $this->select('selectEntries', $startLast, $endLast, 'gdf', $accounts);

            $this->loopPL('now', $accountsGDF, $accountPositions);
            $this->loopPL('old', $accountsGDFLast, $accountPositions);
        }

        $accountPositions['Gross Profit']['now']        = ($accountPositions['Sales']['now'] ?? 0) + ($accountPositions['COGS Material']['now'] ?? 0) + ($accountPositions['COGS Services']['now'] ?? 0);
        $accountPositions['Gross Profit']['old']        = ($accountPositions['Sales']['old'] ?? 0) + ($accountPositions['COGS Material']['old'] ?? 0) + ($accountPositions['COGS Services']['old'] ?? 0);
        $accountPositions['Gross Profit Margin']['now'] = ($accountPositions['Gross Profit']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Gross Profit Margin']['old'] = ($accountPositions['Gross Profit']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Other Selling Expenses']['now']        = ($accountPositions['Freight']['now'] ?? 0) + ($accountPositions['Provisions']['now'] ?? 0) + ($accountPositions['External Seminars']['now'] ?? 0);
        $accountPositions['Other Selling Expenses']['old']        = ($accountPositions['Freight']['old'] ?? 0) + ($accountPositions['Provisions']['old'] ?? 0) + ($accountPositions['External Seminars']['old'] ?? 0);
        $accountPositions['Other Selling Expenses Margin']['now'] = ($accountPositions['Other Selling Expenses']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Other Selling Expenses Margin']['old'] = ($accountPositions['Other Selling Expenses']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Personnel']['now']        = ($accountPositions['Wages & Salaries']['now'] ?? 0) + ($accountPositions['Welfare Expenses']['now'] ?? 0);
        $accountPositions['Personnel']['old']        = ($accountPositions['Wages & Salaries']['old'] ?? 0) + ($accountPositions['Welfare Expenses']['old'] ?? 0);
        $accountPositions['Personnel Margin']['now'] = ($accountPositions['Personnel']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Personnel Margin']['old'] = ($accountPositions['Personnel']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Total Other OPEX']['now'] = ($accountPositions['Marketing']['now'] ?? 0) + ($accountPositions['Trade Fair']['now'] ?? 0) + ($accountPositions['Rental & Leasing']['now'] ?? 0) + ($accountPositions['Utilities']['now'] ?? 0) + ($accountPositions['Repair/Maintenance']['now'] ?? 0) + ($accountPositions['Carpool']['now'] ?? 0) + ($accountPositions['Stationary Expenses']['now'] ?? 0) + ($accountPositions['Communication']['now'] ?? 0) + ($accountPositions['Travel Expenses']['now'] ?? 0) + ($accountPositions['Entertainment']['now'] ?? 0) + ($accountPositions['External Consultants']['now'] ?? 0) + ($accountPositions['R&D']['now'] ?? 0) + ($accountPositions['Patents']['now'] ?? 0) + ($accountPositions['Other Personnel Expenses']['now'] ?? 0) + ($accountPositions['Other OPEX']['now'] ?? 0) + ($accountPositions['Intercompany Expenses']['now'] ?? 0) + ($accountPositions['Intercompany Revenue']['now'] ?? 0) + ($accountPositions['Doubtful Accounts']['now'] ?? 0);
        $accountPositions['Total Other OPEX']['old'] = ($accountPositions['Marketing']['old'] ?? 0) + ($accountPositions['Trade Fair']['old'] ?? 0) + ($accountPositions['Rental & Leasing']['old'] ?? 0) + ($accountPositions['Utilities']['old'] ?? 0) + ($accountPositions['Repair/Maintenance']['old'] ?? 0) + ($accountPositions['Carpool']['old'] ?? 0) + ($accountPositions['Stationary Expenses']['old'] ?? 0) + ($accountPositions['Communication']['old'] ?? 0) + ($accountPositions['Travel Expenses']['old'] ?? 0) + ($accountPositions['Entertainment']['old'] ?? 0) + ($accountPositions['External Consultants']['old'] ?? 0) + ($accountPositions['R&D']['old'] ?? 0) + ($accountPositions['Patents']['old'] ?? 0) + ($accountPositions['Other Personnel Expenses']['old'] ?? 0) + ($accountPositions['Other OPEX']['old'] ?? 0) + ($accountPositions['Intercompany Expenses']['old'] ?? 0) + ($accountPositions['Intercompany Revenue']['old'] ?? 0) + ($accountPositions['Doubtful Accounts']['old'] ?? 0);

        $accountPositions['Total Other OPEX Margin']['now'] = ($accountPositions['Total Other OPEX']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Total Other OPEX Margin']['old'] = ($accountPositions['Total Other OPEX']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['EBITDA']['now']        = ($accountPositions['Total Other OPEX']['now'] ?? 0) + ($accountPositions['Other Revenue']['now'] ?? 0) + ($accountPositions['Gross Profit']['now'] ?? 0) + ($accountPositions['Other Selling Expenses']['now'] ?? 0) + ($accountPositions['Personnel']['now'] ?? 0);
        $accountPositions['EBITDA']['old']        = ($accountPositions['Total Other OPEX']['old'] ?? 0) + ($accountPositions['Other Revenue']['old'] ?? 0) + ($accountPositions['Gross Profit']['old'] ?? 0) + ($accountPositions['Other Selling Expenses']['old'] ?? 0) + ($accountPositions['Personnel']['old'] ?? 0);
        $accountPositions['EBITDA Margin']['now'] = ($accountPositions['EBITDA']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['EBITDA Margin']['old'] = ($accountPositions['EBITDA']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Operating Income (EBIT)']['now'] = ($accountPositions['EBITDA']['now'] ?? 0) + ($accountPositions['Depreciation']['now'] ?? 0);
        $accountPositions['Operating Income (EBIT)']['old'] = ($accountPositions['EBITDA']['old'] ?? 0) + ($accountPositions['Depreciation']['old'] ?? 0);
        $accountPositions['EBIT Margin']['now']             = ($accountPositions['Operating Income (EBIT)']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['EBIT Margin']['old']             = ($accountPositions['Operating Income (EBIT)']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['EBT']['now']        = ($accountPositions['Operating Income (EBIT)']['now'] ?? 0) + ($accountPositions['Interest Revenue']['now'] ?? 0) + ($accountPositions['Interest Expenses']['now'] ?? 0);
        $accountPositions['EBT']['old']        = ($accountPositions['Operating Income (EBIT)']['old'] ?? 0) + ($accountPositions['Interest Revenue']['old'] ?? 0) + ($accountPositions['Interest Expenses']['old'] ?? 0);
        $accountPositions['EBT Margin']['now'] = ($accountPositions['EBT']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['EBT Margin']['old'] = ($accountPositions['EBT']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $accountPositions['Net Income (EAT)']['now']  = ($accountPositions['EBT']['now'] ?? 0) + ($accountPositions['Taxes']['now'] ?? 0);
        $accountPositions['Net Income (EAT)']['old']  = ($accountPositions['EBT']['old'] ?? 0) + ($accountPositions['Taxes']['old'] ?? 0);
        $accountPositions['Net Income Margin']['now'] = ($accountPositions['Net Income (EAT)']['now'] ?? 0) / ($accountPositions['Sales']['now'] ?? 0);
        $accountPositions['Net Income Margin']['old'] = ($accountPositions['Net Income (EAT)']['old'] ?? 0) / ($accountPositions['Sales']['old'] ?? 0);

        $view->setData('accountPositions', $accountPositions);
        $view->setData('date', $endCurrent);

        return $view;
    }

    private function loopPL(string $period, array $resultset, array &$accountPositions)
    {
        foreach ($resultset as $line) {
            $position = StructureDefinitions::getAccountPLPosition($line['Konto']);
            if (!isset($accountPositions[$position][$period])) {
                $accountPositions[$position][$period] = 0.0;
            }

            $accountPositions[$position][$period] += $line['entries'];
        }
    }

    public function showEBIT(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Reporting/reporting-ebit');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $totalSales    = [];
        $accTotalSales = [];

        $accounts = StructureDefinitions::getEBITAccounts();
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
            $accounts[] = 3491;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
            $this->loopEBIT($salesSD, $totalSales);
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
            $this->loopEBIT($salesGDF, $totalSales);
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
        $view->setData('ebit', $totalSales);
        $view->setData('ebitAcc', $accTotalSales);
        $view->setData('date', $current->smartModify(0, -1));

        return $view;
    }

    private function loopEBIT(array $resultset, array &$totalSales)
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

}
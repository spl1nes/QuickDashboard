<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\StructureDefinitions;
use phpOMS\Math\Finance\Forecasting\ExponentialSmoothing\Brown;

class OverviewController extends DashboardController
{
    public function showOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/overview');

        $current = new SmartDateTime($request->getData('t') ?? 'now');
        $start   = $this->getFiscalYearStart($current);
        $start->modify('-2 year');

        $totalSales    = [];
        $accTotalSales = [];

        $accounts = StructureDefinitions::PL_ACCOUNTS['Sales'];
        if ($request->getData('u') === 'sd' || $request->getData('u') === 'gdf') {
            $accounts[] = 8591;
        }

        if ($request->getData('u') !== 'gdf') {
            $salesSD = $this->select('selectSalesYearMonth', $start, $current, 'sd', $accounts);
            $this->loopOverview($salesSD, $totalSales);
        }

        if ($request->getData('u') !== 'sd') {
            $salesGDF = $this->select('selectSalesYearMonth', $start, $current, 'gdf', $accounts);
            $this->loopOverview($salesGDF, $totalSales);
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
        ksort($totalSales);

        $fcData = [];
        foreach($totalSales as $year => $months) {
            foreach($months as $month => $value) {
                $fcData[] = $value;
            }
        }

        $fc = new Brown($fcData, 4);
        $totalSalesFC = $fc->getForecast(12 - $currentMonth + 1);
        $totalSalesFC = array_merge(array_slice($totalSales[$currentYear], -1), array_slice($totalSalesFC, $currentMonth - 12 - 1));

        $accTotalSalesFC[$currentMonth] = $accTotalSales[$currentYear][$currentMonth-1];
        for($i = $currentMonth + 1; $i < 12 + 2; $i++) {
            $accTotalSalesFC[$i] = $accTotalSalesFC[$i-1] + $totalSalesFC[$i - $currentMonth - 1];
        }

        $view->setData('currentFiscalYear', $currentYear);
        $view->setData('currentMonth', $currentMonth);
        $view->setData('sales', $totalSales);
        $view->setData('salesAcc', $accTotalSales);
        $view->setData('salesFC', $totalSalesFC);
        $view->setData('salesAccFC', $accTotalSalesFC);
        $view->setData('date', $current->smartModify(0, -1));

        return $view;
    }

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
}
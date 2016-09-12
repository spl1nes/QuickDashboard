<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\ApplicationAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

class DashboardController
{
    private $app = null;

    public function __construct(ApplicationAbstract $app)
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
        $iterator = clone $start;
        $year     = $iterator->format('Y');

        $currentMonth = (int) $start->format('m');
        $sales        = [];

        while ($iterator->getTimestamp() < $current->getTimestamp()) {
            $endOfMonth = $iterator->getEndOfMonth();
            $month      = ($currentMonth - $this->app->config['fiscal_year'] - 1) % 12 + 1;

            $monthSales           = $this->getSalesPerMonth($iterator, $endOfMonth, 'sd');
            $sales[$year][$month] = array_sum($monthSales);

            $monthSales = $this->getSalesPerMonth($iterator, $endOfMonth, 'gdf');
            $sales[$year][$month] += array_sum($monthSales);

            if ($currentMonth % 12 === 0) {
                $year++;
            }

            $currentMonth++;
            $iterator->modify('+1 month');
        }

        $view->setData('sales', $sales);

        return $view;
    }

    public function showSalesOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-history');

        return $view;
    }

    public function showMonth(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-month');

        return $view;
    }

    public function showLocation(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Sales/sales-location');

        return $view;
    }

    public function showArticles(RequestAbstract $request, ResponseAbstract $response)
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

        return $newDate->modify('-' . ($this->calcCurrentMonth($date) - 1) . ' month');
    }

    private function getSalesPerMonth(\DateTime $start, \DateTime $end, string $company)
    {
        $query = new Builder($this->app->dbPool->get($company));
        $query->raw(
            'SELECT 
                SUM(Kunde_Belegzeilen_Archiv.STATUMSATZ) AS Sales, 
            FROM Kunde_Belegzeilen_Archiv
            WHERE 
                Kunde_Belegzeilen_Archiv.BELEGART IN (\'VR0\', \'VR1\', \'VRS\', \'VRT\', \'VW0\', \'VG0\') 
                AND CONVERT(VARCHAR(30), Kunde_Belegzeilen_Archiv.BELEGDATUM, 104) >= CONVERT(datetime, \'' . $start->format('Y.m.d') . '\', 102) 
                AND CONVERT(VARCHAR(30), Kunde_Belegzeilen_Archiv.BELEGDATUM, 104) <= CONVERT(datetime, \'' . $end->format('Y.m.d') . '\', 102);');
        $result = $query->execute();

        return $result;
    }
}
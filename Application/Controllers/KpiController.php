<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\Datatypes\SmartDateTime;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Views\View;
use QuickDashboard\Application\Models\StructureDefinitions;

class KpiController extends DashboardController
{
    public function showFinance(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-finance');

        // dso (total/domestic/export)

        // dpo

        // dio

        // ebit margin

        // gross profit margin

        // opex quote

        // personnel quote

        return $view;
    }

    public function showMarketing(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-marketing');

        // roi on p

        // roi on k

        // marketing+fairs

        return $view;
    }

    public function showPersonnel(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-personnel');

        return $view;
    }

    public function showQuality(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/Kpi/kpi-quality');

        // credit notes

        return $view;
    }
}
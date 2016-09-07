<?php

namespace QuickDashboard\Application\Controllers;

use phpOMS\ApplicationAbstract;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

class OverviewController {
    private $app = null;

    public function __construct(ApplicationAbstract $app)
    {
        $this->app = $app;
    }

    public function showOverview(RequestAbstract $request, ResponseAbstract $response)
    {
        $view = new View($this->app, $request, $response);
        $view->setTemplate('/QuickDashboard/Application/Templates/overview');

        return $view;
    }
}
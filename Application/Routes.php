<?php

use phpOMS\Router\RouteVerb;

return [
	'^.*/sales/overview.*$' => [
        [
            'dest' => '\QuickDashboard\Controller\DashboardController:showSalesOverview', 
            'verb' => RouteVerb::GET,
        ],
    ],
];
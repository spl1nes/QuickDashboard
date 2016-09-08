<?php

use phpOMS\Router\RouteVerb;

return [
	'^$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\OverviewController:showOverview',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/history.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showOverview',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/location.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showLocation',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showCustomers',
            'verb' => RouteVerb::GET,
        ],
    ],
];
<?php

use phpOMS\Router\RouteVerb;

return [
	'^$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showOverview',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/history.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showSalesOverview',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/list.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showListMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/list.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showListYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/location.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showLocation',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/articles.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticles',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showCustomers',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/reps.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showReps',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^costs.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showCosts',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^analysis/reps.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showAnalysisReps',
            'verb' => RouteVerb::GET,
        ],
    ],
];
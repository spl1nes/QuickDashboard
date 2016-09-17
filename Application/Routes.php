<?php

use phpOMS\Router\RouteVerb;

return [
	'^(u=.*)*$' => [
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
    '^sales/location.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showLocationMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/location.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticle',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/articles.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticle',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/articles.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticleYear',
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
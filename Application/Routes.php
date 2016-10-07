<?php

use phpOMS\Router\RouteVerb;

return [
	'^(\/*\?.*)*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showOverview',
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
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showLocationYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/segmentation.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticleMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/segmentation.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticleYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showCustomersMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showCustomersYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/reps.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showRepsMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/reps.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showRepsYear',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^analysis/reps.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showAnalysisReps',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/customer.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showAnalysisCustomer',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/segmentation.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showAnalysisSegmentation',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^reporting/pl.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showPLMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/pl.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showPLYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/profit.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticleProfitMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/profit.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showArticleProfitYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/ebit.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\DashboardController:showEBIT',
            'verb' => RouteVerb::GET,
        ],
    ],
];
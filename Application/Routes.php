<?php

use phpOMS\Router\RouteVerb;

return [
	'^(\/*\?.*)*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\OverviewController:showOverview',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^sales/list.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showListMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/list.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showListYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/location.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showLocationMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/location.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showLocationYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/countries.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showCountriesMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/countries.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showCountriesYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/segmentation.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showArticleMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/segmentation.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showArticleYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showCustomersMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/customers.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showCustomersYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/reps.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showRepsMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^sales/reps.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\SalesController:showRepsYear',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^analysis/reps.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisReps',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/customer.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisCustomer',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/segmentation.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisSegmentation',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/location.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisLocation',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/sales.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisSales',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/opex.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisOPEX',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^analysis/department.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\AnalysisController:showAnalysisDepartment',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^reporting/pl.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showPLMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/pl.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showPLYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/profit.*?i=month.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showArticleProfitMonth',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/profit.*?i=year.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showArticleProfitYear',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/opex.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showOPEX',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^reporting/ebit.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ReportingController:showEBIT',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^kpi/finance.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\KpiController:showFinance',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^kpi/marketing.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\KpiController:showMarketing',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^kpi/personnel.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\KpiController:showPersonnel',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^kpi/quality.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\KpiController:showQuality',
            'verb' => RouteVerb::GET,
        ],
    ],

    '^mani/package/balance.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showPackageBalance',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/pl/1.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showPackagePL',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/pl/2.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showPackagePL2',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/pl/3.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showPackagePL3',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/production.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showProduction',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/rnd.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showRnD',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/cash.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showCash',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/assets.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showAssets',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/interco/balance.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showIntercoBalance',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/interco/pl.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showIntercoPL',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/interco/production.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showIntercoProduction',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/cv/customer.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showCVCustomer',
            'verb' => RouteVerb::GET,
        ],
    ],
    '^mani/package/cv/vendor.*$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\ManiController:showCVVendor',
            'verb' => RouteVerb::GET,
        ],
    ],
];
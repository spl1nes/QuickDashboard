<?php

use phpOMS\Router\RouteVerb;

return [
	'^$' => [
        [
            'dest' => 'QuickDashboard\Application\Controllers\OverviewController:showOverview',
            'verb' => RouteVerb::GET,
        ],
    ],
];
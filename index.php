<?php
ob_start();

//<editor-fold desc="Require/Include">
require_once __DIR__ . '/../phpOMS/Autoloader.php';
require_once __DIR__ . '/config.php';
//</editor-fold>

$App = new \QuickDashboard\Application\WebApplication($CONFIG);

ob_end_flush();

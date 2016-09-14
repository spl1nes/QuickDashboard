<?php
$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$salesLast = $this->getData('salesLast');
$salesAccLast = $this->getData('salesAccLast');
$days = $this->getData('maxDays');
$today = $this->getData('today');

$salesExportDomestic = $this->getData('salesExportDomestic');
$salesDevUndev = $this->getData('salesDevUndev');
$salesRegion = $this->getData('salesRegion');
?>

<table class="width: 50%; float: left;">
    <caption>Sales By Domestic/Export</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last Year
        <th>Current Year
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Export
        <td><?= number_format($salesExportDomestic['old']['Export'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['new']['Export'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['new']['Export']-$salesExportDomestic['old']['Export'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Export']) ? 0 : ($salesExportDomestic['new']['Export']/$salesExportDomestic['old']['Export']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Domestic
        <td><?= number_format($salesExportDomestic['old']['Domestic'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['new']['Domestic'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['new']['Domestic']-$salesExportDomestic['old']['Domestic'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Domestic']) ? 0 : ($salesExportDomestic['new']['Domestic']/$salesExportDomestic['old']['Domestic']-1)*100, 0, ',', '.') ?> %
</table>

<table class="width: 50%; float: left;">
    <caption>Sales By Developed/Undeveloped</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last Year
        <th>Current Year
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Developed
        <td><?= number_format($salesDevUndev['old']['Developed'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['new']['Developed'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['new']['Developed']-$salesDevUndev['old']['Developed'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Developed']) ? 0 : ($salesDevUndev['new']['Developed']/$salesDevUndev['old']['Developed']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Undeveloped
        <td><?= number_format($salesDevUndev['old']['Undeveloped'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['new']['Undeveloped'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['new']['Undeveloped']-$salesDevUndev['old']['Undeveloped'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Undeveloped']) ? 0 : ($salesDevUndev['new']['Undeveloped']/$salesDevUndev['old']['Undeveloped']-1)*100, 0, ',', '.') ?> %
</table>

<table class="width: 50%; float: left;">
    <caption>Sales By Region</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last Year
        <th>Current Year
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Europe
        <td><?= number_format($salesRegion['old']['Europe'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Europe'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Europe']-$salesRegion['old']['Europe'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Europe']) ? 0 : ($salesRegion['new']['Europe']/$salesRegion['old']['Europe']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>America
        <td><?= number_format($salesRegion['old']['America'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['America'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['America']-$salesRegion['old']['America'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['America']) ? 0 : ($salesRegion['new']['America']/$salesRegion['old']['America']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Asia
        <td><?= number_format($salesRegion['old']['Asia'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Asia'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Asia']-$salesRegion['old']['Asia'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Asia']) ? 0 : ($salesRegion['new']['Asia']/$salesRegion['old']['Asia']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Africa
        <td><?= number_format($salesRegion['old']['Europe'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Europe'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Europe']-$salesRegion['old']['Europe'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Europe']) ? 0 : ($salesRegion['new']['Europe']/$salesRegion['old']['Europe']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Oceania
        <td><?= number_format($salesRegion['old']['Oceania'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Oceania'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Oceania']-$salesRegion['old']['Oceania'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Oceania']) ? 0 : ($salesRegion['new']['Oceania']/$salesRegion['old']['Oceania']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Other
        <td><?= number_format($salesRegion['old']['Other'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Other'], 0, ',', '.') ?>
        <td><?= number_format($salesRegion['new']['Other']-$salesRegion['old']['Other'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Other']) ? 0 : ($salesRegion['new']['Other']/$salesRegion['old']['Other']-1)*100, 0, ',', '.') ?> %
</table>

<table>
    <caption>Sales By Day</caption>
    <thead>
    <tr>
        <th>Day
        <th>Last Year
        <th>Current Year
        <th>Diff
        <th>Diff %
        <th>Last Acc.
        <th>Current Acc.
        <th>Acc. Diff
        <th>Acc. Diff %
    <tbody>
    <?php for($i = 1; $i <= $days; $i++) : ?>
    <tr<?= $i === $today ? ' class="bold"' : '';?>>
        <td><?= $i; ?>
        <td><?= !isset($salesLast[$i]) ? '' : number_format($salesLast[$i], 0, ',', '.'); ?>
        <td><?= !isset($sales[$i]) ? '' : number_format($sales[$i], 0, ',', '.'); ?>
        <td><?= number_format(($sales[$i] ?? 0) - ($salesLast[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesLast[$i] ?? 0) == 0 ? 0 : (($sales[$i] ?? 0)/($salesLast[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
        <td><?= !isset($salesAccLast[$i]) ? '' : number_format($salesAccLast[$i] ?? 0, 0, ',', '.'); ?>
        <td><?= !isset($salesAcc[$i]) ? '' : number_format($salesAcc[$i] ?? 0, 0, ',', '.'); ?>
        <td><?= number_format(($salesAcc[$i] ?? 0) - ($salesAccLast[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesAccLast[$i] ?? 0) == 0 ? 0 : (($salesAcc[$i] ?? 0)/($salesAccLast[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <th>Current
        <th><?= number_format(\phpOMS\Utils\ArrayUtils::arraySum($salesLast, 0, $today), 0, ',', '.'); ?>
        <th><?= number_format(\phpOMS\Utils\ArrayUtils::arraySum($sales, 0, $today), 0, ',', '.'); ?>
        <th><?= number_format(\phpOMS\Utils\ArrayUtils::arraySum($sales, 0, $today) - \phpOMS\Utils\ArrayUtils::arraySum($salesLast, 0, $today), 0, ',', '.'); ?>
        <th><?= number_format(\phpOMS\Utils\ArrayUtils::arraySum($salesLast, 0, $today) == 0 ? 0 : (\phpOMS\Utils\ArrayUtils::arraySum($sales, 0, $today)/\phpOMS\Utils\ArrayUtils::arraySum($salesLast, 0, $today) - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days] - $salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccLast[$today] == 0 ? 0 : ($salesAcc[$days]/$salesAccLast[$today] - 1) * 100, 2, ',', '.'); ?> %
</table>
<div class="clear"></div>
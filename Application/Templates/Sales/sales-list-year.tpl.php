<?php
$sales = $this->getData('sales');
$salesLast = $this->getData('salesLast');
$salesAcc = $this->getData('salesAcc');
$salesAccLast = $this->getData('salesAccLast');

$salesDomestic = $this->getData('salesDomestic');
$salesLastDomestic = $this->getData('salesLastDomestic');
$salesAccDomestic = $this->getData('salesAccDomestic');
$salesAccLastDomestic = $this->getData('salesAccLastDomestic');

$salesExport = $this->getData('salesExport');
$salesLastExport = $this->getData('salesLastExport');
$salesAccExport = $this->getData('salesAccExport');
$salesAccLastExport = $this->getData('salesAccLastExport');

$today = $this->getData('currentMonth');
?>
<h1>Sales List - <?= $this->getData('date')->format('Y/m'); ?></h1>
<table>
    <caption>Sales by Month</caption>
    <thead>
    <tr>
        <th>Month
        <th>Last Year
        <th>Current
        <th>Diff
        <th>Diff %
        <th>Last Acc.
        <th>Current Acc.
        <th>Diff
        <th>Diff %
    <tbody>
    <?php for($i = 1; $i <= 12; $i++) : ?>
    <tr<?= $i === $today ? ' class="bold"' : '';?>>
        <td><?= $i; ?>
        <td><?= !isset($salesLast[$i]) ? '' : number_format($salesLast[$i], 0, ',', '.'); ?>
        <td><?= !isset($sales[$i]) ? '' : number_format($sales[$i], 0, ',', '.'); ?>
        <td><?= number_format(($sales[$i] ?? 0) - ($salesLast[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesLast[$i] ?? 0) == 0 ? 0 : (($sales[$i] ?? 0)/($salesLast[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLast[$i], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$i], 0, ',', '.'); ?>
        <td><?= number_format(($salesAcc[$i] ?? 0) - ($salesAccLast[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesAccLast[$i] ?? 0) == 0 ? 0 : (($salesAcc[$i] ?? 0)/($salesAccLast[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <th>Current
        <th><?= number_format($salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$today] - $salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$today] == 0 ? 0 : ($salesAcc[$today]/$salesAccLast[$today] - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLast[count($salesAccLast)], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[count($salesAcc)] - $salesAccLast[count($salesAccLast)], 0, ',', '.'); ?>
        <th><?= number_format($salesAccLast[$today] == 0 ? 0 : ($salesAcc[$today]/$salesAccLast[count($salesAccLast)] - 1) * 100, 2, ',', '.'); ?> %
</table>

<table>
    <caption>Sales by Month Domestic</caption>
    <thead>
    <tr>
        <th>Month
        <th>Last Year
        <th>Current
        <th>Diff
        <th>Diff %
        <th>Last Acc.
        <th>Current Acc.
        <th>Diff
        <th>Diff %
    <tbody>
    <?php for($i = 1; $i <= 12; $i++) : ?>
    <tr<?= $i === $today ? ' class="bold"' : '';?>>
        <td><?= $i; ?>
        <td><?= !isset($salesLastDomestic[$i]) ? '' : number_format($salesLastDomestic[$i], 0, ',', '.'); ?>
        <td><?= !isset($salesDomestic[$i]) ? '' : number_format($salesDomestic[$i], 0, ',', '.'); ?>
        <td><?= number_format(($salesDomestic[$i] ?? 0) - ($salesLastDomestic[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesLastDomestic[$i] ?? 0) == 0 ? 0 : (($salesDomestic[$i] ?? 0)/($salesLastDomestic[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLastDomestic[$i], 0, ',', '.'); ?>
        <th><?= number_format($salesAccDomestic[$i], 0, ',', '.'); ?>
        <td><?= number_format(($salesAccDomestic[$i] ?? 0) - ($salesAccLastDomestic[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesAccLastDomestic[$i] ?? 0) == 0 ? 0 : (($salesAccDomestic[$i] ?? 0)/($salesAccLastDomestic[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <th>Current
        <th><?= number_format($salesAccLastDomestic[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccDomestic[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccDomestic[$today] - $salesAccLastDomestic[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccDomestic[$today] == 0 ? 0 : ($salesAccDomestic[$today]/$salesAccLastDomestic[$today] - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLastDomestic[count($salesAccLast)], 0, ',', '.'); ?>
        <th><?= number_format($salesAccDomestic[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccDomestic[count($salesAccDomestic)] - $salesAccLastDomestic[count($salesAccLastDomestic)], 0, ',', '.'); ?>
        <th><?= number_format($salesAccLastDomestic[$today] == 0 ? 0 : ($salesAccDomestic[$today]/$salesAccLastDomestic[count($salesAccLastDomestic)] - 1) * 100, 2, ',', '.'); ?> %
</table>

<table>
    <caption>Sales by Month Export</caption>
    <thead>
    <tr>
        <th>Month
        <th>Last Year
        <th>Current
        <th>Diff
        <th>Diff %
        <th>Last Acc.
        <th>Current Acc.
        <th>Diff
        <th>Diff %
    <tbody>
    <?php for($i = 1; $i <= 12; $i++) : ?>
    <tr<?= $i === $today ? ' class="bold"' : '';?>>
        <td><?= $i; ?>
        <td><?= !isset($salesLastExport[$i]) ? '' : number_format($salesLastExport[$i], 0, ',', '.'); ?>
        <td><?= !isset($salesExport[$i]) ? '' : number_format($salesExport[$i], 0, ',', '.'); ?>
        <td><?= number_format(($salesExport[$i] ?? 0) - ($salesLastExport[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesLastExport[$i] ?? 0) == 0 ? 0 : (($salesExport[$i] ?? 0)/($salesLastExport[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLastExport[$i], 0, ',', '.'); ?>
        <th><?= number_format($salesAccExport[$i], 0, ',', '.'); ?>
        <td><?= number_format(($salesAccExport[$i] ?? 0) - ($salesAccLastExport[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesAccLastExport[$i] ?? 0) == 0 ? 0 : (($salesAccExport[$i] ?? 0)/($salesAccLastExport[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <th>Current
        <th><?= number_format($salesAccLastExport[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccExport[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccExport[$today] - $salesAccLastExport[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccExport[$today] == 0 ? 0 : ($salesAccExport[$today]/$salesAccLastExport[$today] - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLastExport[count($salesAccLast)], 0, ',', '.'); ?>
        <th><?= number_format($salesAccExport[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAccExport[count($salesAccExport)] - $salesAccLastExport[count($salesAccLastExport)], 0, ',', '.'); ?>
        <th><?= number_format($salesAccLastExport[$today] == 0 ? 0 : ($salesAccExport[$today]/$salesAccLastExport[count($salesAccLastExport)] - 1) * 100, 2, ',', '.'); ?> %
</table>
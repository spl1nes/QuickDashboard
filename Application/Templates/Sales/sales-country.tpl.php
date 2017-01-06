<?php
$salesCountry = $this->getData('salesCountry');
$sum = ['old' => 0, 'now' => 0];
?>
<h1>Sales Country - <?= $this->getData('date')->format('Y/m'); ?> <?= $this->getData('type'); ?></h1>

<table style="width: 100%; float: left;">
    <caption>Sales Country</caption>
    <thead>
    <tr>
        <th>Country
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesCountry as $code => $value) : $sum['old'] += $salesCountry[$code]['old'] ?? 0; $sum['now'] += $salesCountry[$code]['now'] ?? 0; ?>
    <tr>
        <td><?= $code; ?>
        <td><?= number_format($salesCountry[$code]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesCountry[$code]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesCountry[$code]['now'] ?? 0)-($salesCountry[$code]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesCountry[$code]['old']) || $salesCountry[$code]['old'] == 0 ? 0 : (($salesCountry[$code]['now'] ?? 0)/$salesCountry[$code]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th>Total
        <th><?= number_format($sum['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($sum['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($sum['now'] ?? 0)-($sum['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($sum['old']) || $sum['old'] == 0 ? 0 : (($sum['now'] ?? 0)/$sum['old']-1)*100, 0, ',', '.') ?> %
</table>

<div class="clear"></div>
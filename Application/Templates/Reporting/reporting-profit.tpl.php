<?php
$salesGroups = $this->getData('salesGroups');
$segmentGroups = $this->getData('segmentGroups');
$totalGroups = $this->getData('totalGroups');
?>
<h1>Gross Profit Segmentation - <?= $this->getData('date')->format('Y/m'); ?></h1>
<p>The following tables contain the gross profit of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: 100%; float: left;">
    <caption>Gross Profit Segmentation</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Last
        <th>Current
        <th>Current
    <tbody>
    <?php foreach($salesGroups['Sales'] as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $sales) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format(!isset($salesGroups['Sales'][$segment][$group]['old']) ? 0 : (($salesGroups['Costs'][$segment][$group]['old'] ?? 0)+($salesGroups['Sales'][$segment][$group]['old'] ?? 0)), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups['Sales'][$segment][$group]['old']) ? 0 : (1+($salesGroups['Costs'][$segment][$group]['old'] ?? 0)/($salesGroups['Sales'][$segment][$group]['old'] ?? 0)) * 100, 0, ',', '.') ?> %
        <td><?= number_format(!isset($salesGroups['Sales'][$segment][$group]['now']) ? 0 : (($salesGroups['Costs'][$segment][$group]['now'] ?? 0)+($salesGroups['Sales'][$segment][$group]['now'])), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups['Sales'][$segment][$group]['now']) ? 0 : (1+($salesGroups['Costs'][$segment][$group]['now'] ?? 0)/($salesGroups['Sales'][$segment][$group]['now'])) * 100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format(!isset($segmentGroups['Sales'][$segment]['old']) ? 0 : (($segmentGroups['Costs'][$segment]['old'] ?? 0)+($segmentGroups['Sales'][$segment]['old'])), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups['Sales'][$segment]['old']) ? 0 : (1+($segmentGroups['Costs'][$segment]['old'] ?? 0)/($segmentGroups['Sales'][$segment]['old'])) * 100, 0, ',', '.') ?> %
        <th><?= number_format(!isset($segmentGroups['Sales'][$segment]['now']) ? 0 : (($segmentGroups['Costs'][$segment]['now'] ?? 0)+($segmentGroups['Sales'][$segment]['now'])), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups['Sales'][$segment]['now']) ? 0 : (1+($segmentGroups['Costs'][$segment]['now'] ?? 0)/($segmentGroups['Sales'][$segment]['now'])) * 100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format((($totalGroups['Costs']['old'] ?? 0)+($totalGroups['Sales']['old'])), 0, ',', '.') ?>
        <th><?= number_format((1+($totalGroups['Costs']['old'] ?? 0)/($totalGroups['Sales']['old'])) * 100, 0, ',', '.') ?> %
        <th><?= number_format((($totalGroups['Costs']['now'] ?? 0)+($totalGroups['Sales']['now'])), 0, ',', '.') ?>
        <th><?= number_format((1+($totalGroups['Costs']['now'] ?? 0)/($totalGroups['Sales']['now'])) * 100, 0, ',', '.') ?> %
</table>

<div class="clear"></div>
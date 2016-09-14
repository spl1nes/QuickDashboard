<?php
$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$salesLast = $this->getData('salesLast');
$salesAccLast = $this->getData('salesAccLast');
$days = $this->getData('maxDays');
$today = $this->getData('today');
?>

<div class="box" style="width: 100%;">
    <canvas id="sales-month" height="100"></canvas>
</div>

<table>
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
<script>
</script>
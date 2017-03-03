<?php
$vendors = $this->getData('vendors');
$purchase = $this->getData('purchase');
$consolidation = $this->getData('consolidation');

$top = [];
$total = ['old' => 0.0, 'now' => '0.0'];

$i = 0; 
foreach($vendors['old'] as $name => $value) {
    $i++; if($i > 10) break; 

    $top['old'][$i] = ['name' => $value['name'], 'id' => $name, 'value' => $value['value']];
    $total['old'] += $value['value'];
}

$i = 0; 
foreach($vendors['now'] as $name => $value) {
    $i++; if($i > 10) break; 

    $top['now'][$i] = ['name' => $value['name'], 'id' => $name, 'value' => $value['value']];
    $total['now'] += $value['value'];
}


?>
<h1>MANI Vendors - <?= $this->getData('date')->format('Y/m'); ?></h1>

<table>
    <caption>Vendor purchase list</caption>
    <thead>
    <tr>
        <th>No
        <th>Vendor
        <th>Purchase
        <th>%
        <th>No
        <th>Vendor
        <th>Purchase
        <th>%
    <tbody>
    <?php for($i = 1; $i < 11; $i++) : ?>
    <tr>
        <td><?= $i; ?> - (<?= $top['old'][$i]['id'] ?>)
        <td><?= $top['old'][$i]['name']; ?>
        <td><?= number_format($top['old'][$i]['value'], 2, ',', '.'); ?>
        <td><?= number_format($top['old'][$i]['value']/$purchase['COGS Material']['old']*-1 * 100, 2, ',', '.'); ?> %
        <td><?= $i; ?> - (<?= $top['now'][$i]['id'] ?>)
        <td><?= $top['now'][$i]['name']; ?>
        <td><?= number_format($top['now'][$i]['value'], 2, ',', '.'); ?>
        <td><?= number_format($top['now'][$i]['value']/$purchase['COGS Material']['now']*-1 * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <td>
        <td>Other
        <td><?= number_format($purchase['COGS Material']['old']*-1 - $total['old'], 2, ',', '.'); ?>
        <td><?= number_format(($purchase['COGS Material']['old']*-1 - $total['old'])/$purchase['COGS Material']['old']*-1 * 100, 2, ',', '.'); ?> %
        <td>
        <td>Other
        <td><?= number_format($purchase['COGS Material']['now']*-1 - $total['now'], 2, ',', '.'); ?>
        <td><?= number_format(($purchase['COGS Material']['now']*-1 - $total['now'])/$purchase['COGS Material']['now']*-1 * 100, 2, ',', '.'); ?> %
    <tr>
        <th>小計
        <th>Subtotal
        <th><?= number_format($purchase['COGS Material']['old']*-1, 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
        <th>小計
        <th>Subtotal
        <th><?= number_format($purchase['COGS Material']['now']*-1, 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
    <tr>
        <th>内部取引消去
        <th>Interco
        <th><?= number_format($consolidation['COGS Material']['old']*-1 , 2, ',', '.'); ?>
        <th><?= number_format(($consolidation['COGS Material']['old']*-1)/$purchase['COGS Material']['old']*-1 * 100, 2, ',', '.'); ?> %
        <th>内部取引消去
        <th>Interco
        <th><?= number_format($consolidation['COGS Material']['now']*-1 , 2, ',', '.'); ?>
        <th><?= number_format(($consolidation['COGS Material']['now']*-1)/$purchase['COGS Material']['now']*-1 * 100, 2, ',', '.'); ?> %
    <tr>
        <th>合計
        <th>Total
        <th><?= number_format($purchase['COGS Material']['old']*-1 - $consolidation['COGS Material']['old']*-1, 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
        <th>合計
        <th>Total
        <th><?= number_format($purchase['COGS Material']['now']*-1 - $consolidation['COGS Material']['now']*-1, 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
</table>
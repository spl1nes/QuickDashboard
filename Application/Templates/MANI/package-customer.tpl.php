<?php
$customers = $this->getData('customers');
$sales = $this->getData('sales');
$consolidation = $this->getData('consolidation');

$top = [];
$total = ['old' => 0.0, 'now' => '0.0'];

$i = 0; 
foreach($customers['old'] as $name => $value) {
    $i++; if($i > 10) break; 

    $top['old'][$i] = ['name' => $value['name'], 'id' => $name, 'value' => $value['value']];
    $total['old'] += $value['value'];
}

$i = 0; 
foreach($customers['now'] as $name => $value) {
    $i++; if($i > 10) break; 

    $top['now'][$i] = ['name' => $value['name'], 'id' => $name, 'value' => $value['value']];
    $total['now'] += $value['value'];
}

?>
<h1>MANI Customers - <?= $this->getData('date')->format('Y/m'); ?></h1>

<table>
    <caption>Customer sales list</caption>
    <thead>
    <tr>
        <th>No
        <th>Customer
        <th>Sales
        <th>%
        <th>No
        <th>Customer
        <th>Sales
        <th>%
    <tbody>
    <?php for($i = 1; $i < 11; $i++) : ?>
    <tr>
        <td><?= $i; ?> - (<?= $top['old'][$i]['id'] ?>)
        <td><?= $top['old'][$i]['name']; ?>
        <td><?= number_format($top['old'][$i]['value'], 2, ',', '.'); ?>
        <td><?= number_format($top['old'][$i]['value']/$sales['Sales']['old'] * 100, 2, ',', '.'); ?> %
        <td><?= $i; ?> - (<?= $top['now'][$i]['id'] ?>)
        <td><?= $top['now'][$i]['name']; ?>
        <td><?= number_format($top['now'][$i]['value'], 2, ',', '.'); ?>
        <td><?= number_format($top['now'][$i]['value']/$sales['Sales']['now'] * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <td>
        <td>Other
        <td><?= number_format($sales['Sales']['old'] - $total['old'], 2, ',', '.'); ?>
        <td><?= number_format(($sales['Sales']['old'] - $total['old'])/$sales['Sales']['old'] * 100, 2, ',', '.'); ?> %
        <td>
        <td>Other
        <td><?= number_format($sales['Sales']['now'] - $total['now'], 2, ',', '.'); ?>
        <td><?= number_format(($sales['Sales']['now'] - $total['now'])/$sales['Sales']['now'] * 100, 2, ',', '.'); ?> %
    <tr>
        <th>小計
        <th>Subtotal
        <th><?= number_format($sales['Sales']['old'], 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
        <th>小計
        <th>Subtotal
        <th><?= number_format($sales['Sales']['now'], 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
    <tr>
        <th>内部取引消去
        <th>Interco
        <th><?= number_format($consolidation['Sales']['old'] , 2, ',', '.'); ?>
        <th><?= number_format(($consolidation['Sales']['old'])/$sales['Sales']['old'] * 100, 2, ',', '.'); ?> %
        <th>内部取引消去
        <th>Interco
        <th><?= number_format($consolidation['Sales']['now'] , 2, ',', '.'); ?>
        <th><?= number_format(($consolidation['Sales']['now'])/$sales['Sales']['now'] * 100, 2, ',', '.'); ?> %
    <tr>
        <th>合計
        <th>Total
        <th><?= number_format($sales['Sales']['old'] - $consolidation['Sales']['old'], 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
        <th>合計
        <th>Total
        <th><?= number_format($sales['Sales']['now'] - $consolidation['Sales']['now'], 2, ',', '.'); ?>
        <th><?= number_format(100, 2, ',', '.'); ?> %
</table>
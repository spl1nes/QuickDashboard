<?php
$pl = $this->getData('pl');
?>
<h1>P&L</h1>

<table style="width: 50%; float: left;">
    <caption>Sales by Domestic/Export</caption>
    <thead>
    <tr>
        <th>Name
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Sales
        <td><?= number_format($pl['Sales']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['Sales']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['Sales']['now'] ?? 0) - ($pl['Sales']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['Sales']['old']) ? 0 : number_format((($pl['Sales']['now'] ?? 0)/$pl['Sales']['old'] - 1)*100, 2, ',', '.')?> %
    <tr>
        <th>COGS Material
        <th><?= number_format($pl['COGS Material']['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($pl['COGS Material']['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($pl['COGS Material']['now'] ?? 0) - ($pl['COGS Material']['old'] ?? 0), 0, ',', '.') ?>
        <td><?= !isset($pl['COGS Material']['old']) ? 0 : number_format((($pl['COGS Material']['now'] ?? 0)/$pl['COGS Material']['old'] - 1)*100, 2, ',', '.')?> %
</table>
<?php
$reps = $this->getData('repsSales');
?>
<h1>Sales by Sales Rep - <?= $this->getData('date')->format('Y/m'); ?> <?= $this->getData('type'); ?></h1>
<p>The sales by sales reps are based on all customers assigned to that sales rep. This also includes sales that are usually not recognized in other reportings as part of the sales rep sales. The total sales by sales rep can be different from the actual total sales due to cut-off tests and the resulting different sales recognition in the correct period.</p>
<table>
    <caption>Sales by Sales Rep</caption>
    <thead>
        <tr>
            <th>Name
            <th>Prev. Year
            <th>Current
            <th>Diff.
            <th>Diff. %
    <tbody>
        <?php foreach($reps as $name => $value) : ?>
        <tr>
            <td><?= $name; ?>
            <td><?= number_format($reps[$name]['old'] ?? 0, 0, ',', '.') ?>
            <td><?= number_format($reps[$name]['now'] ?? 0, 0, ',', '.') ?>
            <td><?= number_format(($reps[$name]['now'] ?? 0)-($reps[$name]['old'] ?? 0), 0, ',', '.') ?>
            <td><?= number_format(!isset($reps[$name]['old']) || $reps[$name]['old'] == 0 ? 0 : (($reps[$name]['now'] ?? 0)/$reps[$name]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
</table>

<div class="clear"></div>
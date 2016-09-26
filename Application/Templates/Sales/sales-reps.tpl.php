<?php
$reps = $this->getData('repsSales');
?>
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
            <td><?= number_format(!isset($reps[$name]) || $reps[$name]['old'] == 0 ? 0 : (($reps[$name]['now'] ?? 0)/$reps[$name]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
</table>

<div class="clear"></div>
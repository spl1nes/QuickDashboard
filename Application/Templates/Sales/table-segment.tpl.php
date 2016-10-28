<table style="width: 100%; float: left;">
    <caption>Sales Segmentation</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesGroups['All'] as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $temp) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups['All'][$segment][$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups['All'][$segment][$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups['All'][$segment][$group]['now'] ?? 0)-($salesGroups['All'][$segment][$group]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups['All'][$segment][$group]['old']) || $salesGroups['All'][$segment][$group]['old'] == 0 ? 0 : (($salesGroups['All'][$segment][$group]['now'] ?? 0)/$salesGroups['All'][$segment][$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format($segmentGroups['All'][$segment]['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($segmentGroups['All'][$segment]['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($segmentGroups['All'][$segment]['now'] ?? 0)-($segmentGroups['All'][$segment]['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups['All'][$segment]['old']) || $segmentGroups['All'][$segment]['old'] == 0 ? 0 : (($segmentGroups['All'][$segment]['now'] ?? 0)/$segmentGroups['All'][$segment]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($totalGroups['All']['old'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['All']['now'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['All']['now']-$totalGroups['All']['old'], 0, ',', '.') ?>
        <th><?= number_format(!is_numeric($totalGroups['All']['old']) || $totalGroups['All']['old'] == 0 ? 0 : (($totalGroups['All']['now'] ?? 0)/$totalGroups['All']['old']-1)*100, 0, ',', '.') ?> %
</table>
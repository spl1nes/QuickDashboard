<table style="width: 50%; float: left;">
    <caption>Sales by Region</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Europe
        <td><?= number_format($salesRegion['old']['Europe'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Europe'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Europe'] ?? 0) - ($salesRegion['old']['Europe'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Europe']) || $salesRegion['old']['Europe'] == 0 ? 0 : (($salesRegion['now']['Europe'] ?? 0)/$salesRegion['old']['Europe']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>America
        <td><?= number_format($salesRegion['old']['America'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['America'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['America'] ?? 0) - ($salesRegion['old']['America'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['America']) || $salesRegion['old']['America'] == 0 ? 0 : (($salesRegion['now']['America'] ?? 0)/$salesRegion['old']['America']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Asia
        <td><?= number_format($salesRegion['old']['Asia'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Asia'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Asia'] ?? 0) - ($salesRegion['old']['Asia'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Asia']) || $salesRegion['old']['Asia'] == 0 ? 0 : (($salesRegion['now']['Asia'] ?? 0)/$salesRegion['old']['Asia']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Africa
        <td><?= number_format($salesRegion['old']['Africa'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Africa'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Africa'] ?? 0) - ($salesRegion['old']['Africa'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Africa']) || $salesRegion['old']['Africa'] == 0 ? 0 : (($salesRegion['now']['Africa'] ?? 0)/$salesRegion['old']['Africa']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Oceania
        <td><?= number_format($salesRegion['old']['Oceania'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Oceania'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Oceania'] ?? 0) - ($salesRegion['old']['Oceania'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Oceania']) || $salesRegion['old']['Oceania'] == 0 ? 0 : (($salesRegion['now']['Oceania'] ?? 0)/$salesRegion['old']['Oceania']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Other
        <td><?= number_format($salesRegion['old']['Other'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Other'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Other'] ?? 0) - ($salesRegion['old']['Other'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Other']) || $salesRegion['old']['Other'] == 0 ? 0 : (($salesRegion['now']['Other'] ?? 0)/$salesRegion['old']['Other']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesRegion['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesRegion['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesRegion['now']) - array_sum($salesRegion['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesRegion['old']) || ($sum = array_sum($salesRegion['old'])) == 0 ? 0 : (array_sum($salesRegion['now'])/$sum-1)*100, 0, ',', '.') ?> %
</table>
<table style="width: 50%; float: left;">
    <caption>Sales by Domestic/Export</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Export
        <td><?= number_format($salesExportDomestic['old']['Export'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Export'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesExportDomestic['now']['Export'] ?? 0)-($salesExportDomestic['old']['Export'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Export']) || $salesExportDomestic['old']['Export'] == 0 ? 0 : (($salesExportDomestic['now']['Export'] ?? 0)/$salesExportDomestic['old']['Export']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Domestic
        <td><?= number_format($salesExportDomestic['old']['Domestic'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Domestic'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesExportDomestic['now']['Domestic'] ?? 0)-($salesExportDomestic['old']['Domestic'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Domestic']) || $salesExportDomestic['old']['Domestic'] == 0 ? 0 : (($salesExportDomestic['now']['Domestic'] ?? 0)/$salesExportDomestic['old']['Domestic']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesExportDomestic['old'] ?? []), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesExportDomestic['now'] ?? []), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesExportDomestic['now'] ?? [])-array_sum($salesExportDomestic['old'] ?? []), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesExportDomestic['old']) || ($sum = array_sum($salesExportDomestic['old'])) == 0 ? 0 : (array_sum($salesExportDomestic['now'])/$sum-1)*100, 0, ',', '.') ?> %
</table>
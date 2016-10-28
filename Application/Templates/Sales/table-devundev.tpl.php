<table style="width: 50%; float: left;">
    <caption>Sales by Developed/Undeveloped</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Developed
        <td><?= number_format($salesDevUndev['old']['Developed'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Developed'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Developed']-$salesDevUndev['old']['Developed'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Developed']) || $salesDevUndev['old']['Developed'] == 0 ? 0 : (($salesDevUndev['now']['Developed'] ?? 0)/$salesDevUndev['old']['Developed']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Undeveloped
        <td><?= number_format($salesDevUndev['old']['Undeveloped'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Undeveloped'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesDevUndev['now']['Undeveloped'] ?? 0)-($salesDevUndev['old']['Undeveloped'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Undeveloped']) || $salesDevUndev['old']['Undeveloped'] == 0 ? 0 : (($salesDevUndev['now']['Undeveloped'] ?? 0)/$salesDevUndev['old']['Undeveloped']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesDevUndev['old'] ?? []), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesDevUndev['now'] ?? []), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesDevUndev['now'] ?? [])-array_sum($salesDevUndev['old'] ?? []), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesDevUndev['old']) || ($sum = array_sum($salesDevUndev['old'] ?? [])) == 0 ? 0 : (array_sum($salesDevUndev['now'] ?? [])/$sum-1)*100, 0, ',', '.') ?> %
</table>
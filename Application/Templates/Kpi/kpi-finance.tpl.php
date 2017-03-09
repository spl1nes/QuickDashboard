<?php
$balance = $this->getData('balance');
$pl = $this->getData('pl');
$year = $this->getData('current');
$month = $this->getData('currentMonth');

function getAccountSum(array $accounts, int $year, int $month, array $total)
{
    $sum = 0.0;
    foreach($accounts as $account) {
        $sum += $total[$account][$year]['M' . $month] ?? 0;
    }

    return $sum;
}

function getAccountSumSingle(array $accounts, int $year, int $month, array $total)
{
    $sum = 0.0;
    foreach($accounts as $account) {
        $sum += $total[$account][$year]['S' . $month] ?? 0;
    }

    return $sum;
}
?>
<h1>Financial KPI</h1>

<p>The KPIs are not recognizing creditors with debit balance as debitors and vice versa.</p>

<table>
    <caption>KPI by Month</caption>
    <thead>
    <tr>
        <th>Type
        <th>PY
        <th>Jul.
        <th>Aug.
        <th>Sep.
        <th>Oct.
        <th>Nov.
        <th>Dec.
        <th>Jan.
        <th>Feb.
        <th>Mar.
        <th>Apr.
        <th>May
        <th>Jun.
    <tbody>
    <tr>
    	<th>Sales
    	<td><?= number_format(getAccountSum(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year-1, 12, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 2, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 3, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 4, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 5, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 6, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 7, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 8, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 9, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 10, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 11, $pl)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 12, $pl)/-1000, 0, ',', '.'); ?>
    <tr>
    	<th>Cash
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year-1, 12, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 1, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 2, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 3, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 4, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 5, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 6, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 7, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 8, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 9, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 10, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 11, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, 12, $balance)/1000, 0, ',', '.'); ?>
    <tr>
    	<th>Accounts Receivable
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year-1, 12, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 1, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 2, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 3, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 4, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 5, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 6, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 7, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 8, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 9, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 10, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 11, $balance)/1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 12, $balance)/1000, 0, ',', '.'); ?>
    <tr>
    	<th>DSO
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year-1, 12, $balance)/getAccountSum(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year-1, 12, $pl)/-1 * 365, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 1, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 2, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 3, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 4, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 5, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 6, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 7, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 8, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 9, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 10, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 11, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1400, 1402, 1406, 994, 998, 996, 999, 995, 1405, 1407], $year, 12, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl)/-1 * 30, 0, ',', '.'); ?>
    <tr>
    	<th>Accounts Payable
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year-1, 12, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 1, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 2, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 3, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 4, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 5, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 6, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 7, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 8, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 9, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 10, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 11, $balance)/-1000, 0, ',', '.'); ?>
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, 12, $balance)/-1000, 0, ',', '.'); ?>
    <tr>
    	<th>DPO
    	<td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year-1, 12, $balance)/getAccountSum(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year-1, 12, $pl) * -365, 0, ',', '.'); ?>
    	<td><?= number_format($month < 1 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 1, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 1, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 2 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 2, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 2, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 3 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 3, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 3, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 4 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 4, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 4, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 5 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 5, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 5, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 6 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 6, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 6, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 7 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 7, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 7, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 8 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 8, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 8, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 9 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 9, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 9, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 10 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 10, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 10, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 11 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 11, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 11, $pl) * -30.4, 0, ',', '.'); ?>
    	<td><?= number_format($month < 12 ? 0 : getAccountSum([1600, 1602, 1605, 1607], $year, 12, $balance)/getAccountSumSingle(array_merge(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Material'], \QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['COGS Services']), $year, 12, $pl) * -30.4, 0, ',', '.'); ?>
    <tr>
        <th>Stock
        <td><?= number_format(getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year-1, 12, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 1 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 1, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 2 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 2, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 3 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 3, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 4 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 4, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 5 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 5, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 6 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 6, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 7 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 7, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 8 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 8, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 9 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 9, $balance)/1000, 0, ',', '.'); ?>
        <td><?= number_format($month < 10 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 10, $balance)/100, 0, ',', '.'); ?>
        <td><?= number_format($month < 11 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 11, $balance)/100, 0, ',', '.'); ?>
        <td><?= number_format($month < 12 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 12, $balance)/100, 0, ',', '.'); ?>
    <tr>
        <th>DIO
        <td><?= number_format(getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year-1, 12, $balance)/getAccountSum(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year-1, 12, $pl) * -365, 0, ',', '.'); ?>
        <td><?= number_format($month < 1 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 1, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 1, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 2 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 2, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 2, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 3 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 3, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 3, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 4 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 4, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 4, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 5 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 5, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 5, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 6 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 6, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 6, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 7 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 7, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 7, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 8 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 8, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 8, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 9 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 9, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 9, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 10 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 10, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 10, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 11 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 11, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 11, $pl) * -30.4, 0, ',', '.'); ?>
        <td><?= number_format($month < 12 ? 0 : getAccountSum([3970, 3985, 3975, 3980, 3981, 3982, 3983, 3984], $year, 12, $balance)/getAccountSumSingle(\QuickDashboard\Application\Models\StructureDefinitions::PL_ACCOUNTS['Sales'], $year, 12, $pl) * -30.4, 0, ',', '.'); ?>
</table>
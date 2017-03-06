<?php
$balance = $this->getData('balance');
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
?>
<h1>MANI Cash - <?= $this->getData('date')->format('Y/m'); ?></h1>

<table>
    <caption>現金預金内訳書 Cash deposit breakdown</caption>
    <thead>
    <tr>
        <th>通貨 Currency
        <th>金額 Amount of money
        <th>換算レート Exchange rate
        <th>帳簿金額 Book amount
        <th>Account
    <tbody>
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1000], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1000], $year, $month, $balance), 2, '.', ','); ?>
        <td>1000
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1100], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1100], $year, $month, $balance), 2, '.', ','); ?>
        <td>1100
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1200], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1200], $year, $month, $balance), 2, '.', ','); ?>
        <td>1200
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1240], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1240], $year, $month, $balance), 2, '.', ','); ?>
        <td>1240
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1280], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1280], $year, $month, $balance), 2, '.', ','); ?>
        <td>1280
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1360], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1360], $year, $month, $balance), 2, '.', ','); ?>
        <td>1360
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1361], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1361], $year, $month, $balance), 2, '.', ','); ?>
        <td>1361
    <tr>
        <td>€
        <td><?= number_format(getAccountSum([1362], $year, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(1, 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1362], $year, $month, $balance), 2, '.', ','); ?>
        <td>1362
    <tr>
        <th>€
        <th>
        <th>合計 Total
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, $month, $balance), 2, '.', ','); ?>
        <th>
</table>
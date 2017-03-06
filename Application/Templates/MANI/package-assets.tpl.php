<?php
$balance = $this->getData('balance');
$ahkBeginning = $this->getData('ahkBeginning');
$ahkAddition = $this->getData('ahkAddition');
$ahkSubtraction = $this->getData('ahkSubtraction');
$entries = $this->getData('entries');
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
<h1>Fixed Assets - <?= $this->getData('date')->format('Y/m'); ?></h1>

<table>
    <caption>Fixed Assets</caption>
    <thead>
    <tr>
        <th>Items
        <th>Beg.
        <th>Net Beg.
        <th>Add.
        <th>Sub.
        <th>Armortize
        <th>Depr.
        <th>Net End.
        <th>End.
        <th>Acc. Beg.
        <th>Depr. Add.
        <th>Depr. Sub.
        <th>Acc. End.
    <tbody>
    <tr>
    	<td>Building
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    <tr>
    	<td>Machines
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    <tr>
    	<td>Vehicle
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    <tr>
    	<td>Office
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    <tr>
    	<td>Construction
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    <tr>
    	<th>Tangible
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    <tr>
    	<td>Web + other
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    	<td>
    <tr>
    	<td>Software
    	<td><?= number_format(($ahkBeginning[27] ?? 0), 2, '.', ','); ?>
    	<td><?= number_format(($entries[27][9000] ?? 0)*-1, 2, '.', ','); ?>
    	<td><?= number_format($ahkAddition[27] ?? 0, 2, '.', ','); ?>
    	<td><?= number_format($ahkSubtraction[27] ?? 0, 2, '.', ','); ?>
    	<td><?= number_format(($entries[27][9000] ?? 0)*-1 + ($ahkAddition[27] ?? 0) + ($ahkSubtraction[27] ?? 0), 2, '.', ','); ?>
    	<td><?= number_format(($entries[27][4822] ?? 0) + ($entries[27][0] ?? 0) + ($entries[27][''] ?? 0), 2, '.', ','); ?>
    	<td><?= number_format(($entries[27][9000] ?? 0)*-1 + ($ahkAddition[27] ?? 0) + ($ahkSubtraction[27] ?? 0) - (($entries[27][4822] ?? 0) + ($entries[27][0] ?? 0) + ($entries[27][''] ?? 0)), 2, '.', ','); ?>
    	<td><?= number_format(($ahkBeginning[27] ?? 0) + ($ahkAddition[27] ?? 0), 2, '.', ','); ?>
    	<td><?= number_format(($ahkBeginning[27] ?? 0) - ($entries[27][9000] ?? 0)*-1, 2, '.', ','); ?>
    	<td><?= number_format(($entries[27][4822] ?? 0) + ($entries[27][0] ?? 0) + ($entries[27][''] ?? 0), 2, '.', ','); ?>
    	<td><?= number_format(-($ahkSubtraction[27] ?? 0), 2, '.', ','); ?>
    	<td><?= number_format(($ahkBeginning[27] ?? 0) - ($entries[27][9000] ?? 0)*-1 + ($entries[27][4822] ?? 0) + ($entries[27][0] ?? 0) + ($entries[27][''] ?? 0) - (-($ahkSubtraction[27] ?? 0)), 2, '.', ','); ?>
    <tr>
    	<th>Intangible
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    <tr>
    	<th>Total
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
    	<th>
</table>
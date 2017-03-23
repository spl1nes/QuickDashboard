<?php
$balance = $this->getData('balance');
$ahkBeginning = $this->getData('ahkBeginning');
$ahkAddition = $this->getData('ahkAddition');
$ahkSubtraction = $this->getData('ahkSubtraction');
$entries = $this->getData('entries');
$year = $this->getData('current');
$month = $this->getData('currentMonth');

function getAccountSum(array $account, array $arr)
{
	$sum = 0;
	foreach($account as $key => $value) {
		$sum += $arr[$value] ?? 0;
	}

	return $sum;
}

function getAccountSum2(array $account1, array $account2, array $arr)
{
	$sum = 0;
	foreach($account1 as $key => $value) {
		foreach($account2 as $key2 => $value2) {
			$sum += $arr[$value][$value2] ?? 0;
		}
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
    	<td><?= number_format(getAccountSum([165], $ahkBeginning), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([165], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([165], $ahkAddition), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([165], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([165], [9000], $entries)*-1 + getAccountSum([165], $ahkAddition) + getAccountSum([165], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([165], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([165], [9000], $entries)*-1 + getAccountSum([165], $ahkAddition) + getAccountSum([165], $ahkSubtraction) - getAccountSum2([165], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([165], $ahkBeginning) + getAccountSum([165], $ahkAddition), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([165], $ahkBeginning) - getAccountSum2([165], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([165], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(-getAccountSum([165], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([165], $ahkBeginning) + getAccountSum2([165], [9000, 4822, 0, ''], $entries) - (-getAccountSum([165], $ahkSubtraction)), 2, '.', ','); ?>
    <tr>
    	<td>Machines
    	<td><?= number_format(getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $ahkBeginning), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $ahkAddition) - getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $ahkSubtraction), 2, '.', ','); ?>
    	<td>
    	<td>
    	<td><?= number_format(getAccountSum2([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], [4830, 4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td>
    	<td><?= number_format(getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $ahkBeginning) - getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $ahkAddition) - getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $ahkBeginning) - getAccountSum2([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], [4830, 4822, 0, ''], $entries), 2, '.', ','); ?>
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
    	<td><?= number_format(getAccountSum([25, 26], $ahkBeginning), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([25, 26], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([25, 26], $ahkAddition), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([25, 26], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([25, 26], [9000], $entries)*-1 + getAccountSum([25, 26], $ahkAddition) + getAccountSum([25, 26], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([25, 26], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([25, 26], [9000], $entries)*-1 + getAccountSum([25, 26], $ahkAddition) + getAccountSum([25, 26], $ahkSubtraction) - getAccountSum2([25, 26], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([25, 26], $ahkBeginning) + getAccountSum([25, 26], $ahkAddition), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([25, 26], $ahkBeginning) - getAccountSum2([25, 26], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([25, 26], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(-getAccountSum([25, 26], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([25, 26], $ahkBeginning) + getAccountSum2([25, 26], [9000, 4822, 0, ''], $entries) - (-getAccountSum([25, 26], $ahkSubtraction)), 2, '.', ','); ?>
    <tr>
    	<td>Software
    	<td><?= number_format(getAccountSum([27], $ahkBeginning), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([27], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([27], $ahkAddition), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([27], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([27], [9000], $entries)*-1 + getAccountSum([27], $ahkAddition) + getAccountSum([27], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([27], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([27], [9000], $entries)*-1 + getAccountSum([27], $ahkAddition) + getAccountSum([27], $ahkSubtraction) - getAccountSum2([27], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([27], $ahkBeginning) + getAccountSum([27], $ahkAddition), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([27], $ahkBeginning) - getAccountSum2([27], [9000], $entries)*-1, 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum2([27], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<td><?= number_format(-getAccountSum([27], $ahkSubtraction), 2, '.', ','); ?>
    	<td><?= number_format(getAccountSum([27], $ahkBeginning) + getAccountSum2([27], [9000, 4822, 0, ''], $entries) - (-getAccountSum([27], $ahkSubtraction)), 2, '.', ','); ?>
    <tr>
    	<th>Intangible
    	<th><?= number_format(getAccountSum([25, 26, 27], $ahkBeginning), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum2([25, 26, 27], [9000], $entries)*-1, 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum([25, 26, 27], $ahkAddition), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum([25, 26, 27], $ahkSubtraction), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum2([25, 26, 27], [9000], $entries)*-1 + getAccountSum([25, 26, 27], $ahkAddition) + getAccountSum([25, 26, 27], $ahkSubtraction), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum2([25, 26, 27], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum2([25, 26, 27], [9000], $entries)*-1 + getAccountSum([25, 26, 27], $ahkAddition) + getAccountSum([25, 26, 27], $ahkSubtraction) - getAccountSum2([25, 26, 27], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum([25, 26, 27], $ahkBeginning) + getAccountSum([25, 26, 27], $ahkAddition), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum([25, 26, 27], $ahkBeginning) - getAccountSum2([25, 26, 27], [9000], $entries)*-1, 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum2([25, 26, 27], [4822, 0, ''], $entries), 2, '.', ','); ?>
    	<th><?= number_format(-getAccountSum([25, 26, 27], $ahkSubtraction), 2, '.', ','); ?>
    	<th><?= number_format(getAccountSum([25, 26, 27], $ahkBeginning) + getAccountSum2([25, 26, 27], [9000, 4822, 0, ''], $entries) - (-getAccountSum([25, 26, 27], $ahkSubtraction)), 2, '.', ','); ?>
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
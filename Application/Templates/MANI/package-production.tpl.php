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
?>

<table>
    <caption>Production</caption>
    <thead>
    <tr>
        <th>ID
        <th>科目
        <th>Title
        <th>PY
        <th>PY Month
        <th>Current Year
    <tbody>
    <tr><td>601<td>期首材料棚卸高<td>Initial materials
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>602<td>期首副資材棚卸<td>Initial secondary material<td><td><td>
    <tr><td>603<td>期首包装資材棚<td>Initial Packaging material<td><td><td>
    <tr><td>611<td>材 料 仕 入 高<td>Material purchased
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>613<td>副資材 仕 入高<td>Secondary material purchased<td><td><td>
    <tr><td>614<td>包装資材仕入高<td>Packaging material purchased<td><td><td>
    <tr><td>620<td>材料他勘定振替<td>Transfer to other accounts<td><td><td>
    <tr><td>621<td>期末材料棚卸高<td>Ending materials
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>622<td>期末副資材棚卸<td>Ending secondary material<td><td><td>
    <tr><td>623<td>期末包装資材棚<td>Ending packaging material<td><td><td>
    <tr><th><th>材料費<th>Cost of materials
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>副資材<th>Secondary materials
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>包装資材<th>Packaging material
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>他勘定振替<th>Transfer to other accounts
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>材料費計<th>Total material costs
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>631<td>(製)賃金手当<td>(Manufacturing)Wages
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>632<td>(製)賞    与<td>(Manufacturing)Bonuses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>633<td>(製)賞与繰入損<td>(Manufacturing)Provision of reserve for bonuses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>634<td>(製)雑    給<td>(Manufacturing)Other Salaries and allowances<td><td><td>
    <tr><td>635<td>(製)退 職 金<td>(Manufacturing)Retirement allowances<td><td><td>
    <tr><td>636<td>(製)退職繰入損<td>(Manufacturing)Retirement provision<td><td><td>
    <tr><td>638<td>(製)通勤手当<td>(Manufacturing)Cummuting allowance<td><td><td>
    <tr><td>639<td>(製)法定福利費<td>(Manufacturing)Legal welfare expenses<td><td><td>
    <tr><td>641<td>(製)福利厚生費<td>(Manufacturing)Welfare expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>642<td>(製)退職給付費<td>(Manufacturing)Retirement benefit cost<td><td><td>
    <tr><th><th>労務費計<th>(Manufacturing)Total labor costs
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>650<td>(製)子会社外注<td>Amount paid to subcontractor（subsidiary）<td><td><td>
    <tr><td>651<td>(製)専属外注<td>Amount paid to subcontractor（other than subsidiary)<td><td><td>
    <tr><td>652<td>(製)その他外注<td>Amount paid to subcontractor（other than the aboves)<td><td><td>
    <tr><td>653<td>(製)委 託 費<td>(Manufacturing)Consignment expense<td><td><td>
    <tr><th><th>外注加工費及び委託費計<th>Total amounts paid to subcontractors and commission fee
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>654<td>(製)電 力 費<td>(Manufacturing)cost of electricity<td><td><td>
    <tr><td>655<td>(製)燃 料 費<td>(Manufacturing)Fuel expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>656<td>(製)水道光熱費<td>(Manufacturing)Utility expenses<td><td><td>
    <tr><td>657<td>(製)修 繕 費<td>(Manufacturing)Repair expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>658<td>(製)保 守 費<td>(Manufacturing)maintenance cost
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>659<td>(製)消耗品費<td>(Manufacturing)Supplies expenses<td><td><td>
    <tr><td>661<td>(製)事務用品費<td>(Manufacturing)Stationery expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>662<td>(製)部品冶工具<td>(Manufacturing)Jig<td><td><td>
    <tr><td>664<td>(製)旅費交通費<td>(Manufacturing)Travel expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>665<td>(製)車 両 費<td>(Manufacturing)Vehicle expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>666<td>(製)通 信 費<td>(Manufacturing)Communication expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>667<td>(製)荷造包装費<td>(Manufacturing)Packaging and wrapping  expenses<td><td><td>
    <tr><td>668<td>(製)運    賃<td>(Manufacturing)Freight expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>669<td>(製)保 険 料<td>(Manufacturing)Insurance expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>(製)車輌賃借料<td>(Manufacturing)Vehicle lease expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>(製)機械賃借料<td>(Manufacturing)Machinery lease expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>(製)建物賃借料<td>(Manufacturing)Building rent expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>671<td>(製)賃 借 料<td>(Manufacturing)Rent expense<td><td><td>
    <tr><td>672<td>(製)図 書 費<td>(Manufacturing)Books and subscription
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>673<td>(製)支払手数料<td>(Manufacturing)Commission fee and Bank charges
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>674<td>(製)諸 会 費<td>(Manufacturing)Membership fees
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>675<td>(製)寄 付 金<td>(Manufacturing)Donations 
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>676<td>(製)会 議 費<td>(Manufacturing)Conference expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>677<td>(製)交 際 費<td>(Manufacturing)Entertainment expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>678<td>(製)教育QC費<td>(Manufacturing)Training expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>679<td>(製)研修負担金<td>(Manufacturing)Training load money<td><td><td>
    <tr><td>683<td>(製)租税公課<td>(Manufacturing)Taxes and dues<td><td><td>
    <tr><td>684<td>(製)事業所税<td>(Manufacturing)Office tax<td><td><td>
    <tr><td>685<td>(製)固定資産税<td>(Manufacturing)Property tax<td><td><td>
    <tr><td>687<td>(製)リース料<td>(Manufacturing)Rental revenue<td><td><td>
    <tr><td>688<td>(製)減価償却費<td>(Manufacturing)Depreciation expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>689<td>(製)雑    費<td>(Manufacturing)Miscellaneous expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>製造経費計<th>Total production expenses
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>総製造費用<th>Grand total manufacturing cost
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>691<td>期首仕掛棚卸高<td>Beginning work in process
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>694<td>期末仕掛棚卸高<td>Ending work-in-process
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>692<td>仕掛品他勘定<td>Transfer to other accounts<td><td><td>
    <tr><th><th>当期製品製造原価<th>Cost of products manufactured
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
</table>
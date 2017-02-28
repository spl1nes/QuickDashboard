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
    <caption>R&D</caption>
    <thead>
    <tr>
        <th>ID
        <th>科目
        <th>Title
        <th>PY
        <th>PY Month
        <th>Current Year
    <tbody>
    <tr><td>711<td>役  員  報  酬<td>Officer's compensation or remuneration<td><td><td>
    <tr><td>712<td>給  料  手  当<td>Salaries and allowances
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>713<td>賞          与<td>Bonuses<td><td><td>
    <tr><td>714<td>賞与引当繰入損<td>Provision of reserve for bonuses<td><td><td>
    <tr><td>715<td>雑          給<td>Miscellaneous wages and salaries<td><td><td>
    <tr><td>716<td>退    職    金<td>Retirement allowances<td><td><td>
    <tr><td>718<td>役員退職金繰入<td>Provision of retirement allowance for officer<td><td><td>
    <tr><td>722<td>通  勤  手  当<td>Cummuting allowance<td><td><td>
    <tr><td>723<td>法 定 福 利 費<td>Legal welfare expenses<td><td><td>
    <tr><td>724<td>福 利 厚 生 費<td>Welfare expenses<td><td><td>
    <tr><td>725<td>退職給 付 費用<td>Retirement benefit cost<td><td><td>
    <tr><th><th>人件費小計<th>Total personnel expenses
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>726<td>販 売 促 進 費<td>Promotional expense<td><td><td>
    <tr><td>727<td>見    本    費<td>Samples expense<td><td><td>
    <tr><td>728<td>販売拡大奨励金<td>Sales promotion premium<td><td><td>
    <tr><td>729<td>販 売 調 査 費<td>Sales investigation expense<td><td><td>
    <tr><td>731<td>広 告 宣 伝 費<td>Advertising expenses<td><td><td>
    <tr><td>732<td>旅 費 交 通 費<td>Travel expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>733<td>車    両    費<td>Vehicle expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>734<td>通    信    費<td>Communication expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>735<td>荷 造 包 装 費<td>Loading and unloading expenses<td><td><td>
    <tr><td>736<td>運          賃<td>Freight expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>737<td>保    険    料<td>Insurance expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>車 輌 賃 借 料<td>Vehicle lease expense<td><td><td>
    <tr><td><td>機 械 賃 借 料<td>Machinery lease expense<td><td><td>
    <tr><td><td>建 物 賃 借 料<td>Building rent expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>738<td>賃    借    料<td>Rent expense<td><td><td>
    <tr><td>739<td>図    書    費<td>Books and subscription
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>741<td>支 払 手 数 料<td>Commission fee and Bank charges<td><td><td>
    <tr><td>742<td>諸    会    費<td>Membership fees<td><td><td>
    <tr><td>743<td>寄    付    金<td>Donation<td><td><td>
    <tr><td>744<td>会    議    費<td>Conference expense<td><td><td>
    <tr><td>745<td>交    際    費<td>Entertainment expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>746<td>燃    料    費<td>Fuel expenses(Diesel and Gasoline)<td><td><td>
    <tr><td>747<td>水 道 光 熱 費<td>Utility expenses<td><td><td>
    <tr><td>748<td>修    繕    費<td>Repair expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>749<td>保    守    料<td>Maintenance fee<td><td><td>
    <tr><td>751<td>消  耗  品  費<td>Supplies expense<td><td><td>
    <tr><td>752<td>事 務 用 品 費<td>Stationery expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>753<td>研 究 開 発 費<td>Research and development expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>754<td>子会社外注加費<td>Amount paid to subcontractors(subsidiary)<td><td><td>
    <tr><td>756<td>委    託    費<td>Consignment expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>757<td>教 育 Ｑ Ｃ 費<td>Training fee<td><td><td>
    <tr><td>758<td>研 修 負 担 金<td>Training load money<td><td><td>
    <tr><td>759<td>支  払  報  酬<td>Remuneration<td><td><td>
    <tr><td>761<td>顧    問    料<td>Payment to advisors<td><td><td>
    <tr><td>762<td>支　払　地　代<td>Land rent fee<td><td><td>
    <tr><td>763<td>支　払　家　賃<td>Rent fee<td><td><td>
    <tr><td>771<td>租  税  公  課<td>Taxes and dues<td><td><td>
    <tr><td>772<td>事　　業　　税<td>Enterprise tax<td><td><td>
    <tr><td>773<td>事  業  所  税<td>Office tax<td><td><td>
    <tr><td>774<td>固 定 資 産 税<td>Property tax<td><td><td>
    <tr><td>781<td>リ  ー  ス  料<td>Lease payment<td><td><td>
    <tr><td>782<td>減 価 償 却 費<td>Depreciation expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>783<td>貸倒引当金繰入<td>Provision for allowance for bad debts<td><td><td>
    <tr><td>788<td>貸倒損失(課税)<td>Bad debt expense<td><td><td>
    <tr><td>791<td>雑          費<td>Miscellaneous expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>人件費を除く経費計<th>Total selling, general & admin expenses (w/o HR)
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>研究開発費内訳書<th>Research and development expenses breakdown 
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
</table>
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
<table>
    <caption>PL</caption>
    <thead>
    <tr>
        <th>ID
        <th>科目
        <th>Title
        <th>SD
        <th>GDF
        <th>MANI
    <tbody>
    <tr><td>507<td>眼 科 売 上 高<td>Sales for ophthalmic instruments<td><td><td>
    <tr><td>511<td>ｻｰｼﾞｶﾙ売上高<td>Sales for surgical instruments<td><td><td>
    <tr><td>512<td>ｱｲﾚｽ売上高<td>Sales for eyeless needle instruments<td><td><td>
    <tr><td>513<td>ﾃﾞﾝﾀﾙ売上高<td>Sales for dental instruments
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>審美レジン<td>Standard consumables
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>貴金属<td>Precious alloys
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>デジタルワークフォローシステム<td>Digital workflow system (incl. digital consumables)
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>インプラント<td>Implantology
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>その他<td>Miscellaneous
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td><td>MANI Articles
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>514<td>売上高<td><td><td><td>
    <tr><td>517<td>事業開発売上高<td><td><td><td>
    <tr><th><th>純売上高<th>Net sales
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>541<td>他勘定 受 入高<td><td><td><td>
    <tr><td><td>商 品 仕 入 高<td>Merchandise purchases
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>期首商品棚卸高<td>Beginning merchandise inventory
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>571<td>期首製品棚卸高<td>Beginning product inventories 
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>当期製造原価<td>Cost of products manufactured
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>計<th>Total
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>期末商品棚卸高<td>Year-end merchandise inventories
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>591<td>期末製品棚卸高<td>Year-end finished product inventories
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>592<td>製品他勘定振替<td>Finished goods transfer to other account<td><td><td>
    <tr><th><th>売上原価計<th>cost of goods sold
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>売上総利益<th>Gross profit
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>711<td>役  員  報  酬<td>Officer's compensation or remuneration<td><td><td>
    <tr><td>712<td>給  料  手  当<td>Salaries and allowances
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>713<td>賞          与<td>Bonus
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>714<td>賞与引当繰入損<td>Provision of reserve for bonuses<td><td><td>
    <tr><td>715<td>雑          給<td>Miscellaneous wages and salaries<td><td><td>
    <tr><td>716<td>退    職    金<td>Retirement allowances<td><td><td>
    <tr><td>718<td>役員退職金繰入<td>Provision of retirement allowance for officer<td><td><td>
    <tr><td>722<td>通  勤  手  当<td>Cummuting allowance<td><td><td>
    <tr><td>723<td>法 定 福 利 費<td>Legal welfare expenses<td><td><td>
    <tr><td>724<td>福 利 厚 生 費<td>Welfare expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>725<td>退職給 付 費用<td>Retirement benefit cost
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>人件費小計<th>Total personnel expenses
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>726<td>販 売 促 進 費<td>Promotional expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>727<td>見    本    費<td>Samples expense<td><td><td>
    <tr><td>728<td>販売拡大奨励金<td>Sales promotion premium<td><td><td>
    <tr><td>729<td>販 売 調 査 費<td>Sales investigation expense<td><td><td>
    <tr><td>731<td>広 告 宣 伝 費<td>Advertising expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
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
    <tr><td><td>車 輌 賃 借 料<td>Vehicle lease expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>機 械 賃 借 料<td>Machinery lease expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>建 物 賃 借 料<td>Building rent expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>738<td>賃    借    料<td>Rent expense<td><td><td>
    <tr><td>739<td>図    書    費<td>Books and subscription
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>741<td>支 払 手 数 料<td>Commission fee and Bank charges
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>742<td>諸    会    費<td>Membership fees
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>743<td>寄    付    金<td>Donation
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>744<td>会    議    費<td>Conference expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>745<td>交    際    費<td>Entertainment expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>746<td>燃    料    費<td>Fuel expenses(Diesel and Gasoline)
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>747<td>水 道 光 熱 費<td>Utility expenses 
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>748<td>修    繕    費<td>Repair expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>749<td>保    守    料<td>Maintenance fee
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>751<td>消  耗  品  費<td>Supplies expense<td><td><td>
    <tr><td>752<td>事 務 用 品 費<td>Stationery expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>753<td>研 究 開 発 費<td>Research and development expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>754<td>子会社外注加費<td>Amount paid to subcontractors(subsidiary)
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>756<td>委    託    費<td>Consignment expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>757<td>教 育 Ｑ Ｃ 費<td>Training fee
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>758<td>研 修 負 担 金<td>Training load money
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>759<td>支  払  報  酬<td>Remuneration
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>761<td>顧    問    料<td>Payment to advisors
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>762<td>支　払　地　代<td>Land rent fee<td><td><td>
    <tr><td>763<td>支　払　家　賃<td>Rent fee
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>771<td>租  税  公  課<td>Taxes and dues<td><td><td>
    <tr><td>772<td>事　　業　　税<td>Enterprise tax<td><td><td>
    <tr><td>773<td>事  業  所  税<td>Office tax<td><td><td>
    <tr><td>774<td>固 定 資 産 税<td>Property tax<td><td><td>
    <tr><td>781<td>リ  ー  ス  料<td>Lease payment<td><td><td>
    <tr><td>782<td>減 価 償 却 費<td>Depreciation expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>783<td>貸倒引当金繰入<td>Provision for allowance for bad debts
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>788<td>貸倒損失(課税)<td>Bad debt expense<td><td><td>
    <tr><td>791<td>雑          費<td>Miscellaneous expense
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>人件費を除く経費計<th>Total selling, general & admin expenses(w/o HR)
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>販売費及び一般管理費計<th>Total selling, general & admin expenses
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>営業損益<th>Net loss for the period
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>810<td>有証券受取利息<td>Interest on securities<td><td><td>
    <tr><td>811<td>受  取  利  息<td>Interest income
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>812<td>受 取 配 当 金<td>Dividends received<td><td><td>
    <tr><td>813<td>貸倒引当金戻入額<td>Profit from allowance for doubtful accounts
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>814<td>賃    貸    料<td>Rental charges<td><td><td>
    <tr><td>815<td>有価証券売却益<td>Profit on Sold securities<td><td><td>
    <tr><td>816<td>匿名組合投資収<td>Gain on investments in silent partnership<td><td><td>
    <tr><td>817<td>為  替  差  益<td>Foreign exchange profit<td><td><td>
    <tr><td>819<td>雑    収    入<td>Miscellaneous income
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>営業外収益<th>Non-0perating Income
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>821<td>支払利息割引料<td>Interest and discount expenses
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>822<td>有価証券支利息<td>Interest expense on securities<td><td><td>
    <tr><td>825<td>新 株 発 行 費<td>stock issue expenses<td><td><td>
    <tr><td>829<td>有価証券評価損<td>Loss from deleing In value of securities<td><td><td>
    <tr><td>831<td>有価証券売却損<td>Loss on sales of securities<td><td><td>
    <tr><td>833<td>貸倒損失<td>Bad debt expense<td><td><td>
    <tr><td>837<td>匿名組合投資損<td>Loss on investments in silent partnership<td><td><td>
    <tr><td>839<td>引  越  費  用<td>Move cost<td><td><td>
    <tr><td>841<td>為  替  差  損<td>Foreign exchange loss<td><td><td>
    <tr><td>899<td>雑    損    失<td>Miscellaneous losses<td><td><td>
    <tr><th><th>営業外費用<th>Non-Operating Expenses
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>経常損益<th>Ordinary Income
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>905<td>投資有価証券売却益<td>Profit on sales of Investment in securities<td><td><td>
    <tr><td>911<td>固定資産売却益<td>Gains on sale of fixed assets<td><td><td>
    <tr><td>915<td>賞与引当金戻入額<td>Bonus reserve insertion<td><td><td>
    <tr><td>917<td>保 険 解 約 益<td>Insurance cancellation profit<td><td><td>
    <tr><td>918<td>過年度損益修益<td>Profit from prior period adjustments<td><td><td>
    <tr><td>919<td>雑  収  入 (特)<td>Other extraordinary gain<td><td><td>
    <tr><th><th>特別利益<th>Extraordinary Profit
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>921<td>固定資産売却損<td>Loss on disposal of fixed assets<td><td><td>
    <tr><td>922<td>固定資産除却損<td>Loss on fixed assets disposed<td><td><td>
    <tr><td>923<td>役員退職金<td>Retirement allowances for officers<td><td><td>
    <tr><td>925<td>投資有価証評損<td>Loss on write down of  investments in securities<td><td><td>
    <tr><td>926<td>投資有価証売却損<td>Loss on sales of Investment in securities<td><td><td>
    <tr><td>928<td>過年度損益修損<td>Loss from prior period adjustments<td><td><td>
    <tr><td>929<td>雑  損  失 (特)<td>Other extraordinary loss
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>特別損失<th>Extraordinary Losses
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>税引前当期純損益<th>Net Income before Adjusting of Taxes,etc
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>931<td>法人住民事業税<td>Income Taxes
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>933<td>法人税等調整額<td>Deferred Income Taxes,etc<td><td><td>
    <tr><th><th>当期純損益<th>current term net income
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>437<td>前期繰越利益剰余金<td>surplus at the beginning of the period<td><td><td>
    <tr><td>952<td>中間配当額<td>profit transfer to Mani  (interim dividends)
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>当期繰越利益剰余金<th>Earned surplus carried forward to the following term
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
</table>
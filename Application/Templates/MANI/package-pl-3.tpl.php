<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/customer'); ?>">
    <table>
        <tr>
            <td><label for="segment">Segment ID:</label>
            <td><select id="segment" name="segment">
                <option value="0">All
                <?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::NAMING as $id => $name) : ?>
                <option value="<?= $id; ?>"<?php if(((int) $this->request->getData('segment')) == (int) $id) { echo ' selected'; $gId = $id; $gName = $name; }; ?>><?= $id; ?> - <?= $name; ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="5"><input type="submit" value="Analyse">
    </table>
</form>

<table>
    <caption>P&L</caption>
    <thead>
    <tr>
        <th>部名、製品名
        <th>Title
        <th>Value
    <tbody>
    <tr>
        <th>売上高 
        <th>Net sales
        <th>
    <tr>
        <td>最高期比 
        <td>Growth rate from the best record of the 4 years
        <td>
    <tr>
        <td>1期前売上 
        <td>Net sales of the last year
        <td>
    <tr>
        <td>2期前売上 
        <td>Net sales of two years ago
        <td>
    <tr>
        <td>3期前売上 
        <td>Net sales of three years ago
        <td>
    <tr>
        <td>4期前売上 
        <td>Net sales of four years ago
        <td>
    <tr>
        <th>最高期比 
        <th>Growth rate from the best record of the 4 years
        <th>
    <tr>
        <td>国内売上
        <td>Domestic sales
        <td>
    <tr>
        <td>海外売上
        <td>Export sales
        <td>
    <tr>
        <td>外販数量（千本）
        <td>Amount sold
        <td>
    <tr>
        <td>平均売価
        <td>Average unit price
        <td>
    <tr>
        <td>1期前売価
        <td>Unit price of the last year
        <td>
    <tr>
        <td>2期前売価
        <td>Unit price of two years ago
        <td>
    <tr>
        <td>3期前売価
        <td>Unit price of three years ago
        <td>
    <tr>
        <td>4期前売価
        <td>Unit price of four years ago
        <td>
    <tr>
        <th>対前期値下率
        <th>Discounting rate from the last year
        <th>
    <tr>
        <td>平均売価（国内）
        <td>Average unit price (Domestic)
        <td>
    <tr>
        <td>前期売価（国内）
        <td>Unit price of the last year (Domestic)
        <td>
    <tr>
        <th>対前期値下率（国内）
        <th>Discounting rate from the last year (Domestic)
        <th>
    <tr>
        <td>平均売価（国外）
        <td>Average unit price (Export)
        <td>
    <tr>
        <td>前期売価（国外）
        <td>Unit price of the last year (Export)
        <td>
    <tr>
        <th>対前期値下率（国外）
        <th>Discounting rate from the last year (Export)
        <th>
    <tr>
        <td>期首材料棚卸高
        <td>Initial materials
        <td>
    <tr>
        <td>期首副資材棚卸高
        <td>Initial secondary material
        <td>
    <tr>
        <td>期首包装資材棚卸高
        <td>Initial Packaging material
        <td>
    <tr>
        <td>材料仕入高
        <td>Material purchased
        <td>
    <tr>
        <td>副資材仕入高
        <td>Secondary material purchased
        <td>
    <tr>
        <td>包装資材仕入高
        <td>Packaging material purchased
        <td>
    <tr>
        <td>期末材料棚卸高
        <td>Ending materials
        <td>
    <tr>
        <td>期末副資材棚卸高
        <td>Ending secondary material
        <td>
    <tr>
        <td>期末包装資材棚卸高
        <td>Ending packaging material
        <td>
    <tr>
        <th>材料費計
        <th>Total material costs
        <th>
    <tr>
        <th>労務費
        <th>Total labor costs
        <th>
    <tr>
        <td>外注費
        <td>Total amounts paid to subcontractors and commission fee
        <td>
    <tr>
        <td>減価償却費
        <td>Depreciation expense
        <td>
    <tr>
        <td>その他
        <td>Others
        <td>
    <tr>
        <th>その他製造経費
        <th>Total production expenses
        <th>
    <tr>
        <th>総製造費計
        <th>Grand total manufacturing cost
        <th>
    <tr>
        <td>生産数量(千本）
        <td>Amount manufactured (thousand pieces)
        <td>
    <tr>
        <td>1期前生産数量(千本)
        <td>Amount manufactured the last year (thousand pieces)
        <td>
    <tr>
        <td>2期前生産数量(千本)
        <td>Amount manufactured two years ago (thousand pieces)
        <td>
    <tr>
        <td>3期前生産数量(千本)
        <td>Amount manufactured three years ago (thousand pieces)
        <td>
    <tr>
        <td>4期前生産数量(千本)
        <td>Amount manufactured four years ago (thousand pieces)
        <td>
    <tr>
        <td>生産－販売(千本)
        <td>Amount manufactured - Amount sold (thousand pieces)
        <td>
    <tr>
        <td>仕掛品　　期首
        <td>Beginning work in process
        <td>
    <tr>
        <td>仕掛品　　現在
        <td>Ending work-in-process 
        <td>
    <tr>
        <td>仕掛品在庫増減
        <td>Work-in-process inventory increase/decrease
        <td>
    <tr>
        <td>仕掛品他勘定振替高
        <td>Transfer to other accounts 
        <td>
    <tr>
        <th>製造原価
        <th>Cost of products manufactured
        <th>
    <tr>
        <th>製造原平単価
        <th>Unit cost of products manufactured
        <th>
</table>
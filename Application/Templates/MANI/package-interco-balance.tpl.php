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
    <caption>Balance</caption>
    <thead>
    <tr>
        <th>ID
        <th>科目
        <th>Title
        <th>SD
        <th>GDF
        <th>MANI
    <tbody>
    <tr><td>111<td>現          金<td>Cash on hand
        <td><?= number_format(getAccountSum([1000], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1000], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1000], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>121<td>当  座  預  金<td>Current account
        <td><?= number_format(getAccountSum([1100, 1200, 1240, 1280, 1360, 1361, 1362], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1100, 1200, 1240, 1280, 1360, 1361, 1362], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>131<td>普  通  預  金<td>Saving account<td><td><td>
    <tr><td>141<td>別  段  預  金<td>Separate deposit<td><td><td>
    <tr><td>142<td>通  知  預  金<td>Notice account<td><td><td>
    <tr><td>143<td>定  期  預  金<td>Fiexed deposit account<td><td><td>
    <tr><th><th>現預金計<th>Total cash
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>150<td>受  取  手  形<td>Notes receivable-trade<td><td><td>
    <tr><td>152<td>売    掛    金<td>Accounts receivable
        <td><?= number_format(getAccountSum([1400, 1405, 1407], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1400, 1405, 1407], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1400, 1405, 1407], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>売上債権計<th>Total trade receivables
        <th><?= number_format(getAccountSum([1400, 1405, 1407], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1400, 1405, 1407], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1400, 1405, 1407], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>161<td>有  価  証  券<td>Securities<td><td><td>
    <tr><td>163<td>抵　当　証　券<td>Mortgage certificates<td><td><td>
    <tr><th><th>当座資産計<th>Total liquid assets
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>商　　　　　品<td>Merchandise
        <td><?= number_format(getAccountSum([3980, 3981, 3982, 3983, 3984], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([3980, 3981, 3982, 3983, 3984], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([3980, 3981, 3982, 3983, 3984], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>172<td>製          品<td>Finished products
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>173<td>有 償 支 給 材<td>On cost material supplies<td><td><td>
    <tr><td>174<td>未 　 着    品<td>Goods in transit<td><td><td>
    <tr><td>175<td>材          料<td>Raw material
        <td><?= number_format(getAccountSum([3970, 3985], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([3970, 3985], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([3970, 3985], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>176<td>副    資    材<td>Secondary material<td><td><td>
    <tr><td>177<td>包  装  資  材<td>Packaging material<td><td><td>
    <tr><td>178<td>仕    掛    品<td>Work in progress
        <td><?= number_format(getAccountSum([3975], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([3975], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([3975], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>179<td>貯    蔵    品<td>Inventory goods<td><td><td>
    <tr><th><th>棚卸資産計<th>Total inventories
        <th><?= number_format(getAccountSum([3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>181<td>前    渡    金<td>Advance payment
        <td><?= number_format(getAccountSum([1519], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1519], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1519], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>184<td>未  収  収  益<td>Accrued income<td><td><td>
    <tr><td>182<td>立    替    金<td>Advance paid<td><td><td>
    <tr><td>183<td>短 期 貸 付 金<td>Short-term loans receivable<td><td><td>
    <tr><td>185<td>未  収  入  金<td>Accrued revenue
        <td><?= number_format(getAccountSum([1403, 1401], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1403, 1401], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1403, 1401], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>186<td>前  払  費  用<td>Prepaid expenses
        <td><?= number_format(getAccountSum([980, 981, 982], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([980, 981, 982], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([980, 981, 982], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>187<td>仮    払    金<td>Temporary payment<td><td><td>
    <tr><td>189<td>その他流動資産<td>Other current assets
        <td><?= number_format(getAccountSum([1410, 1440, 1520, 1525, 1590], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1410, 1440, 1520, 1525, 1590], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1410, 1440, 1520, 1525, 1590], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>190<td>繰延税金資産流<td>Deferred taxes assets-Current<td><td><td>
    <tr><td>191<td>仮払消 費 税等<td>Suspense consumption tax paid<td><td><td>
    <tr><td>197<td>未収消 費 税等<td>Consumption taxes receivable
        <td><?= number_format(getAccountSum([1539, 1540, 1567, 1570, 1575, 1587, 1545], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1539, 1540, 1567, 1570, 1575, 1587, 1545], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1539, 1540, 1567, 1570, 1575, 1587, 1545], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>199<td>貸倒引当金(流)<td>Allowance for doubtful accouts(current)
        <td><?= number_format(getAccountSum([994, 998, 996, 999, 995], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([994, 998, 996, 999, 995], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([994, 998, 996, 999, 995], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>その他流動資産計<th>Total other current assets
        <th><?= number_format(getAccountSum([1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>流動資産合計<th>Total current assets
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407, 3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975, 1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407, 3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975, 1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407, 3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975, 1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>211<td>建          物<td>Buildings<td><td><td>
    <tr><td>212<td>建物付 属 設備<td>buildings and accompanying facilities
        <td><?= number_format(getAccountSum([165], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([165], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([165], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>213<td>構    築    物<td>Structures<td><td><td>
    <tr><td>214<td>機  械  装  置<td>Machinery and equipment
        <td><?= number_format(getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>215<td>車 両 運 搬 具<td>Vehicle
        <td><?= number_format(getAccountSum([320], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([320], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([320], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>216<td>工  具  器  具<td>Tools
        <td><?= number_format(getAccountSum([440, 464], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([440, 464], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([440, 464], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>217<td>什  器  備  品<td>furniture and fixtures ＆ office equipment
        <td><?= number_format(getAccountSum([270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>221<td>土          地<td>Land<td><td><td>
    <tr><td>222<td>建 設 仮 勘 定<td>Construction in progress
        <td><?= number_format(getAccountSum([299], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([299], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([299], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>223<td>機 械 仮 勘 定<td>Machine in process account<td><td><td>
    <tr><td>229<td>減価償却累計額<td>Accumulated depreciation<td><td><td>
    <tr><th><th>有形固定資産計<th>Total Tangible Fixed Assets
        <th><?= number_format(getAccountSum([165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>232<td>電 話 加 入 権<td>Telephone subscription right<td><td><td>
    <tr><td>233<td>水道施設利用権<td>Water rights　of utilization<td><td><td>
    <tr><td>234<td>工業用水施設利<td>Water rights　of utilization（facilities)<td><td><td>
    <tr><td>235<td>電気通信施設利<td>Electricity rights　of utilization<td><td><td>
    <tr><td>237<td>ソフト ウ ェア<td>Software
        <td><?= number_format(getAccountSum([25, 26, 27, 39], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([25, 26, 27, 39], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([25, 26, 27, 39], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>238<td>ソフト仮勘定<td>Software in progress<td><td><td>
    <tr><th><th>無形固定資産計<th>Total intangible fixed assets
        <th><?= number_format(getAccountSum([25, 26, 27, 39], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([25, 26, 27, 39], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([25, 26, 27, 39], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>240<td>投資有 価 証券<td>Securities of investments<td><td><td>
    <tr><td>241<td>子 会 社 株 式<td>Stocks of subsidiaries<td><td><td>
    <tr><td>242<td>出    資    金<td>Investments in capital<td><td><td>
    <tr><td>243<td>長 期 貸 付 金<td>Long-term loans<td><td><td>
    <tr><td>244<td>差 入 保 証 金<td>Lease deposit<td><td><td>
    <tr><td>245<td>長期前払費用<td>Long-term prepaid expenses<td><td><td>
    <tr><td>246<td>保 険 積 立 金<td>Insurance reserve<td><td><td>
    <tr><td>247<td>そ の 他 投 資<td>Other investments<td><td><td>
    <tr><td>248<td>繰延税金資産固<td>Deferred taxes assets-non current<td><td><td>
    <tr><td>249<td>長 期 性 預 金<td>Long-term deposits<td><td><td>
    <tr><td>259<td>貸倒引当金(固)<td>Allowance for doubtful accounts(non-current)<td><td><td>
    <tr><td>260<td>自  己  株  式<td>Treasury stock－B/S<td><td><td>
    <tr><th><th>投資その他の資産<th>Total investments and other assets
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>固定資産合計<th>Total Fixed Assets
        <th><?= number_format(getAccountSum([165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299, 25, 26, 27, 39], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299, 25, 26, 27, 39], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299, 25, 26, 27, 39], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>297<td>その他繰延資産<td>Deferred assets(others)<td><td><td>
    <tr><th><th>繰延資産合計<th>Total Deferred assets
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>資産の部合計<th>Total Assets
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407, 3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975, 1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995, 165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299, 25, 26, 27, 39], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407, 3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975, 1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995, 165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299, 25, 26, 27, 39], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1000, 1100, 1200, 1240, 1280, 1360, 1361, 1362, 1400, 1405, 1407, 3980, 3981, 3982, 3983, 3984, 3970, 3985, 3975, 1519, 1403, 1401, 980, 981, 982, 1410, 1440, 1520, 1525, 1590, 1539, 1540, 1567, 1570, 1575, 1587, 1545, 994, 998, 996, 999, 995, 165, 200, 201, 205, 210, 211, 215, 220, 225, 230, 232, 235, 240, 241, 245, 250, 255, 260, 280, 320, 440, 464, 270, 400, 401, 402, 403, 404, 405, 410, 411, 419, 420, 421, 422, 423, 424, 431, 460, 461, 462, 480, 481, 485, 490, 299, 25, 26, 27, 39], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>301<td>支  払  手  形<td>Notes payable<td><td><td>
    <tr><td>302<td>設備支 払 手形<td>Notes payable for equipment<td><td><td>
    <tr><td>312<td>買    掛    金<td>Accounts payable
        <td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>仕入債務計<th>Total purchase liability
        <th><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([1600, 1602, 1605, 1607], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>321<td>短 期 借 入 金<td>Short-term loan payable
        <td><?= number_format(getAccountSum([702], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([702], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([702], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>322<td>未    払    金<td>Accounts payable - other
        <td><?= number_format(getAccountSum([1610, 1700, 1730, 1740, 1755], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1610, 1700, 1730, 1740, 1755], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1610, 1700, 1730, 1740, 1755], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>322-1<td><td>Equipment Payable<td><td><td>
    <tr><td>323<td>未  払  費  用<td>Accrued expenses
        <td><?= number_format(getAccountSum([990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>324<td>前    受    金<td>Advance received
        <td><?= number_format(getAccountSum([1722, 1724], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1722, 1724], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1722, 1724], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>325<td>前  受  収  益<td>Unearned revenue<td><td><td>
    <tr><td>326<td>預    り    金<td>Deposits received<td><td><td>
    <tr><td>327<td>未払法 人 税等<td>Taxes and payables to the state budget
        <td><?= number_format(getAccountSum([1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>328<td>未払事 業 所税<td>Accrued business office taxes<td><td><td>
    <tr><td>329<td>仮    受    金<td>Suspense receipt<td><td><td>
    <tr><td>332<td>未 払 配 当 金<td>Dividend payable<td><td><td>
    <tr><td>334<td>その他流動負債<td>Other current liabilities
        <td><?= number_format(getAccountSum([1601, 1603], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1601, 1603], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([1601, 1603], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>335<td>仮受消 費 税等<td>suspense receipt of consumption taxes<td><td><td>
    <tr><td>336<td>未払消 費 税等<td>Accrued consumption taxes<td><td><td>
    <tr><td>340<td>繰延税金負債流<td>deferred tax liability(current)<td><td><td>
    <tr><td>337<td>賞 与 引 当 金<td>Reserve for bonuses
        <td><?= number_format(getAccountSum([975], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([975], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([975], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>その他流動負債計<th>Total other current liabilities
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>流動負債合計<th>Total Current Liabilities
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>342<td>長 期 借 入 金<td>Long - term loans payable<td><td><td>
    <tr><td>343<td>預 り 保 証 金<td>Deposits received of guarantee<td><td><td>
    <tr><td>344<td>長 期 未 払 金<td>Long-term accounts payable<td><td><td>
    <tr><th><th>固定負債合計<th>Total Fixed liabilities
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>358<td>退職給付引当金<td>Reserve for retirement allowance<td><td><td>
    <tr><td>363<td>役員退職引当金<td>Reserve for retirement Allowance of officer<td><td><td>
    <tr><td>365<td>社内調整引当金<td>Reserve for internal coordination<td><td><td>
    <tr><td><td>休 暇 引 当 金<td>Vacation accrual
        <td><?= number_format(getAccountSum([971], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([971], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([971], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>366<td>繰延税金負債固<td>deferred tax liability(non-current)<td><td><td>
    <tr><th><th>引当金合計<th>Total allowances
        <th><?= number_format(getAccountSum([971], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([971], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([971], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>負債の部合計<th>Total Liabilities
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607, 971], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607, 971], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607, 971], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>411<td>資    本    金<td>Capital
        <td><?= number_format(getAccountSum([800], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([800], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([800], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>421<td>資 本 準 備 金<td>Capital surplus
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>資本剰余金計<th>Total capital surplus
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>422<td>利 益 準 備 金<td>Legal reserve
        <td><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>432<td>別 途 積 立 金<td>General reserve
        <td><?= number_format(getAccountSum([860], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([860], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([860], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td><td>繰越利益剰余金<td>Retained earnings
        <td><?= number_format(getAccountSum([9000, 9008, 9009], $year-1, 12, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([9000, 9008, 9009], $year-1, $month, $balance), 2, '.', ','); ?>
        <td><?= number_format(getAccountSum([9000, 9008, 9009], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>利益剰余金計<th>Total retained earnings
        <th><?= number_format(getAccountSum([860, 9000, 9008, 9009], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([860, 9000, 9008, 9009], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([860, 9000, 9008, 9009], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>434<td>その他有価証券評価差額<td>Unrealized appreciation of securities<td><td><td>
    <tr><th><th>株式等評価差額<th>Total unrealized appreciation of securities
        <th><?= number_format(getAccountSum([], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([], $year, $month, $balance), 2, '.', ','); ?>
    <tr><td>450<td>自己株式<td>Treasury stock－B/S<td><td><td>
    <tr><th><th>純資産の部合計<th>Total Net Asset
        <th><?= number_format(getAccountSum([800, 860, 9000, 9008, 9009], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([800, 860, 9000, 9008, 9009], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([800, 860, 9000, 9008, 9009], $year, $month, $balance), 2, '.', ','); ?>
    <tr><th><th>負債純資産合計<th>Total Liabilities and Net Asset
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607, 971, 800, 860, 9000, 9008, 9009], $year-1, 12, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607, 971, 800, 860, 9000, 9008, 9009], $year-1, $month, $balance), 2, '.', ','); ?>
        <th><?= number_format(getAccountSum([702, 1610, 1700, 1730, 1740, 1755, 990, 991, 1701, 950, 969, 970, 972, 973, 974, 977, 979, 1722, 1724, 1741, 1571, 1576, 1577, 1586, 1588, 1761, 1764, 1766, 1767, 1770, 1773, 1774, 1776, 1777, 1779, 1781, 1787, 1742, 1745, 956, 963, 1601, 1603, 975, 1600, 1602, 1605, 1607, 971, 800, 860, 9000, 9008, 9009], $year, $month, $balance), 2, '.', ','); ?>
</table>
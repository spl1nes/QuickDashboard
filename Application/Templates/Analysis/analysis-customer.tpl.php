<form>
    <table>
        <tr>
            <td><label>Customer:</label>
            <td><input type="text">
            <td><label>Unit:</label>
            <td><select>
                <option value='sd'<?= ($this->request->getData('cu') !== 'gdf' ? ' selected' : ''; ?>>SD
                <option value='gdf'<?= ($this->request->getData('cu') === 'gdf' ? ' selected' : ''; ?>>GDF</select>
            <td><input type="submit" value="Analyse">
    </table>
</form>

<?php if($this->request->getData('customer') != null && $this->request->getData('customer') != '') : ?>
<table style="width: 50%; float: left;">
    <caption>Customer - </caption>
    <tbody>
        <tr><th>Name:<td><?= $customer->getName(); ?>
        <tr><th>Address:<td><?= $customer->getAddress(); ?>
        <tr><th>Country:<td><?= $customer->getCountry(); ?>
        <tr><th>Group:<td><?= $customer->getCountry(); ?>
        <tr><th>Sales Rep:<td><?= $customer->getRep(); ?>
        <tr><th>Registered:<td><?= $customer->getCreatedAt(); ?>
</table>

<table style="width: 50%; float: left;">
    <caption>Stats</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last Year
        <th>Current
    <tbody>
        <tr><th>DSO:<td><?= $customer->getDSO(); ?>
        <tr><th>Orders:<td><?= $customer->getOrders(); ?>
        <tr><th>Avg. Order:<td><?= $customer->getAvgOrders(); ?>
        <tr><th>Sales:<td><?= $customer->getSales(); ?>
        <tr><th>Total Sales:<td><?= $customer->getSales(); ?>
</table>

<div class="clear"></div>

<div class="box" style="width: 50%; float: left">
    <canvas id="sales-month" height="270"></canvas>
</div>

<div class="box" style="width: 50%; float: left">
    <canvas id="sales-month-acc" height="270"></canvas>
</div>

<div class="box" style="width: 100%; float: left">
    <canvas id="sales-groups" height="270"></canvas>
</div>
<?php endif; ?>
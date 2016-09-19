<?php
$salesGroups = $this->getData('salesGroups');
$totalGroups = $this->getData('totalGroups');
?>
<h1>Sales Customers</h1>
<p>The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: 50%; float: left;">
    <caption>Sales By Domestic/Export</caption>
    <thead>
    <tr>
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesGroups as $group => $groups) : ?>
    <tr>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups[$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups[$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups[$group]['now'] ?? 0)-($salesGroups['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups[$group]) || $salesGroups[$group]['old'] == 0 ? 0 : (($salesGroups[$group]['now'] ?? 0)/$salesGroups[$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th>Total
        <th><?= number_format(array_sum($totalGroups['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($totalGroups['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($totalGroups['now'])-array_sum($totalGroups['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($totalGroups['old']) ? 0 : (array_sum($totalGroups['now'])/array_sum($totalGroups['old'])-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="group-sales" height="100"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 50%; float: left">
    <canvas id="top-customers-sales" height="100"></canvas>
</div>

<script>
    let configSalesSegments = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    <?= $salesGroups['Dentist']['old'] ?>
                ],
                backgroundColor: [
                    "#F7464A",
                    "#46BFBD",
                    "#FDB45C",
                    "#949FB1",
                    "#4D5360",
                    "#E2CF56"
                ],
                label: 'Current'
            }, {
                data: [
                    <?= $salesGroups['Dentist']['now'] ?>
                ],
                backgroundColor: [
                    "#F7464A",
                    "#46BFBD",
                    "#FDB45C",
                    "#949FB1",
                    "#4D5360",
                    "#E2CF56"
                ],
                label: 'Last Year'
            }],
            labels: [
                "Dentist",
                "Consumables",
                "Digitial",
                "Impla",
                "Misc.",
                "MANI",
            ]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Sales By Segments'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
        }
    };

    window.onload = function() {
        let ctxSalesSegments = document.getElementById("segment-sales");
        window.salesSegments = new Chart(ctxSalesSegments, configSalesSegments);
    };
</script>
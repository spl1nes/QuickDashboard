<?php
$salesGroups = $this->getData('salesGroups');
$totalGroups = $this->getData('totalGroups');
?>
<h1>Sales Customers</h1>
<p>The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: 50%; float: left;">
    <caption>Sales by Customer Groups</caption>
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
        <th><?= number_format($totalGroups['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($totalGroups['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($totalGroups['now'] ?? 0)-($totalGroups['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($totalGroups['old']) ? 0 : (($totalGroups['now'] ?? 0)/$totalGroups['old']-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="group-sales" height="<?= (int) (23.3 * count($salesGroups) + 50); ?>"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="top-customers-sales" height="100"></canvas>
</div>

<div class="clear"></div>

<script>
    let configSalesGroups = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    <?php $data = ''; foreach($salesGroups as $group) { $data .= number_format(($group['now'] ?? 0) / $totalGroups['now'] * 100, 0, ',', '.') . ','; } echo rtrim($data, ','); ?>
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
                    <?php $data = ''; foreach($salesGroups as $group) { $data .= number_format(($group['old'] ?? 0) / $totalGroups['old'] * 100, 0, ',', '.') . ','; } echo rtrim($data, ','); ?>
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
                <?= '"' . implode('","', array_keys($salesGroups)) . '"'; ?>
            ]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Sales Ratio by Groups'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.labels[tooltipItem.index] || 'Other';

                            return data.datasets[tooltipItem.datasetIndex].label + ' - ' + datasetLabel + ': ' + Math.round(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]).toString().split(/(?=(?:...)*$)/).join('.') + '%';
                          }
                }
            },
        }
    };

    window.onload = function() {
        let ctxSalesGroups = document.getElementById("group-sales");
        window.salesGroups = new Chart(ctxSalesGroups, configSalesGroups);
    };
</script>
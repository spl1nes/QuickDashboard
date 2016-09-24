<?php
$salesGroups = $this->getData('salesGroups');
$totalGroups = $this->getData('totalGroups');
$topCustomers = $this->getData('customer');
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
    <canvas id="top-customers-sales" height="200"></canvas>
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
                    "#56E2CF",
                    "#5668E2",
                    "#CF56E2",
                    "#E25668",
                    "#E2CF56",
                    "#68E256",
                    "#56AEE2",
                    "#8A56E2",
                    "#E256AE",
                    "#E28956",
                    "#AEE256",
                    "#56E289"
                ],
                label: 'Current'
            }, {
                data: [
                    <?php $data = ''; foreach($salesGroups as $group) { $data .= number_format(($group['old'] ?? 0) / $totalGroups['old'] * 100, 0, ',', '.') . ','; } echo rtrim($data, ','); ?>
                ],
                backgroundColor: [
                    "#56E2CF",
                    "#5668E2",
                    "#CF56E2",
                    "#E25668",
                    "#E2CF56",
                    "#68E256",
                    "#56AEE2",
                    "#8A56E2",
                    "#E256AE",
                    "#E28956",
                    "#AEE256",
                    "#56E289"
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

    let configTopCustomers = {
        type: 'bar',
        data: {
            labels: [<?= '"' . implode('","', array_keys($top = array_slice($topCustomers['now'], 0, 15, true))) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = $topCustomers['old'][$key] ?? 0 . ','; } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= implode(',', $top); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Sales by Countries"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
                    }
                }
            },
            scales: {
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }],
            }
        }
    };

    window.onload = function() {
        let ctxSalesGroups = document.getElementById("group-sales");
        window.salesGroups = new Chart(ctxSalesGroups, configSalesGroups);

        let ctxSalesCustomers = document.getElementById("top-customers-sales");
        window.salesCustomers = new Chart(ctxSalesCustomers, configTopCustomers);
    };
</script>
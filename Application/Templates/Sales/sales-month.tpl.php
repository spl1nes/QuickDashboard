<?php
$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$salesLast = $this->getData('salesLast');
$salesAccLast = $this->getData('salesAccLast');
$days = $this->getData('maxDays');
$today = $this->getData('today');

$salesExportDomestic = $this->getData('salesExportDomestic');
$salesDevUndev = $this->getData('salesDevUndev');
$salesRegion = $this->getData('salesRegion');
?>

<p>The following tables contain the sales of the current month compared to the same month of the last year. The calculation of developed and undeveloped countires is based on the MANI definition. The region calculation is mostly based on the ISO-3166 definition.</p>

<table style="width: 50%; float: left;">
    <caption>Sales By Domestic/Export</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Export
        <td><?= number_format($salesExportDomestic['old']['Export'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Export'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Export']-$salesExportDomestic['old']['Export'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Export']) || $salesExportDomestic['old']['Export'] == 0 ? 0 : ($salesExportDomestic['now']['Export']/$salesExportDomestic['old']['Export']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Domestic
        <td><?= number_format($salesExportDomestic['old']['Domestic'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Domestic'], 0, ',', '.') ?>
        <td><?= number_format($salesExportDomestic['now']['Domestic']-$salesExportDomestic['old']['Domestic'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesExportDomestic['old']['Domestic']) || $salesExportDomestic['old']['Domestic'] == 0 ? 0 : ($salesExportDomestic['now']['Domestic']/$salesExportDomestic['old']['Domestic']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesExportDomestic['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesExportDomestic['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesExportDomestic['now'])-array_sum($salesExportDomestic['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesExportDomestic['old']) ? 0 : (array_sum($salesExportDomestic['now'])/array_sum($salesExportDomestic['old'])-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="domestic-export-chart" height="130"></canvas>
</div>

<div class="clear"></div>

<table style="width: 50%; float: left;">
    <caption>Sales By Developed/Undeveloped</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Developed
        <td><?= number_format($salesDevUndev['old']['Developed'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Developed'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Developed']-$salesDevUndev['old']['Developed'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Developed']) || $salesDevUndev['old']['Developed'] == 0 ? 0 : ($salesDevUndev['now']['Developed']/$salesDevUndev['old']['Developed']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Undeveloped
        <td><?= number_format($salesDevUndev['old']['Undeveloped'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Undeveloped'], 0, ',', '.') ?>
        <td><?= number_format($salesDevUndev['now']['Undeveloped']-$salesDevUndev['old']['Undeveloped'], 0, ',', '.') ?>
        <td><?= number_format(!isset($salesDevUndev['old']['Undeveloped']) || $salesDevUndev['old']['Undeveloped'] == 0 ? 0 : ($salesDevUndev['now']['Undeveloped']/$salesDevUndev['old']['Undeveloped']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesDevUndev['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesDevUndev['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesDevUndev['now'])-array_sum($salesDevUndev['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesDevUndev['old']) || array_sum($salesDevUndev['old']) == 0 ? 0 : (array_sum($salesDevUndev['now'])/array_sum($salesDevUndev['old'])-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="developed-undeveloped-chart" height="130"></canvas>
</div>

<div class="clear"></div>

<table style="width: 50%; float: left;">
    <caption>Sales By Region</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <tr>
        <td>Europe
        <td><?= number_format($salesRegion['old']['Europe'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Europe'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Europe'] ?? 0) - ($salesRegion['old']['Europe'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Europe']) ? 0 : (($salesRegion['now']['Europe'] ?? 0)/$salesRegion['old']['Europe']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>America
        <td><?= number_format($salesRegion['old']['America'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['America'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['America'] ?? 0) - ($salesRegion['old']['America'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['America']) ? 0 : (($salesRegion['now']['America'] ?? 0)/$salesRegion['old']['America']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Asia
        <td><?= number_format($salesRegion['old']['Asia'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Asia'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Asia'] ?? 0) - ($salesRegion['old']['Asia'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Asia']) ? 0 : (($salesRegion['now']['Asia'] ?? 0)/$salesRegion['old']['Asia']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Africa
        <td><?= number_format($salesRegion['old']['Africa'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Africa'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Africa'] ?? 0) - ($salesRegion['old']['Africa'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Africa']) ? 0 : (($salesRegion['now']['Africa'] ?? 0)/$salesRegion['old']['Africa']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Oceania
        <td><?= number_format($salesRegion['old']['Oceania'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Oceania'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Oceania'] ?? 0) - ($salesRegion['old']['Oceania'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Oceania']) ? 0 : ($salesRegion['now']['Oceania']/$salesRegion['old']['Oceania']-1)*100, 0, ',', '.') ?> %
    <tr>
        <td>Other
        <td><?= number_format($salesRegion['old']['Other'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesRegion['now']['Other'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesRegion['now']['Other'] ?? 0) - ($salesRegion['old']['Other'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesRegion['old']['Other']) ? 0 : (($salesRegion['now']['Other'] ?? 0)/$salesRegion['old']['Other']-1)*100, 0, ',', '.') ?> %
    <tr>
        <th>Total
        <th><?= number_format(array_sum($salesRegion['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesRegion['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesRegion['now']) - array_sum($salesRegion['old']), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesRegion['old']) ? 0 : (array_sum($salesRegion['now'])/array_sum($salesRegion['old'])-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="region-chart" height="250"></canvas>
</div>

<div class="clear"></div>

<table>
    <caption>Sales By Day</caption>
    <thead>
    <tr>
        <th>Day
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
        <th>Last Acc.
        <th>Current Acc.
        <th>Acc. Diff
        <th>Acc. Diff %
    <tbody>
    <?php for($i = 1; $i <= $days; $i++) : ?>
    <tr<?= $i === $today ? ' class="bold"' : '';?>>
        <td><?= $i; ?>
        <td><?= !isset($salesLast[$i]) ? '' : number_format($salesLast[$i], 0, ',', '.'); ?>
        <td><?= !isset($sales[$i]) ? '' : number_format($sales[$i], 0, ',', '.'); ?>
        <td><?= number_format(($sales[$i] ?? 0) - ($salesLast[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesLast[$i] ?? 0) == 0 ? 0 : (($sales[$i] ?? 0)/($salesLast[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
        <td><?= !isset($salesAccLast[$i]) ? '' : number_format($salesAccLast[$i] ?? 0, 0, ',', '.'); ?>
        <td><?= !isset($salesAcc[$i]) ? '' : number_format($salesAcc[$i] ?? 0, 0, ',', '.'); ?>
        <td><?= number_format(($salesAcc[$i] ?? 0) - ($salesAccLast[$i] ?? 0), 0, ',', '.'); ?>
        <td><?= number_format(($salesAccLast[$i] ?? 0) == 0 ? 0 : (($salesAcc[$i] ?? 0)/($salesAccLast[$i] ?? 0) - 1) * 100, 2, ',', '.'); ?> %
    <?php endfor; ?>
    <tr>
        <th>Current
        <th><?= number_format($salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days] - $salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days] == 0 ? 0 : ($salesAcc[$days]/$salesAccLast[$today] - 1) * 100, 2, ',', '.'); ?> %
        <th><?= number_format($salesAccLast[count($salesAccLast)], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days], 0, ',', '.'); ?>
        <th><?= number_format($salesAcc[$days] - $salesAccLast[$today], 0, ',', '.'); ?>
        <th><?= number_format(($salesAcc[$days]/$salesAccLast[count($salesAccLast)] - 1) * 100, 2, ',', '.'); ?> %
</table>
<div class="clear"></div>
<script>
    let configExportDomestic = {
        type: 'bar',
        data: {
            labels: ["Export", "Domestic"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesExportDomestic['old']['Export'] ?? 0; ?>, <?= $salesExportDomestic['old']['Domestic'] ?? 0; ?>]
            }, {
                label: 'Current Month',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesExportDomestic['now']['Export'] ?? 0; ?>, <?= $salesExportDomestic['now']['Domestic'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Export/Domestic Sales"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                            let label = data.labels[tooltipItem.index];

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

    let configDevelopedUndeveloped = {
        type: 'bar',
        data: {
            labels: ["Undeveloped", "Developed"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesDevUndev['old']['Undeveloped'] ?? 0; ?>, <?= $salesDevUndev['old']['Developed'] ?? 0; ?>]
            }, {
                label: 'Current Month',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesDevUndev['now']['Undeveloped'] ?? 0; ?>, <?= $salesDevUndev['now']['Developed'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Developed/Undeveloped Sales"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                            let label = data.labels[tooltipItem.index];

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

    let configRegion = {
        type: 'bar',
        data: {
            labels: ["Other", "Oceania", "Africa", "Asia", "America", "Europe"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesRegion['old']['Other'] ?? 0; ?>, <?= $salesRegion['old']['Oceania'] ?? 0; ?>, <?= $salesRegion['old']['Africa'] ?? 0; ?>, <?= $salesRegion['old']['Asia'] ?? 0; ?>, <?= $salesRegion['old']['America'] ?? 0; ?>, <?= $salesRegion['old']['Europe'] ?? 0; ?>]
            }, {
                label: 'Current Month',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesRegion['now']['Other'] ?? 0; ?>, <?= $salesRegion['now']['Oceania'] ?? 0; ?>, <?= $salesRegion['now']['Africa'] ?? 0; ?>, <?= $salesRegion['now']['Asia'] ?? 0; ?>, <?= $salesRegion['now']['America'] ?? 0; ?>, <?= $salesRegion['now']['Europe'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Developed/Undeveloped Sales"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                            let label = data.labels[tooltipItem.index];

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
        let ctxExportDomestic = document.getElementById("domestic-export-chart");
        window.ExportDomestic = new Chart(ctxExportDomestic, configExportDomestic);

        let ctxDevelopedUndeveloped = document.getElementById("developed-undeveloped-chart");
        window.DevelopedUndeveloped = new Chart(ctxDevelopedUndeveloped, configDevelopedUndeveloped);

        let ctxRegion = document.getElementById("region-chart");
        window.salesRegion = new Chart(ctxRegion, configRegion);
    };
</script>
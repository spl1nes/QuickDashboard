<?php
$salesGroups = $this->getData('salesGroups');
$segmentGroups = $this->getData('segmentGroups');
$totalGroups = $this->getData('totalGroups');
?>
<h1>Sales Segmentation - <?= $this->getData('date')->format('Y/m'); ?> <?= $this->getData('type'); ?></h1>
<p class="info">The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: 100%; float: left;">
    <caption>Sales Segmentation</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesGroups['All'] as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $sales) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups['All'][$segment][$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups['All'][$segment][$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups['All'][$segment][$group]['now'] ?? 0)-($salesGroups['All'][$segment][$group]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups['All'][$segment][$group]['old']) || $salesGroups['All'][$segment][$group]['old'] == 0 ? 0 : (($salesGroups['All'][$segment][$group]['now'] ?? 0)/$salesGroups['All'][$segment][$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format($segmentGroups['All'][$segment]['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($segmentGroups['All'][$segment]['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($segmentGroups['All'][$segment]['now'] ?? 0)-($segmentGroups['All'][$segment]['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups['All'][$segment]['old']) || $segmentGroups['All'][$segment]['old'] == 0 ? 0 : (($segmentGroups['All'][$segment]['now'] ?? 0)/$segmentGroups['All'][$segment]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($totalGroups['All']['old'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['All']['now'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['All']['now']-$totalGroups['All']['old'], 0, ',', '.') ?>
        <th><?= number_format(!is_numeric($totalGroups['All']['old']) || $totalGroups['All']['old'] == 0 ? 0 : (($totalGroups['All']['now'] ?? 0)/$totalGroups['All']['old']-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 100%; float: left">
    <canvas id="group-sales" height="150"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<table style="width: 100%; float: left;">
    <caption>Sales Segmentation Domestic</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesGroups['Domestic'] as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $sales) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups['Domestic'][$segment][$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups['Domestic'][$segment][$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups['Domestic'][$segment][$group]['now'] ?? 0)-($salesGroups['Domestic'][$segment][$group]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups['Domestic'][$segment][$group]['old']) || $salesGroups['Domestic'][$segment][$group]['old'] == 0 ? 0 : (($salesGroups['Domestic'][$segment][$group]['now'] ?? 0)/$salesGroups['Domestic'][$segment][$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format($segmentGroups['Domestic'][$segment]['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($segmentGroups['Domestic'][$segment]['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($segmentGroups['Domestic'][$segment]['now'] ?? 0)-($segmentGroups['Domestic'][$segment]['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups['Domestic'][$segment]['old']) || $segmentGroups['Domestic'][$segment]['old'] == 0 ? 0 : (($segmentGroups['Domestic'][$segment]['now'] ?? 0)/$segmentGroups['Domestic'][$segment]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($totalGroups['Domestic']['old'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['Domestic']['now'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['Domestic']['now']-$totalGroups['Domestic']['old'], 0, ',', '.') ?>
        <th><?= number_format(!is_numeric($totalGroups['Domestic']['old']) || $totalGroups['Domestic']['old'] == 0 ? 0 : (($totalGroups['Domestic']['now'] ?? 0)/$totalGroups['Domestic']['old']-1)*100, 0, ',', '.') ?> %
</table>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="group-sales-domestic" height="150"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<table style="width: 100%; float: left;">
    <caption>Sales Segmentation Export</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesGroups['Export'] as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $sales) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups['Export'][$segment][$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups['Export'][$segment][$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups['Export'][$segment][$group]['now'] ?? 0)-($salesGroups['Export'][$segment][$group]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups['Export'][$segment][$group]['old']) || $salesGroups['Export'][$segment][$group]['old'] == 0 ? 0 : (($salesGroups['Export'][$segment][$group]['now'] ?? 0)/$salesGroups['Export'][$segment][$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format($segmentGroups['Export'][$segment]['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($segmentGroups['Export'][$segment]['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($segmentGroups['Export'][$segment]['now'] ?? 0)-($segmentGroups['Export'][$segment]['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups['Export'][$segment]['old']) || $segmentGroups['Export'][$segment]['old'] == 0 ? 0 : (($segmentGroups['Export'][$segment]['now'] ?? 0)/$segmentGroups['Export'][$segment]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($totalGroups['Export']['old'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['Export']['now'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['Export']['now']-$totalGroups['Export']['old'], 0, ',', '.') ?>
        <th><?= number_format(!is_numeric($totalGroups['Export']['old']) || $totalGroups['Export']['old'] == 0 ? 0 : (($totalGroups['Export']['now'] ?? 0)/$totalGroups['Export']['old']-1)*100, 0, ',', '.') ?> %
</table>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="group-sales-export" height="150"></canvas>
</div>

<div class="clear"></div>

<script>
    let configSalesGroups = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = []; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } $groupNames = array_merge($groupNames, array_keys($groups)); }; echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['old'] ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['now']  ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Sales by Groups"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
                    }
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    };

    let configSalesGroupsDomestic = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = []; foreach($salesGroups['Domestic'] as $key => $groups) { if(!is_array($groups)) { continue; } $groupNames = array_merge($groupNames, array_keys($groups)); }; echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['Domestic'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['old'] ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['Domestic'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['now']  ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Sales by Groups Domestic"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
                    }
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    };

    let configSalesGroupsExport = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = []; foreach($salesGroups['Export'] as $key => $groups) { if(!is_array($groups)) { continue; } $groupNames = array_merge($groupNames, array_keys($groups)); }; echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['Export'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['old'] ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['Export'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['now']  ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Sales by Groups Export"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
                    }
                }
            },
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    };

    window.onload = function() {
        let ctxSalesGroups = document.getElementById("group-sales");
        window.salesGroups = new Chart(ctxSalesGroups, configSalesGroups);

        let ctxSalesGroupsDomestic = document.getElementById("group-sales-domestic");
        window.salesGroupsDomestic = new Chart(ctxSalesGroupsDomestic, configSalesGroupsDomestic);

        let ctxSalesGroupsExport = document.getElementById("group-sales-export");
        window.salesGroupsExport = new Chart(ctxSalesGroupsExport, configSalesGroupsExport);
    };
</script>
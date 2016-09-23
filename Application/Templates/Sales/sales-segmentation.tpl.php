<?php
$salesGroups = $this->getData('salesGroups');
$segmentGroups = $this->getData('segmentGroups');
$totalGroups = $this->getData('totalGroups');
?>
<h1>Sales Segmentation</h1>
<p>The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

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
    <?php foreach($salesGroups as $segment => $groups) : if(!is_array($groups)) { continue; } foreach($groups as $group => $sales) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups[$segment][$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups[$segment][$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups[$segment][$group]['now'] ?? 0)-($salesGroups[$segment][$group]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups[$segment][$group]['old']) || !is_numeric($salesGroups[$segment][$group]['old']) ? 0 : (($salesGroups[$segment][$group]['now'] ?? 0)/$salesGroups[$segment][$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format($segmentGroups[$segment]['old'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format($segmentGroups[$segment]['now'] ?? 0, 0, ',', '.') ?>
        <th><?= number_format(($segmentGroups[$segment]['now'] ?? 0)-($segmentGroups[$segment]['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups[$segment]['old']) || $segmentGroups[$segment]['old'] == 0 ? 0 : (($segmentGroups[$segment]['now'] ?? 0)/$segmentGroups[$segment]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($totalGroups['old'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['now'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['now']-$totalGroups['old'], 0, ',', '.') ?>
        <th><?= number_format(!is_numeric($totalGroups['old']) || $totalGroups['old'] == 0 ? 0 : (($totalGroups['now'] ?? 0)/$totalGroups['old']-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 100%; float: left">
    <canvas id="group-sales" height="200"></canvas>
</div>

<div class="clear"></div>

<script>
    let configSalesGroups = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = []; foreach($salesGroups as $key => $groups) { if(!is_array($groups)) { continue; } $groupNames = array_merge($groupNames, array_keys($groups)); }; echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['old'] ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['now']  ?? 0) . ','; } } echo rtrim($data, ','); ?>]
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

                        return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.');
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); },
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
    };
</script>
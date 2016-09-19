<?php
$salesGroups = $this->getData('salesGroups');
$segmentGroups = $this->getData('segmentGroups');
$totalGroups = $this->getData('totalGroups');
?>
<h1>Sales Article</h1>
<p>The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: 50%; float: left;">
    <caption>Sales By Domestic/Export</caption>
    <thead>
    <tr>
        <th>Segment
        <th>Group
        <th>Last
        <th>Current
        <th>Diff
        <th>Diff %
    <tbody>
    <?php foreach($salesGroups as $segment => $groups) : foreach($groups as $group => $sales) : ?>
    <tr>
        <td><?= $segment; ?>
        <td><?= $group; ?>
        <td><?= number_format($salesGroups[$segment][$group]['old'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups[$segment][$group]['now'] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups[$segment][$group]['now'] ?? 0)-($salesGroups[$segment][$group]['old'] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups[$segment][$group]['old']) || $salesGroups[$segment][$group]['old'] == 0 ? 0 : (($salesGroups[$segment][$group]['now'] ?? 0)/$salesGroups[$segment][$group]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format(array_sum($segmentGroups[$segment]['old']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($segmentGroups[$segment]['now']), 0, ',', '.') ?>
        <th><?= number_format(array_sum($segmentGroups[$segment]['now'])-array_sum($segmentGroups[$segment]['old'] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($segmentGroups[$segment]['old']) || array_sum($segmentGroups[$segment]['old']) == 0 ? 0 : (array_sum($segmentGroups[$segment]['now'] ?? 0)/array_sum($segmentGroups[$segment]['old']-1))*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format($totalGroups['old'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['now'], 0, ',', '.') ?>
        <th><?= number_format($totalGroups['now']-$totalGroups['old'], 0, ',', '.') ?>
        <th><?= number_format(!isset($totalGroups['old']) ? 0 : ($totalGroups['now']/$totalGroups['old']-1)*100, 0, ',', '.') ?> %
</table>

<div class="box" style="width: 50%; float: left">
    <canvas id="segment-sales" height="100"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="group-sales" height="100"></canvas>
</div>

<script>
    let configSalesSegments = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    <?= array_sum($segmentGroups['Alloys']['old']); ?>,<?= array_sum($segmentGroups['Consumables']['old']); ?>,<?= array_sum($segmentGroups['Digitial']['old']); ?>,<?= array_sum($segmentGroups['Impla']['old']); ?>,<?= array_sum($segmentGroups['Misc']['old']); ?>,<?= array_sum($segmentGroups['MANI']['old']); ?>
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
                    <?= array_sum($segmentGroups['Alloys']['now']); ?>,<?= array_sum($segmentGroups['Consumables']['now']); ?>,<?= array_sum($segmentGroups['Digitial']['now']); ?>,<?= array_sum($segmentGroups['Impla']['now']); ?>,<?= array_sum($segmentGroups['Misc']['now']); ?>,<?= array_sum($segmentGroups['MANI']['now']); ?>
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
                "Alloys",
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

    let configSalesGroups = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = []; foreach($salesGroups as $key => $groups) { $groupNames[] = $groups; }; echo '"' . implode('","', array_keys($groupNames)) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups as $key => $groups) { foreach($groups as $group) { $data .= $group['now'] . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups as $key => $groups) { foreach($groups as $group) { $data .= $group['now'] . ','; } } echo rtrim($data, ','); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Sales by Group"
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
        let ctxSalesSegments = document.getElementById("segment-sales");
        window.salesSegments = new Chart(ctxSalesSegments, configSalesSegments);

        let ctxSalesGroups = document.getElementById("group-sales");
        window.salesGroups = new Chart(ctxSalesGroups, configSalesGroups);
    };
</script>
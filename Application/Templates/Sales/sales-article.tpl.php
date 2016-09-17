<?php
$salesGroups = $this->getData('salesGroups');
$salesGroupsExport = $this->getData('salesGroups');
$salesGroupsDomestic = $this->getData('salesGroups');
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
        <td><?= number_format($salesGroups['old'][$segment][$group] ?? 0, 0, ',', '.') ?>
        <td><?= number_format($salesGroups['now'][$segment][$group] ?? 0, 0, ',', '.') ?>
        <td><?= number_format(($salesGroups['now'][$segment][$group] ?? 0)-($salesGroups['old'][$segment][$group] ?? 0), 0, ',', '.') ?>
        <td><?= number_format(!isset($salesGroups['old'][$segment][$group]) || $salesGroups['old'][$segment][$group] == 0 ? 0 : (($salesGroups['now'][$segment][$group] ?? 0)/$salesGroups['old'][$segment][$group]-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th><?= $segment; ?>
        <th>Total
        <th><?= number_format(array_sum($salesGroups['old'][$segment]), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesGroups['now'][$segment]), 0, ',', '.') ?>
        <th><?= number_format(array_sum($salesGroups['now'][$segment])-array_sum($salesGroups['old'][$segment] ?? 0), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesGroups['old'][$segment]) || array_sum($salesGroups['old'][$segment]) == 0 ? 0 : (array_sum($salesGroups['now'][$segment] ?? 0)/array_sum($salesGroups['old'][$segment]-1))*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
    <tr>
        <th colspan="2">Total
        <th><?= number_format(\phpOMS\Utils\ArrayUtils::arraySumRecursive($salesGroups['old']), 0, ',', '.') ?>
        <th><?= number_format(\phpOMS\Utils\ArrayUtils::arraySumRecursive($salesGroups['now']), 0, ',', '.') ?>
        <th><?= number_format((\phpOMS\Utils\ArrayUtils::arraySumRecursive($salesGroups['now']))-(\phpOMS\Utils\ArrayUtils::arraySumRecursive($salesGroups['old'])), 0, ',', '.') ?>
        <th><?= number_format(!isset($salesGroups['old']) ? 0 : (\phpOMS\Utils\ArrayUtils::arraySumRecursive($salesGroups['now'])/\phpOMS\Utils\ArrayUtils::arraySumRecursive($salesGroups['old'])-1)*100, 0, ',', '.') ?> %
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
                    <?= array_sum($salesGroups['old']['Alloys']); ?>,<?= array_sum($salesGroups['old']['Consumables']); ?>,<?= array_sum($salesGroups['old']['Digitial']); ?>,<?= array_sum($salesGroups['old']['Impla']); ?>,<?= array_sum($salesGroups['old']['Misc']); ?>,<?= array_sum($salesGroups['old']['MANI']); ?>
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
                    <?= array_sum($salesGroups['now']['Alloys']); ?>,<?= array_sum($salesGroups['now']['Consumables']); ?>,<?= array_sum($salesGroups['now']['Digitial']); ?>,<?= array_sum($salesGroups['now']['Impla']); ?>,<?= array_sum($salesGroups['now']['Misc']); ?>,<?= array_sum($salesGroups['now']['MANI']); ?>
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
            labels: [<?php $groupNames = []; foreach($salesGroups['now'] as $key => $groups) { $groupNames[] = $groups; }; echo '"' . implode('","', array_keys($groupNames)) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['old'] as $key => $groups) { foreach($groups as $group) { $data .= $group . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['now'] as $key => $groups) { foreach($groups as $group) { $data .= $group . ','; } } echo rtrim($data, ','); ?>]
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
<?php
$opex = $this->getData('opex');
$opexAcc = $this->getData('opexAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');
$opexGroup = $this->getData('opexGroups');
?>
<h1>EBIT - <?= $this->getData('date')->format('Y/m'); ?></h1>
<p>Please be aware that the following EBIT figures are always unconsolidated. The EBIT doesn't include the interim profit resulting from different stock evaluations.</p>
<p class="info">The following table compares the values based on the last month. The currently ongoing month is not considered for easier comparison purpose.</p>
<table>
    <thead>
    <tr>
        <th>Type
        <th>2 Years Ago
        <th>Last Year
        <th>Currently
        <th>Diff Last Year
        <th>Diff Last Year %
    <tbody>
    <tr>
        <td>Isolated Month
        <td><?= '€  ' . number_format($opex[$current_2][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($opex[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($opex[$current][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($opex[$current][$currentMonth-1]-$opex[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= number_format(($opex[$current][$currentMonth-1]/$opex[$current_1][$currentMonth-1]-1)*100, 2, ',', '.') . '%';  ?>
    <tr>
        <td>Accumulated Year
        <td><?= '€  ' . number_format($opexAcc[$current_2][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($opexAcc[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($opexAcc[$current][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($opexAcc[$current][$currentMonth-1]-$opexAcc[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= number_format(($opexAcc[$current][$currentMonth-1]/$opexAcc[$current_1][$currentMonth-1]-1)*100, 2, ',', '.') . '%';  ?>
</table>
<p>The following chart shows the consolidated EBIT on a monthly basis for the last 3 years.</p>
<div style="width: 100%;">
    <canvas id="overview-consolidated-opex"></canvas>
</div>
<p>The following chart shows the accumlated EBIT on a monthly basis for the last 3 years.</p>
<div style="width: 100%;">
    <canvas id="overview-acc-consolidated-opex"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="opex-groups" height="130"></canvas>
</div>

<div class="clear"></div>
<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php echo implode(',', $opex[$current]); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php echo implode(',', $opex[$current_1]); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php echo implode(',', $opex[$current_2]); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Consolidated Isolated OPEX'
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
            hover: {
                mode: 'dataset'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'EBIT'
                    },
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }]
            }
        }
    };

    let configConsolidatedAcc = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php echo implode(',', $opexAcc[$current]); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php echo implode(',', $opexAcc[$current_1]); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php echo implode(',', $opexAcc[$current_2]); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Consolidated Accumulated OPEX'
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
            hover: {
                mode: 'dataset'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'EBIT'
                    },
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }]
            }
        }
    };

    let configOPEXGroups = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = array_unique(array_merge(array_keys($opexGroup[$current] ?? []), array_keys($opexGroup[$current_1] ?? []), array_keys($opexGroup[$current_2] ?? []))); echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $opexGroup[$current_2][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $opexGroup[$current_1][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $opexGroup[$current][$name] ?? 0; } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"OPEX by Departments"
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
        let ctxConsolidated = document.getElementById("overview-consolidated-opex").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-opex").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);

        let ctxGroupSales = document.getElementById("opex-groups").getContext("2d");
        window.groupSales = new Chart(ctxGroupSales, configOPEXGroups);
    };
</script>

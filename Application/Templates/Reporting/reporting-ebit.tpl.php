<?php
$ebit = $this->getData('ebit');
$ebitAcc = $this->getData('ebitAcc');
$ebitFC = $this->getData('ebitFC');
$ebitAccFC = $this->getData('ebitAccFC');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');
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
        <td><?= '€  ' . number_format($ebit[$current_2][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($ebit[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($ebit[$current][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($ebit[$current][$currentMonth-1]-$ebit[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= number_format(($ebit[$current][$currentMonth-1]/$ebit[$current_1][$currentMonth-1]-1)*100, 2, ',', '.') . '%';  ?>
    <tr>
        <td>Accumulated Year
        <td><?= '€  ' . number_format($ebitAcc[$current_2][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($ebitAcc[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($ebitAcc[$current][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($ebitAcc[$current][$currentMonth-1]-$ebitAcc[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= number_format(($ebitAcc[$current][$currentMonth-1]/$ebitAcc[$current_1][$currentMonth-1]-1)*100, 2, ',', '.') . '%';  ?>
</table>
<p>The following chart shows the consolidated EBIT on a monthly basis for the last 3 years.</p>
<div style="width: 100%;">
    <canvas id="overview-consolidated-ebit"></canvas>
</div>
<p>The following chart shows the accumlated EBIT on a monthly basis for the last 3 years.</p>
<div style="width: 100%;">
    <canvas id="overview-acc-consolidated-ebit"></canvas>
</div>
<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Forecast",
                data: [<?php echo str_repeat(',', ($currentMonth-2)) , implode(',', $ebitFC); ?>],
                hidden: true,
                fill: false,
                borderDash: [5, 5],
                borderColor: 'rgba(0,0,0,1)',
                backgroundColor: 'rgba(0,0,0,1)',
                pointBorderColor: 'rgba(0,0,0,1)',
                pointBackgroundColor: 'rgba(0,0,0,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Current Year",
                data: [<?php echo implode(',', $ebit[$current]); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Last Year",
                data: [<?php echo implode(',', $ebit[$current_1]); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Two Years Ago",
                data: [<?php echo implode(',', $ebit[$current_2]); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Consolidated Isolated EBIT'
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
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
                label: "Forecast",
                data: [<?php echo str_repeat(',', ($currentMonth-2)) , implode(',', $ebitAccFC); ?>],
                hidden: true,
                fill: false,
                borderDash: [5, 5],
                borderColor: 'rgba(0,0,0,1)',
                backgroundColor: 'rgba(0,0,0,1)',
                pointBorderColor: 'rgba(0,0,0,1)',
                pointBackgroundColor: 'rgba(0,0,0,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Current Year",
                data: [<?php echo implode(',', $ebitAcc[$current]); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Last Year",
                data: [<?php echo implode(',', $ebitAcc[$current_1]); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Two Years Ago",
                data: [<?php echo implode(',', $ebitAcc[$current_2]); ?>],
                fill: false,
                borderColor: 'rgba(255, 206, 86, 1)',
                backgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderColor: 'rgba(255, 206, 86, 1)',
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Consolidated Accumulated EBIT'
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                            let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';
                            let label = data.labels[tooltipItem.index];

                            return ' ' + datasetLabel + ': ' + '€ ' + Math.round(tooltipItem.yLabel).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-');
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
                    }
                }]
            }
        }
    };

    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-ebit").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-ebit").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);
    };
</script>

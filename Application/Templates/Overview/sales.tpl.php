<?php
$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');
?>
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
        <td><?= '€  ' . number_format($sales[$current_2][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current][$currentMonth-1]-$sales[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= number_format(($sales[$current][$currentMonth-1]/$sales[$current_1][$currentMonth-1]-1)*100, 2, ',', '.') . '%';  ?>
    <tr>
        <td>Accumulated Year
        <td><?= '€  ' . number_format($salesAcc[$current_2][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current][$currentMonth-1]-$salesAcc[$current_1][$currentMonth-1], 0, ',', '.');  ?>
        <td><?= number_format(($salesAcc[$current][$currentMonth-1]/$salesAcc[$current_1][$currentMonth-1]-1)*100, 2, ',', '.') . '%';  ?>
</table>
<p>The following chart shows the consolidated sales on a monthly basis for the last 3 years.</p>
<div style="width: 100%;">
    <canvas id="overview-consolidated-sales"></canvas>
</div>
<p>The following chart shows the accumlated sales on a monthly basis for the last 3 years.</p>
<div style="width: 100%;">
    <canvas id="overview-acc-consolidated-sales"></canvas>
</div>
<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php echo implode(',', $sales[$current]); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php echo implode(',', $sales[$current_1]); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php echo implode(',', $sales[$current_2]); ?>],
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
                text:'Consolidated Isolated Sales'
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
                        labelString: 'Sales'
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
                data: [<?php echo implode(',', $salesAcc[$current]); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php echo implode(',', $salesAcc[$current_1]); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php echo implode(',', $salesAcc[$current_2]); ?>],
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
                text:'Consolidated Accumulated Sales'
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
                        labelString: 'Sales'
                    },
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.'); }
                    }
                }]
            }
        }
    };
</script>
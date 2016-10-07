<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/segmentation?{?}'); ?>">
    <table>
        <tr>
            <td><label for="segment">Segment:</label>
            <td><select id="segment" name="segment"><?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::NAMING as $id => $name) : ?>
                <option value="<?= $id; ?>"<?= ((int) $this->request->getData('segment')) == (int) $id ? ' selected' : ''; ?>><?= $id; ?> - <?= $name; ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
<?php if(($this->request->getData('segment') ?? '') != '') : ?>
<?php
$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');
?>
<h1>Segmentation Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>
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
        <td><?= '€  ' . number_format($sales[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format(($sales[$current][$currentMonth] ?? 0) - ($sales[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <td><?= !isset($sales[$current_1][$currentMonth]) || $sales[$current_1][$currentMonth] == 0 ? 0 : number_format((($sales[$current][$currentMonth] ?? 0)/$sales[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
    <tr>
        <td>Accumulated Year
        <td><?= '€  ' . number_format($salesAcc[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format(($salesAcc[$current][$currentMonth] ?? 0) - ($salesAcc[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <td><?= !isset($salesAcc[$current_1][$currentMonth]) || $salesAcc[$current_1][$currentMonth] == 0? 0 : number_format((($salesAcc[$current][$currentMonth] ?? 0)/$salesAcc[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
</table>

<div style="width: 50%; float: left;">
    <canvas id="overview-consolidated-sales" height="270"></canvas>
</div>

<div style="width: 50%; float: left;">
    <canvas id="overview-acc-consolidated-sales" height="270"></canvas>
</div>

<div class="clear"></div>

<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
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
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
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

    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-sales").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-sales").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);
    };
</script>
<?php endif; ?>
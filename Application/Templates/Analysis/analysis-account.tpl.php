<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/account?{?}'); ?>">
    <table>
        <tr>
            <td><label for="account">Account:</label>
            <td><input id="account" name="account"></input>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
<?php if(($this->request->getData('account') ?? '') != '') : ?>
<?php
$sales = $opex = $this->getData('opex');
$salesAcc = $opexAcc = $this->getData('opexAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');
$opexGroup = $this->getData('opexGroups');
?>
<?php if(!empty($opexAcc)) : ?>
<h1><?= $this->request->getData('account') ?? '' ?> Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>
<?php include __DIR__ . '/../Sales/table-overview.tpl.php'; ?>

<div style="width: 50%; float: left;">
    <canvas id="overview-consolidated-opex" height="270"></canvas>
</div>

<div style="width: 50%; float: left;">
    <canvas id="overview-acc-consolidated-opex" height="270"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="opex-groups" height="130"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<table>
    <thead>
    <tr>
        <th>Department
        <th>2 Years Ago
        <th>Last Year
        <th>Currently
        <th>Diff Last Year
        <th>Diff Last Year %
    <tbody>
    <?php $groupNames = array_unique(array_merge(array_keys($opexGroup[$current] ?? []), array_keys($opexGroup[$current_1] ?? []), array_keys($opexGroup[$current_2] ?? []))); asort($groupNames); foreach($groupNames as $name) : ?>
        <tr>
            <td><?= $name; ?>
            <td><?= '€  ' . number_format($opexGroup[$current_2][$name] ?? 0, 0, ',', '.');  ?>
            <td><?= '€  ' . number_format($opexGroup[$current_1][$name] ?? 0, 0, ',', '.');  ?>
            <td><?= '€  ' . number_format($opexGroup[$current][$name] ?? 0, 0, ',', '.');  ?>
            <td><?= '€  ' . number_format(($opexGroup[$current][$name] ?? 0)-($opexGroup[$current_1][$name] ?? 0), 0, ',', '.');  ?>
            <td><?= ($opexGroup[$current_1][$name] ?? 0) == 0 ? 0 : number_format((($opexGroup[$current][$name] ?? 0)/$opexGroup[$current_1][$name]-1)*100, 2, ',', '.') . '%';  ?>
    <?php endforeach; ?>
    <tr>
        <th>Accumulated Year
        <th><?= '€  ' . number_format($opexAcc[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <th><?= '€  ' . number_format($opexAcc[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <th><?= '€  ' . number_format($opexAcc[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <th><?= '€  ' . number_format(($opexAcc[$current][$currentMonth]?? 0) - ($opexAcc[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <th><?= number_format(($opexAcc[$current_1][$currentMonth] ?? 0) === 0 ? 0 : (($opexAcc[$current][$currentMonth] ?? 0)/$opexAcc[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
</table>

<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $opex[$current][$i] ?? '0'; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $opex[$current_1][$i] ?? '0'; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $opex[$current_2][$i] ?? '0'; } echo implode(',', $data ?? []); ?>],
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
                text:'Consolidated Isolated Sales'
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
                        labelString: 'Sales'
                    },
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        reverse: true
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
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $opexAcc[$current][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $opexAcc[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0,
                cubicInterpolationMode: 'monotone'
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $opexAcc[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
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
                text:'Consolidated Accumulated Sales'
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
                        labelString: 'Sales'
                    },
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        reverse: true
                    }
                }]
            }
        }
    };

    let configOPEXGroups = {
        type: 'bar',
        data: {
            labels: [<?php echo '"' . implode('","', $groupNames) . '"'; ?>],
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
                        reverse: true
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
<?php endif; endif; ?>
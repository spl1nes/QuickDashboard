<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/department?{?}'); ?>">
    <table>
        <tr>
            <td><label for="department">Department:</label>
            <td><select id="department" name="department">
                <?php $dep = \QuickDashboard\Application\Models\StructureDefinitions::DEPARTMENTS_SD; foreach($dep as $name => $costcenter) : ?>
                    <option value="<?= $name; ?>"<?= $this->request->getData('department') == $name ? ' selected' : ''; ?>><?= $name; ?>
                <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
<?php if(($this->request->getData('department') ?? '') != '') : ?>
<?php
$sales = $department = $this->getData('department');
$salesAcc = $departmentAcc = $this->getData('departmentAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');
$departmentGroup = $this->getData('departmentGroups');
?>
<?php if(!empty($departmentAcc)) : ?>
    <h1><?= $this->request->getData('department') ?? '' ?> Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>
<?php include __DIR__ . '/../Sales/table-overview.tpl.php'; ?>

<div style="width: 50%; float: left;">
    <canvas id="overview-consolidated-department" height="270"></canvas>
</div>

<div style="width: 50%; float: left;">
    <canvas id="overview-acc-consolidated-department" height="270"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<table>
    <thead>
    <tr>
        <th>Name
        <th>2 Years Ago
        <th>Last Year
        <th>Currently
        <th>Diff Last Year
        <th>Diff Last Year %
    <tbody>
    <?php $groupNames = array_unique(array_merge(array_keys($departmentGroup[$current] ?? []), array_keys($departmentGroup[$current_1] ?? []), array_keys($departmentGroup[$current_2] ?? []))); asort($groupNames); foreach($groupNames as $name) : ?>
    <tr>
        <td><?= $name; ?>
        <td><?= '€  ' . number_format($departmentGroup[$current_2][$name] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($departmentGroup[$current_1][$name] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($departmentGroup[$current][$name] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format(($departmentGroup[$current][$name] ?? 0) - ($departmentGroup[$current_1][$name] ?? 0), 0, ',', '.');  ?>
        <td><?= !isset($departmentGroup[$current_1][$name]) || $departmentGroup[$current_1][$name] == 0 ? 0 : number_format((($departmentGroup[$current][$name] ?? 0)/$departmentGroup[$current_1][$name]-1)*100, 2, ',', '.') . '%';  ?>
    <?php endforeach; ?>
    <tr>
        <th>Accumulated Year
        <th><?= '€  ' . number_format($departmentAcc[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <th><?= '€  ' . number_format($departmentAcc[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <th><?= '€  ' . number_format($departmentAcc[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <th><?= '€  ' . number_format(($departmentAcc[$current][$currentMonth] ?? 0) - ($departmentAcc[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <th><?= !isset($departmentAcc[$current_1][$currentMonth]) || $departmentAcc[$current_1][$currentMonth] == 0? 0 : number_format((($departmentAcc[$current][$currentMonth] ?? 0)/$departmentAcc[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
</table>

<div class="clear"></div>

<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $department[$current][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $department[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $department[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
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
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $departmentAcc[$current][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $departmentAcc[$current_1][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $departmentAcc[$current_2][$i] ?? ''; } echo implode(',', $data ?? []); ?>],
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

    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-department").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-department").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);
    };
</script>
<?php endif; endif; ?>
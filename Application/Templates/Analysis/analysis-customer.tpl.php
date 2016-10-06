<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/customer?{?}'); ?>">
    <table>
        <tr>
            <td><label for="customer">Customer ID:</label>
            <td><input id="customer" name="customer" type="text" value="<?= $this->request->getData('customer') ?? ''; ?>" blaceholder="123456" pattern="[0-9]{6}">
            <td><label for="cu">Unit:</label>
            <td><select id="cu" name="cu">
                <option value='sd'<?= $this->request->getData('cu') !== 'gdf' ? ' selected' : ''; ?>>SD
                <option value='gdf'<?= $this->request->getData('cu') === 'gdf' ? ' selected' : ''; ?>>GDF</select>
            <td style="width: 100%">
        <tr>
            <td colspan="5"><input type="submit" value="Analyse">
    </table>
</form>

<?php if($this->getData('customer') != null) : ?>
<?php
$customer = $this->getData('customer');
$location = $customer->getLocation();

$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');

$salesGroup = $this->getData('salesGroups');
$salesGroupTotal = $this->getData('salesGroupsTotal');
?>
<h1>Customer Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>

<p class="info">The following tables and chart contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis.</p>

<table style="width: calc(50% - 5px); float: left;">
    <caption>Customer - <?= $customer->getName(); ?></caption>
    <thead>
    <tr>
        <th>Type
        <th>Value
    <tbody>
        <tr><th>ID:<td><?= $customer->getId(); ?>
        <tr><th>Name:<td><?= $customer->getName(); ?>
        <tr><th>Address:<td><?= $location->getAddress(); ?>
        <tr><th>Postal:<td><?= $location->getPostal(); ?> <?= $location->getCity() ?>
        <tr><th>Country:<td><?= $location->getCountry(); ?>
        <tr><th>Group:<td><?= $customer->getCustomerGroup(); ?>
        <tr><th>Sales Rep:<td><?= $customer->getSalesRep(); ?>
        <tr><th>Registered:<td><?= $customer->getCreatedAt()->format('Y-m-d'); ?>
</table>

<table style="width: calc(50% - 5px); float: right;">
    <caption>Stats</caption>
    <thead>
    <tr>
        <th>Type
        <th>Last Year
        <th>Current
        <th>Diff
    <tbody>
        <tr><th>DSO:<td><?= 1; ?><td><?= 1; ?><td><?= 1; ?>
        <tr><th>Orders:<td><?= 1; ?><td><?= 1; ?><td><?= 1; ?>
        <tr><th>Avg. Order:<td><?= 1; ?><td><?= 1; ?><td><?= 1; ?>
        <tr><th>Sales:<td><?= 1; ?><td><?= 1; ?><td><?= 1; ?>
        <tr><th>Total Sales:<td><?= 1; ?><td><?= 1; ?><td><?= 1; ?>
</table>

<div class="clear"></div>

<?php if(isset($sales[$current]) || isset($sales[$current_1]) || isset($sales[$current_2])) : ?>
<div class="box" style="width: 50%; float: left">
    <canvas id="sales-month" height="270"></canvas>
</div>

<div class="box" style="width: 50%; float: left">
    <canvas id="sales-month-acc" height="270"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="sales-groups" height="130"></canvas>
</div>

<div class="clear"></div>

<div class="box" style="width: 100%; float: left">
    <canvas id="sales-groups-total" height="130"></canvas>
</div>

<div class="clear"></div>

<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_1][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_2][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
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
                text:'Isolated Sales'
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
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_1][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $salesAcc[$current_2][$i] ?? 0; } echo implode(',', $data ?? []); ?>],
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
                text:'Accumulated Sales'
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

    let configSalesGroups = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = array_unique(array_merge(array_keys($salesGroup[$current] ?? []), array_keys($salesGroup[$current_1] ?? []), array_keys($salesGroup[$current_2] ?? []))); echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroup[$current_2][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroup[$current_1][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroup[$current][$name] ?? 0; } echo implode(',', $data); ?>]
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

    let configSalesGroupsTotal = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = array_unique(array_merge(array_keys($salesGroupTotal[$current] ?? []), array_keys($salesGroupTotal[$current_1] ?? []), array_keys($salesGroupTotal[$current_2] ?? []))); echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroupTotal[$current_2][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroupTotal[$current_1][$name] ?? 0; } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($groupNames as $key => $name) { $data[] = $salesGroupTotal[$current][$name] ?? 0; } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Total Sales by Groups"
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
        let ctxConsolidated = document.getElementById("sales-month").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("sales-month-acc").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);

        let ctxGroupSales = document.getElementById("sales-groups").getContext("2d");
        window.groupSales = new Chart(ctxGroupSales, configSalesGroups);

        let ctxGroupSalesTotal = document.getElementById("sales-groups-total").getContext("2d");
        window.groupSalesTotal = new Chart(ctxGroupSalesTotal, configSalesGroupsTotal);
    };
</script>
<?php endif; endif; ?>
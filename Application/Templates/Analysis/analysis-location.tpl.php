<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/location?{?}'); ?>">
    <table>
        <tr>
            <td><label for="location">Location:</label>
            <td><select id="location" name="location">
                <optgroup label="Domestic/Export">
                    <option value="Domestic"<?= $this->request->getData('location') == 'Domestic' ? ' selected' : ''; ?>>Domestic
                    <option value="Export"<?= $this->request->getData('location') == 'Export' ? ' selected' : ''; ?>>Export
                <optgroup label="Country">
            <?php $countries = \QuickDashboard\Application\Models\StructureDefinitions::getCountries(); asort($countries); foreach($countries as $id => $name) : ?>
                <option value="<?= $name; ?>"<?= $this->request->getData('location') == $name ? ' selected' : ''; ?>><?= empty($fullName = \phpOMS\Localization\ISO3166TwoEnum::getName($name)) ? $name : \phpOMS\Localization\ISO3166NameEnum::getByName(\phpOMS\Localization\ISO3166TwoEnum::getName($name)); ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
<?php if(($this->request->getData('location') ?? '') != '') : ?>
<?php
$sales = $this->getData('sales');
$salesAcc = $this->getData('salesAcc');
$current = $this->getData('currentFiscalYear');
$current_1 = $this->getData('currentFiscalYear')-1;
$current_2 = $this->getData('currentFiscalYear')-2;
$currentMonth = $this->getData('currentMonth');
$salesGroups = $this->getData('salesGroups');
$segmentGroups = $this->getData('segmentGroups');
$totalGroups = $this->getData('totalGroups');
$topCustomers = $this->getData('customer');
$customerCount = $this->getData('customerCount');
$gini = $this->getData('gini');
?>
<?php if(!empty($salesAcc)) : ?>
<h1><?= $this->request->getData('location') ?? '' ?> Analysis - <?= $this->getData('date')->format('Y/m'); ?></h1>
<h2>Sales</h2>
<?php include __DIR__ . '/../Sales/table-overview.tpl.php'; ?>

<div style="width: 50%; float: left;">
    <canvas id="overview-consolidated-sales" height="270"></canvas>
</div>

<div style="width: 50%; float: left;">
    <canvas id="overview-acc-consolidated-sales" height="270"></canvas>
</div>

<div class="box" style="width: 100%; float: left">
    <canvas id="group-sales" height="150"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<?php include __DIR__ . '/../Sales/table-segment.tpl.php'; ?>

<div class="clear"></div>
<div class="break"></div>

<h2>Customers</h2>

<p>The customer sales distribution last year had a Gini-Coefficient of <?= number_format($gini['old'] ?? 0, 4, ',', '.'); ?> and this year of <?= number_format($gini['now'] ?? 0, 4, ',', '.'); ?>.</p>

<blockquote><i class="fa fa-2x fa-quote-left"></i>The Gini-Coefficient ranges from 0 to 1 and represents the distribution of sales, income etc. as one figure. 0 means that all customers have the same amount of sales where 1 means that all sales are generated by one customer. In this case only active customers are considered for the evaluation. The income distribution in Germany for example had a Gini-Coefficient of 0,283 in 2012.</blockquote>

<p>The following two charts show the top customers sorted in descending order based on the current sales (left chart) and the top customers sorted in descending order based on the last year sales (right chart).</p>

<div class="box" style="width: 50%; float: left">
    <canvas id="top-customers-sales" height="270"></canvas>
</div>

<div class="box" style="width: 50%; float: left">
    <canvas id="top-customers-sales-old" height="270"></canvas>
</div>

<div class="clear"></div>

<p>The follwoing chart shows the amount of active customers (have sales impact) per month.</p>

<div class="box" style="width: 100%; float: left">
    <canvas id="customers-count" height="100"></canvas>
</div>

<div class="clear"></div>

<script>
    let configConsolidated = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = ($i <= $currentMonth) ? $sales[$current][$i] ?? '0' : ''; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_1][$i] ?? '0'; } echo implode(',', $data ?? []); ?>],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Two Years Ago",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $sales[$current_2][$i] ?? '0'; } echo implode(',', $data ?? []); ?>],
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
                    }
                }]
            }
        }
    };

    let configSalesGroups = {
        type: 'bar',
        data: {
            labels: [<?php $groupNames = []; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } $groupNames = array_merge($groupNames, array_keys($groups)); }; echo '"' . implode('","', $groupNames) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['old'] ?? 0) . ','; } } echo rtrim($data, ','); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = ''; foreach($salesGroups['All'] as $key => $groups) { if(!is_array($groups)) { continue; } foreach($groups as $group) { $data .= ($group['now']  ?? 0) . ','; } } echo rtrim($data, ','); ?>]
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
                        min: 0
                    }
                }],
            }
        }
    };

    let configTopCustomers = {
        type: 'bar',
        data: {
            labels: [<?= '"' . implode('","', array_keys($top = array_slice($topCustomers['now'] ?? [], 0, 15, true))) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['old'][$key]) || !is_numeric($topCustomers['old'][$key]) ? 0 : $topCustomers['old'][$key]); } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['now'][$key]) || !is_numeric($topCustomers['now'][$key]) ? 0 : $topCustomers['now'][$key]); } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Sales by Customers - Current Year"
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
                    }
                }],
            }
        }
    };

    let configTopCustomersOld = {
        type: 'bar',
        data: {
            labels: [<?= '"' . implode('","', array_keys($top = array_slice($topCustomers['old'] ?? [], 0, 15, true))) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['old'][$key]) || !is_numeric($topCustomers['old'][$key]) ? 0 : $topCustomers['old'][$key]); } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = (!isset($topCustomers['now'][$key]) || !is_numeric($topCustomers['now'][$key]) ? 0 : $topCustomers['now'][$key]); } echo implode(',', $data); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Sales by Customers - Last Year"
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
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); }
                    }
                }],
            }
        }
    };


    let configCustomerCount = {
        type: 'bar',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: 'Two Years Ago',
                backgroundColor: "rgba(255, 206, 86, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $customerCount[$current_2][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }, {
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $customerCount[$current_1][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; for($i = 1; $i < 13; $i++) { $data[$i] = $customerCount[$current][$i] ?? 0; } echo implode(',', $data ?? []); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Customers per Month"
            },
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        let datasetLabel = data.datasets[tooltipItem.datasetIndex].label || 'Other';

                        return ' ' + datasetLabel + ': ' + Math.round(tooltipItem.yLabel).toString();
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
                        userCallback: function(value, index, values) { return Math.floor(value).toString(); },
                        beginAtZero: true,
                        min: 0
                    }
                }],
            }
        }
    };

    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-sales").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-sales").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);

        let ctxSalesGroups = document.getElementById("group-sales");
        window.salesGroups = new Chart(ctxSalesGroups, configSalesGroups);

        let ctxSalesCustomers = document.getElementById("top-customers-sales");
        window.salesCustomers = new Chart(ctxSalesCustomers, configTopCustomers);

        let ctxSalesCustomersOld = document.getElementById("top-customers-sales-old");
        window.salesCustomersOld = new Chart(ctxSalesCustomersOld, configTopCustomersOld);

        let ctxSalesCustomersCount = document.getElementById("customers-count");
        window.salesCustomersCount = new Chart(ctxSalesCustomersCount, configCustomerCount);
    };
</script>
<?php endif; endif; ?>
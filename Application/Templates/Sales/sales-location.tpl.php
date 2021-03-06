<?php
$salesExportDomestic = $this->getData('salesExportDomestic');
$salesDevUndev = $this->getData('salesDevUndev');
$salesRegion = $this->getData('salesRegion');
$salesCountry = $this->getData('salesCountry');
?>
<h1>Sales Location - <?= $this->getData('date')->format('Y/m'); ?> <?= $this->getData('type'); ?></h1>
<p class="info">The following tables contain the sales of the current month compared to the same month of the last year. Please be aware that these figures represent the full month and not a comparison on a daily basis. The calculation of developed and undeveloped countires is based on the MANI definition. The region calculation is mostly based on the ISO-3166 definition.</p>

<?php include __DIR__ . '/../Sales/table-domesticexport.tpl.php'; ?>

<div class="box" style="width: 50%; float: left">
    <canvas id="domestic-export-chart" height="110"></canvas>
</div>

<div class="clear"></div>

<?php include __DIR__ . '/../Sales/table-devundev.tpl.php'; ?>

<div class="box" style="width: 50%; float: left">
    <canvas id="developed-undeveloped-chart" height="110"></canvas>
</div>

<div class="clear"></div>

<?php include __DIR__ . '/../Sales/table-region.tpl.php'; ?>

<div class="box" style="width: 50%; float: left">
    <canvas id="region-chart" height="200"></canvas>
</div>

<div class="clear"></div>
<div class="break"></div>

<p>The following world map shows the sales by country as well as the sales by region. A total of <?= count($salesCountry['now']); ?> countries have been delivered where last year <?= count($salesCountry['old']); ?> countries have been delivered.</p>

<div class="box" id="world-map-country" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>
<div class="box" id="world-map-region" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>

<div class="clear"></div>

<p>The following chart shows the current top countries as well as the sales of these countries compared to last year.</p>

<div class="box" style="width: 100%">
    <canvas id="top-countries-chart" height="100"></canvas>
</div>
<script>
    let configExportDomestic = {
        type: 'bar',
        data: {
            labels: ["Export", "Domestic"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesExportDomestic['old']['Export'] ?? 0; ?>, <?= $salesExportDomestic['old']['Domestic'] ?? 0; ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesExportDomestic['now']['Export'] ?? 0; ?>, <?= $salesExportDomestic['now']['Domestic'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Export/Domestic Sales"
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
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        beginAtZero: true
                    }
                }],
            }
        }
    };

    let configDevelopedUndeveloped = {
        type: 'bar',
        data: {
            labels: ["Undeveloped", "Developed"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesDevUndev['old']['Undeveloped'] ?? 0; ?>, <?= $salesDevUndev['old']['Developed'] ?? 0; ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesDevUndev['now']['Undeveloped'] ?? 0; ?>, <?= $salesDevUndev['now']['Developed'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Export/Domestic Sales"
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
                yAxes: [{
                    type: "linear",
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                    ticks: {
                        userCallback: function(value, index, values) { return '€ ' + value.toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); },
                        beginAtZero: true
                    }
                }],
            }
        }
    };

    let configRegion = {
        type: 'bar',
        data: {
            labels: ["Other", "Oceania", "Africa", "Asia", "America", "Europe"],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesRegion['old']['Other'] ?? 0; ?>, <?= $salesRegion['old']['Oceania'] ?? 0; ?>, <?= $salesRegion['old']['Africa'] ?? 0; ?>, <?= $salesRegion['old']['Asia'] ?? 0; ?>, <?= $salesRegion['old']['America'] ?? 0; ?>, <?= $salesRegion['old']['Europe'] ?? 0; ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= $salesRegion['now']['Other'] ?? 0; ?>, <?= $salesRegion['now']['Oceania'] ?? 0; ?>, <?= $salesRegion['now']['Africa'] ?? 0; ?>, <?= $salesRegion['now']['Asia'] ?? 0; ?>, <?= $salesRegion['now']['America'] ?? 0; ?>, <?= $salesRegion['now']['Europe'] ?? 0; ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:false,
                text:"Export/Domestic Sales"
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

    let worldMapCountry = new Datamap({
        scope: 'world',
        element: document.getElementById('world-map-country'),
        projection: 'mercator',
        height: 300,
        fills: {
            defaultFill: '#cccccc',
            gt50: 'red'
        },
        data: {
            <?php foreach($salesCountry['now'] as $key => $value) : if(is_string($key) && !empty($key)) : ?>
                <?= $key; ?>: {fillKey: 'gt50', value: <?= $value; ?>, country: '<?= $key; ?>'},
            <?php endif; endforeach; ?>
        },
        geographyConfig: {
            popupTemplate: function(geo, data) {
                return "<div class='hoverinfo'>Sales " + data.country + ": € " + Math.round(data.value).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); + "</div>";
            }
        }
    });

    let worldMapRegion = new Datamap({
        scope: 'world',
        element: document.getElementById('world-map-region'),
        projection: 'mercator',
        height: 300,
        fills: {
            defaultFill: '#cccccc',
            gt50: 'red'
        },
        data: {}
    });

    worldMapRegion.bubbles([
        {name: 'Europe', latitude: 51.1657, longitude: 10.4515, radius: <?= max(log($salesRegion['now']['Europe'] ?? 1), 0); ?>, fillKey: 'gt50'},
        {name: 'Asia', latitude: 53.4815, longitude: 88.7695, radius: <?= max(log($salesRegion['now']['Asia'] ?? 1), 0); ?>, fillKey: 'gt50'},
        {name: 'America', latitude: 12.8010, longitude: -87.3632, radius: <?= max(log($salesRegion['now']['America'] ?? 1), 0); ?>, fillKey: 'gt50'},
        {name: 'Africa', latitude: 13.8274, longitude: 15.6445, radius: <?= max(log($salesRegion['now']['Africa'] ?? 1), 0); ?>, fillKey: 'gt50'},
        {name: 'Oceania', latitude: -25.2744, longitude: 133.7751, radius: <?= max(log($salesRegion['now']['Oceania'] ?? 1), 0); ?>, fillKey: 'gt50'},
    ], {
        popupTemplate: function(geo, data) {
            return "<div class='hoverinfo'>Sales " + data.name + ": € " + Math.round(Math.exp(data.radius)).toString().split(/(?=(?:...)*$)/).join('.').replace('-.', '-'); + "</div>";
        }
    });

    let configTopCountries = {
        type: 'bar',
        data: {
            labels: [<?= '"' . implode('","', array_keys($top = array_slice($salesCountry['now'], 0, 15, true))) . '"'; ?>],
            datasets: [{
                label: 'Last Year',
                backgroundColor: "rgba(54, 162, 235, 1)",
                yAxisID: "y-axis-1",
                data: [<?php $data = []; foreach($top as $key => $value) { $data[] = ($salesCountry['old'][$key] ?? 0); } echo implode(',', $data); ?>]
            }, {
                label: 'Current',
                backgroundColor: "rgba(255,99,132,1)",
                yAxisID: "y-axis-1",
                data: [<?= implode(',', $top); ?>]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Sales by Countries"
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

    window.onload = function() {
        let ctxExportDomestic = document.getElementById("domestic-export-chart");
        window.ExportDomestic = new Chart(ctxExportDomestic, configExportDomestic);

        let ctxDevelopedUndeveloped = document.getElementById("developed-undeveloped-chart");
        window.DevelopedUndeveloped = new Chart(ctxDevelopedUndeveloped, configDevelopedUndeveloped);

        let ctxRegion = document.getElementById("region-chart");
        window.salesRegion = new Chart(ctxRegion, configRegion);

        let ctxTopCountries = document.getElementById("top-countries-chart");
        window.salesTopCountries = new Chart(ctxTopCountries, configTopCountries);
    };
</script>
<div class="box" id="canvas-holder-1" style="width: 50%; float: left">
    <canvas id="sales-group-dist" height="200"></canvas>
</div>

<div class="box" id="canvas-holder-2" style="width: 50%; float: left">
    <canvas
        id="sales-history" height="200"></canvas>
</div>
<div class="box" id="world-map-country" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>
<div class="box" id="world-map-region" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>

<div class="box" id="canvas-holder-3" style="width: 50%; float: left">
    <canvas id="export-domestic-sales" height="200"></canvas>
</div>

<div class="box" id="canvas-holder-4" style="width: 50%; float: left">
    <canvas id="developed-undeveloped-sales" height="200"></canvas>
</div>
<div class="clear"></div>
<script>
    let randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    let configSalesGroupDist = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),
                ],
                backgroundColor: [
                    "#F7464A",
                    "#46BFBD",
                    "#FDB45C",
                    "#949FB1",
                    "#4D5360",
                ],
                label: 'Current year'
            }, {
                data: [
                    randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),
                ],
                backgroundColor: [
                    "#F7464A",
                    "#46BFBD",
                    "#FDB45C",
                    "#949FB1",
                    "#4D5360",
                ],
                label: 'Previous year'
            }, {
                data: [
                    randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),
                ],
                backgroundColor: [
                    "#F7464A",
                    "#46BFBD",
                    "#FDB45C",
                    "#949FB1",
                    "#4D5360",
                ],
                label: 'Previous year total'
            }],
            labels: [
                "Alloys",
                "Consumables",
                "Digitigal",
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
                text: 'Sales By Groups'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
        }
    };

    let configSalesHistory = {
        type: 'line',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: "Current Month",
                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
                fill: false,
                borderColor: 'rgba(255,99,132,1)',
                backgroundColor: 'rgba(255,99,132,1)',
                pointBorderColor: 'rgba(255,99,132,1)',
                pointBackgroundColor: 'rgba(255,99,132,1)',
                pointBorderWidth: 0
            }, {
                label: "Last Month",
                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderWidth: 0
            }, {
                label: "Last Year",
                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
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
                text:'Consolidated Sales'
            },
            tooltips: {
                mode: 'label',
                callbacks: {
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
                    }
                }]
            }
        }
    };

    let worldMapCountry = new Datamap({
        scope: 'world',
        element: document.getElementById('world-map-country'),
        projection: 'mercator',
        height: 300,
        fills: {
            defaultFill: '#f0af0a',
            lt50: 'rgba(0,244,244,0.9)',
            gt50: 'red'
        },

        data: {
            USA: {fillKey: 'lt50' },
            RUS: {fillKey: 'lt50' },
            CAN: {fillKey: 'lt50' },
            BRA: {fillKey: 'gt50' },
            ARG: {fillKey: 'gt50'},
            COL: {fillKey: 'gt50' },
            AUS: {fillKey: 'gt50' },
            ZAF: {fillKey: 'gt50' },
            MAD: {fillKey: 'gt50' }
        }
    });

    //bubbles, custom popup on hover template
    worldMapCountry.bubbles([
        {name: 'Hot', latitude: 21.32, longitude: 5.32, radius: 10, fillKey: 'gt50'},
        {name: 'Chilly', latitude: -25.32, longitude: 120.32, radius: 18, fillKey: 'lt50'},
        {name: 'Hot again', latitude: 21.32, longitude: -84.32, radius: 8, fillKey: 'gt50'},

    ], {
        popupTemplate: function(geo, data) {
            return "<div class='hoverinfo'>It is " + data.name + "</div>";
        }
    });

    let worldMapRegion= new Datamap({
        scope: 'world',
        element: document.getElementById('world-map-region'),
        projection: 'mercator',
        height: 300,
        fills: {
            defaultFill: '#f0af0a',
            lt50: 'rgba(0,244,244,0.9)',
            gt50: 'red'
        },

        data: {
            USA: {fillKey: 'lt50' },
            RUS: {fillKey: 'lt50' },
            CAN: {fillKey: 'lt50' },
            BRA: {fillKey: 'gt50' },
            ARG: {fillKey: 'gt50'},
            COL: {fillKey: 'gt50' },
            AUS: {fillKey: 'gt50' },
            ZAF: {fillKey: 'gt50' },
            MAD: {fillKey: 'gt50' }
        }
    });

    //bubbles, custom popup on hover template
    worldMapRegion.bubbles([
        {name: 'Hot', latitude: 21.32, longitude: 5.32, radius: 10, fillKey: 'gt50'},
        {name: 'Chilly', latitude: -25.32, longitude: 120.32, radius: 18, fillKey: 'lt50'},
        {name: 'Hot again', latitude: 21.32, longitude: -84.32, radius: 8, fillKey: 'gt50'},

    ], {
        popupTemplate: function(geo, data) {
            return "<div class='hoverinfo'>It is " + data.name + "</div>";
        }
    });

    let configDomesticExport = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                ],
                backgroundColor: [
                    "#F7464A",
                    "#46BFBD",
                    "#FDB45C",
                    "#949FB1",
                    "#4D5360",
                ],
            }],
            labels: [
                "Red",
                "Green",
                "Yellow",
                "Grey",
                "Dark Grey"
            ]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Domestic/Export Sales'
            }
        }
    };

    let configDevelopedUndeveloped = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                ],
                backgroundColor: [
                    "#F7464A",
                    "#46BFBD",
                    "#FDB45C",
                    "#949FB1",
                    "#4D5360",
                ],
            }],
            labels: [
                "Red",
                "Green",
                "Yellow",
                "Grey",
                "Dark Grey"
            ]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Developed/Undeveloped Sales'
            }
        }
    };

    window.onload = function() {
        let ctxSalesGroupDist = document.getElementById("sales-group-dist").getContext("2d");
        window.salesGroupDist = new Chart(ctxSalesGroupDist, configSalesGroupDist);

        let ctexSalesHistory = document.getElementById("sales-history").getContext("2d");
        window.salesHistory = new Chart(ctexSalesHistory, configSalesHistory);

        let ctxDomesticExport = document.getElementById("export-domestic-sales").getContext("2d");
        window.domesticExport = new Chart(ctxDomesticExport, configDomesticExport);

        let ctxDevelopedUndeveloped = document.getElementById("developed-undeveloped-sales").getContext("2d");
        window.developedUndeveloped = new Chart(ctxDevelopedUndeveloped, configDevelopedUndeveloped);
    };
</script>
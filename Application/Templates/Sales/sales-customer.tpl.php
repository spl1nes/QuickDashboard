<div id="world-map-country" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>
<div id="world-map-region" style="position: relative; width: 50%; max-height: 450px; float: left;"></div>

<div style="width: 100%;">
    <canvas id="active-customers" height="200px"></canvas>
</div>

<div id="canvas-holder-3" style="width: 50%; float: left">
    <canvas id="customer-group" height="200px">
</div>

<div id="canvas-holder-4" style="width: 50%; float: left">
    <canvas id="group-sales" height="200px">
</div>

<div style="width: 50%; float: left;">
    <canvas id="top-customers" height="250px"></canvas>
</div>

<div style="width: 50%; float: left;">
    <canvas id="customer-dependency" height="250px"></canvas>
</div>

<div class="clear"></div>
<script>
    let randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
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

    let configActiveCustomers = {
        type: 'bar',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: 'Dataset 1',
                backgroundColor: "rgba(220,220,220,0.5)",
                yAxisID: "y-axis-1",
                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
            }, {
                label: 'Dataset 2',
                backgroundColor: "rgba(151,187,205,0.5)",
                yAxisID: "y-axis-1",
                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
            }, {
                label: 'Dataset 3',
                backgroundColor: "rgba(0,244,244,0.9)",
                yAxisID: "y-axis-1",
                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Customer Activities"
            },
            scales: {
                yAxes: [{
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                }],
            }
        }
    };

    let configCustomerGroup = {
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
                text:'Customer Groups'
            }
        }
    };

    let configCustomerGroupSales = {
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
                text:'Customer Group Sales'
            }
        }
    };

    let configTopCustomers = {
        type: 'bar',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: 'Dataset 3',
                backgroundColor: "rgba(0,244,244,0.9)",
                yAxisID: "y-axis-1",
                data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
            }]
        },
        options: {
            responsive: true,
            hoverMode: 'label',
            hoverAnimationDuration: 400,
            stacked: false,
            title:{
                display:true,
                text:"Top Customers"
            },
            scales: {
                yAxes: [{
                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: "left",
                    id: "y-axis-1",
                }],
            }
        }
    };

    let configCustomerDependency = {
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
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Sales Distribution'
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

    window.onload = function() {
        let ctxActiveCustomers = document.getElementById("active-customers").getContext("2d");
        window.activeCustomers = new Chart(ctxActiveCustomers , configActiveCustomers);

        let ctxCustomerGroup = document.getElementById("customer-group").getContext("2d");
        window.customerGroup = new Chart(ctxCustomerGroup, configCustomerGroup);

        let ctxCustomerGroupSales = document.getElementById("group-sales").getContext("2d");
        window.developedUndeveloped = new Chart(ctxCustomerGroupSales, configCustomerGroupSales);

        let ctxTopCustomers = document.getElementById("top-customers").getContext("2d");
        window.topCustomers = new Chart(ctxTopCustomers, configTopCustomers);

        let ctxCustomerDependency = document.getElementById("customer-dependency").getContext("2d");
        window.customerDependency = new Chart(ctxCustomerDependency, configCustomerDependency);
    };
</script>
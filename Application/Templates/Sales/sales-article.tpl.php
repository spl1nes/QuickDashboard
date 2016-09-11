<div class="box" id="canvas-holder-1" style="width:50%; float: left">
    <canvas id="group-sales" height="200"></canvas>
</div>

<div id="canvas-holder-2" style="width:50%; float: left">
    <canvas id="product-group-sales" height="200"></canvas>
</div>

<table>
    <thead>
    <tr>
        <th>Name
        <th>Two Years Ago Acc.
        <th>Prev. Year Acc.
        <th>Current Year Acc.
        <th>Diff. Acc.
    <tbody>
    <tr>
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
</table>

<div id="canvas-holder-3" style="width:100%">
    <canvas id="top-articles" height="100"></canvas>
</div>
<div class="clear"></div>
<script>
    let randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    let configSalesGroup = {
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

    let configSalesGroupProduct = {
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

    let configTopArticles = {
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
                text:"Top Articles"
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

    window.onload = function() {
        let ctxSalesGroup = document.getElementById("group-sales");
        window.salesGroup = new Chart(ctxSalesGroup, configSalesGroup);

        let ctxSalesGroupProduct = document.getElementById("product-group-sales");
        window.salesGroupProduct = new Chart(ctxSalesGroupProduct, configSalesGroupProduct);

        let ctxTopArticles = document.getElementById("top-articles");
        window.salesGroupProduct = new Chart(ctxTopArticles, configTopArticles);
    };
</script>
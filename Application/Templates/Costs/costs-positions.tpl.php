<div style="width: 100%;">
    <canvas id="costs"></canvas>
</div>

<table>
    <thead>
    <tr>
        <th>Position
        <th>Two Years Ago Acc.
        <th>Prev. Year Acc.
        <th>Current Year Acc.
        <th>Diff. Acc.
    <tbody>
    <tr>
        <td>Freight
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Courses
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>External Commission
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Personnel
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Misc. Personnel
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Marketing
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Fairs
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Travel
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Repairs & Maintenance
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Communication
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Carpool
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>External Advisor
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>R&D
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <td>Patent & Licence
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
    <tr>
        <th>Total
        <th>231554321
        <th>231554321
        <th>231554321
        <th>20%
</table>

<script>
    let randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    let configCosts = {
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

    window.onload = function() {
        let ctxCosts = document.getElementById("costs").getContext("2d");
        window.consolidatedLine = new Chart(ctxCosts, configCosts);
    };
</script>
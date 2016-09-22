<div class="box" id="canvas-holder-1" style="width: 100%; float: left">
    <canvas id="top-reps-domestic" height="100"></canvas>
</div>

<div class="box" id="canvas-holder-2" style="width: 100%; float: left">
    <canvas id="top-reps-export" height="100"></canvas>
</div>

<table>
    <caption>Sales by Sales Rep. Domestic</caption>
    <thead>
        <tr>
            <th>Pos.
            <th>Name
            <th>Prev. Year
            <th>Prev. Year Acc.
            <th>Current Year
            <th>Diff. Acc.
    <tbody>
        <tr>
            <td>1
            <td>Test Name
            <td>231554321
            <td>231554321
            <td>231554321
            <td>20%
</table>

<table>
    <caption>Sales by Sales Rep. Dentist</caption>
    <thead>
    <tr>
        <th>Pos.
        <th>Name
        <th>Prev. Year
        <th>Prev. Year Acc.
        <th>Current Year
        <th>Diff. Acc.
    <tbody>
    <tr>
        <td>1
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
</table>

<table>
    <caption>Sales by Sales Rep. Export</caption>
    <thead>
    <tr>
        <th>Pos.
        <th>Name
        <th>Prev. Year
        <th>Prev. Year Acc.
        <th>Current Year
        <th>Diff. Acc.
    <tbody>
    <tr>
        <td>1
        <td>Test Name
        <td>231554321
        <td>231554321
        <td>231554321
        <td>20%
</table>

<div class="clear"></div>
<script>
    let randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    let configTopRepsDomestic = {
        type: 'bar',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: 'Domestic Sales Reps',
                backgroundColor: "rgba(102 ,237 , 152, 1)",
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
                text:"Sales Reps"
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

    let configTopRepsExport = {
        type: 'bar',
        data: {
            labels: ["July", "August", "September", "October", "November", "December", "January","February", "March", "April", "May", "June"],
            datasets: [{
                label: 'Export Sales Reps',
                backgroundColor: "rgba(237 ,102 , 102, 1)",
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
                text:"Sales Reps"
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
        let ctxTopRepsDomestic = document.getElementById("top-reps-domestic").getContext("2d");
        window.topRepsDomestic = new Chart(ctxTopRepsDomestic, configTopRepsDomestic);

        let ctxTopRepsExport = document.getElementById("top-reps-export").getContext("2d");
        window.topRepsExport = new Chart(ctxTopRepsExport, configTopRepsExport);
    };
</script>
<?php
$reps = $this->getData('repsSales');
?>
<table>
    <caption>Sales by Sales Rep</caption>
    <thead>
        <tr>
            <th>Name
            <th>Prev. Year
            <th>Current
            <th>Diff.
            <th>Diff. %
    <tbody>
        <?php foreach($reps as $name => $value) : ?>
        <tr>
            <td><?= $name; ?>
            <td><?= number_format($reps[$name]['old'] ?? 0, 0, ',', '.') ?>
            <td><?= number_format($reps[$name]['now'] ?? 0, 0, ',', '.') ?>
            <td><?= number_format(($reps[$name]['now'] ?? 0)-($reps['old'] ?? 0), 0, ',', '.') ?>
            <td><?= number_format(!isset($reps[$name]) || $reps[$name]['old'] == 0 ? 0 : (($reps[$name]['now'] ?? 0)/$reps[$name]['old']-1)*100, 0, ',', '.') ?> %
    <?php endforeach; ?>
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
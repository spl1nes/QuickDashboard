<h1>Overview</h1>
<?php include __DIR__ . '/Overview/sales.tpl.php'; ?>
<script>
    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-sales").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-sales").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);
    };
</script>
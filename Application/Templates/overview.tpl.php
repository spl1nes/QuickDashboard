<h1>Overview</h1>
<?php include __DIR__ . '/Overview/sales.tpl.php'; ?>
<blockquote><i class="fa fa-2x fa-quote-left"></i>Please keep in mind that the data integrity for live data cannot get ensured. Final figures are only available in the monthly, quarterly and annualy reporting. It's also important to note that many accounting entries are performed only at month end during the financial reporting. The provided figures aim to be as accurate as possible but for offical matters please refere to the monthly financial reporting.</blockquote>
<script>
    window.onload = function() {
        let ctxConsolidated = document.getElementById("overview-consolidated-sales").getContext("2d");
        window.consolidatedLine = new Chart(ctxConsolidated, configConsolidated);

        let ctxConsolidatedAcc = document.getElementById("overview-acc-consolidated-sales").getContext("2d");
        window.consolidatedLineAcc = new Chart(ctxConsolidatedAcc, configConsolidatedAcc);
    };
</script>
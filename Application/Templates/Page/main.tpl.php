<main>
    <?php include __DIR__ . '/nav.tpl.php'; ?>
    <div class="floater">
<?php
$dispatch = $this->getData('dispatch') ?? [];

foreach ($dispatch as $view) {
	if ($view instanceOf \Serializable) {
		echo $view->render();
	}
}
?>
<blockquote><i class="fa fa-2x fa-quote-left"></i>Please keep in mind that the data integrity for live data cannot get ensured. Final figures are only available in the monthly, quarterly and annualy reporting. It's also important to note that many accounting entries are performed only at month end during the financial reporting. The provided figures aim to be as accurate as possible but for offical matters please refere to the monthly financial reporting.</blockquote>

    </div>
</main>
<main>
    <?php include __DIR__ . '/nav.tpl.php'; ?>
    <div class="floater">
    <div id="loader" class="hidden"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><br><p>Loading...</p></div>
<?php
$dispatch = $this->getData('dispatch') ?? [];

foreach ($dispatch as $view) {
	if ($view instanceOf \Serializable) {
		echo $view->render();
	}
}
?>
    </div>
</main>
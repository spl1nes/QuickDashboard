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
    </div>
</main>
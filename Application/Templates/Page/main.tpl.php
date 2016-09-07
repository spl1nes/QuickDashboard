<?php
$dispatch = $this->getData('dispatch') ?? [];

foreach ($dispatch as $view) {
	if ($view instanceOf \Serializable) {
		echo $view->render();
	}
}
?>
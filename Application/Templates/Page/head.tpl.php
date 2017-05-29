<!DOCTYPE html>
<html>
<head>
	<title><?= $this->getData('title') ?? 'QuickDashboard'; ?></title>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/js/Chart.bundle.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/js/d3.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/js/topojson.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/js/datamaps.world.min.js'); ?>"></script>

	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/Asset/AssetManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/Autoloader.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/Utils/oLib.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/Uri/UriFactory.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/Event/EventManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/UI/ActionManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/Views/FormView.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/UI/FormManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/UI/TableManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/UI/TabManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/jsOMS/UI/UIManager.js'); ?>"></script>

	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Model/Message/Redirect.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Model/Message/Reload.js'); ?>"></script>

	<link rel="stylesheet" type="text/css" href="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/css/font-awesome.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/css/styles.css'); ?>">
	<link rel="shortcut icon" href="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/img/favicon.ico'); ?>" type="image/x-icon">
</head>
<body>
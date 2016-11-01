<!DOCTYPE html>
<html>
<head>
	<title><?= $this->getData('title') ?? 'QuickDashboard'; ?></title>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/js/Chart.bundle.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/js/d3.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/js/topojson.min.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/js/datamaps.world.min.js'); ?>"></script>

	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/Asset/AssetManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/Autoloader.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/Utils/oLib.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/Uri/UriFactory.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/Event/EventManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/UI/ActionManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/Views/FormView.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/UI/FormManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/UI/TableManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/UI/TabManager.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}jsOMS/UI/UIManager.js'); ?>"></script>

	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Model/Message/Redirect.js'); ?>"></script>
	<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Model/Message/Reload.js'); ?>"></script>

	<link rel="stylesheet" type="text/css" href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/css/font-awesome.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/css/styles.css'); ?>">
	<link rel="shortcut icon" href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/img/favicon.ico'); ?>" type="image/x-icon">
</head>
<body>
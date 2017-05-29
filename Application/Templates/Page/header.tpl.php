<div id="darken" class="hidden"></div>
<header>
    <div class="floater">
	    <a href="<?= \phpOMS\Uri\UriFactory::build('{/base}/'); ?>" id="logo-img"><img src="<?= \phpOMS\Uri\UriFactory::build('{/base}/Application/img/logo.png'); ?>"></a>
        <div id="logo-name">
        	<select class="plain" id="unit-selector" data-action='[{"listener": "change", "action": [{"type": "redirect", "uri": "{%}&u={#unit-selector}", "target": "self"}]}]'>
                <option value="consolidated"<?= $this->getData('unit') === 'consolidated' ? ' selected' :  ''; ?>>SD & GDF
                <option value="sd"<?= $this->getData('unit') === 'sd' ? ' selected' :  ''; ?>>SD
                <option value="gdf"<?= $this->getData('unit') === 'gdf' ? ' selected' :  ''; ?>>GDF
            </select>
        </div>
    </div>
</header>
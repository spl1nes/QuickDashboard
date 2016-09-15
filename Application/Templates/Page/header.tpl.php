<header>
    <div class="floater">
	    <a href="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}'); ?>" id="logo-img"><img src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/img/logo.png'); ?>"></a>
        <div id="logo-name">
        	<select class="plain" id="unit-selector" data-action='[{"listener": "change", "action": [{"type": "redirect", "uri": "{%}&u={#unit-selector}", "target": "self"}]}]'>
                <option value="consolidated" selected>SD & GDF
                <option value="sd">SD
                <option value="gdf">GDF
            </select>
        </div>
    </div>
</header>
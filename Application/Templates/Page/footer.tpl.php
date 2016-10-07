<footer>
    <div class="floater">
    	<div style="display: inline-block; float: left;"><lable for="date-selector">Date Selector:</lable> <input type="date" min="2015-07-01" max="<?= (new \DateTime('now'))->format('Y-m-d'); ?>" id="date-selector" data-action='[{"listener": "change", "action": [{"type": "redirect", "uri": "{%}&t={#date-selector}", "target": "self"}]}]' value="<?= $this->request->getData('t') ?? ''; ?>"></div>
        <div style="display: inline-block; float: right;">based on <a href="https://github.com/Orange-Management" target="_blank">Orange-Management</a></div>
        <div class="clear"></div>
    </div>
</footer>
<script type="text/javascript" src="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}Application/js/logic.js'); ?>"></script>
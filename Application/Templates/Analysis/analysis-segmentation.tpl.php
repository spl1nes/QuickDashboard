<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/segmentation?{?}'); ?>">
    <table>
        <tr>
            <td><label for="segment">Segment:</label>
            <td><select id="segment" name="segment"><?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::NAMING as $id => $name) : ?>
                <option value="<?= $id; ?>"<?php (int) $this->request->getData('segment') == $id ? ' selected' : ''; ?>><?= $id; ?> - <?= $name; ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
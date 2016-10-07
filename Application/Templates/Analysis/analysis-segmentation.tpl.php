<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/segmentation?{?}'); ?>">
    <table>
        <tr>
            <td><label for="segment">Segment:</label>
            <td><select id="segment" name="segment"><?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::NAMING as $id => $name) : ?>
                <option value="<?= $id; ?>"<?= ((int) $this->request->getData('segment')) == (int) $id ? ' selected' : ''; ?>><?= $id; ?> - <?= $name; ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
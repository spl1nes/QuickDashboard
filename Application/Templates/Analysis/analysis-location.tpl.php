<form method="GET" action="<?= \phpOMS\Uri\UriFactory::build('{/base}{/rootPath}analysis/location?{?}'); ?>">
    <table>
        <tr>
            <td><label for="location">Location:</label>
            <td><select id="location" name="location">
                <optgroup label="Domestic/Export">
                    <option value="Domestic">Domestic
                    <option value="Export">Export
                <optgroup label="Developed/Undeveloped">
                    <option value="Developed">Developed
                    <option value="Undeveloped">Undeveloped
                <optgroup label="Region">
                <?php foreach(\QuickDashboard\Application\Models\StructureDefinitions::REGIONS as $id => $countries) : ?>
                    <option value="<?= $id; ?>"><?= $id; ?>
                <?php endforeach; ?>
                    <option value="Other">Other
                <optgroup label="Country">
            <?php $countries = \QuickDashboard\Application\Models\StructureDefinitions::getCountries(); foreach($countries as $id => $name) : ?>
                <option value="<?= $name; ?>"><?= $name; ?>
            <?php endforeach; ?>
            </select>
            <td style="width: 100%">
                <input type="hidden" name="u" value="<?= $this->request->getData('u') ?? ''; ?>">
                <input type="hidden" name="t" value="<?= $this->request->getData('t') ?? ''; ?>">
        <tr>
            <td colspan="3"><input type="submit" value="Analyse">
    </table>
</form>
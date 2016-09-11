<form>
    <table>
        <tr>
            <td><label>Sales Rep:</label>
            <td><select><option>Name 1</select>
            <td><label>Start:</label>
            <td><input type="datetime-local" value="<?= (new \DateTime('NOW'))->format('Y-m-d\TH:i:s') ?>">
            <td><select><option>Consolidated<option>SD<option>GDF</select>
        <tr>
            <td><label>Article Groups:</label>
            <td><select><option>Name 1</select>
            <td><label>End:</label>
            <td><input type="datetime-local" value="<?= (new \DateTime('NOW'))->format('Y-m-d\TH:i:s') ?>">
            <td><input type="submit" value="Analyse">
    </table>
</form>
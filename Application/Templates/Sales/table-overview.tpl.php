<table>
    <thead>
    <tr>
        <th>Type
        <th>2 Years Ago
        <th>Last Year
        <th>Currently
        <th>Diff Last Year
        <th>Diff Last Year %
    <tbody>
    <tr>
        <td>Isolated Month
        <td><?= '€  ' . number_format($sales[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($sales[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format(($sales[$current][$currentMonth] ?? 0) - ($sales[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <td><?= !isset($sales[$current_1][$currentMonth]) || $sales[$current_1][$currentMonth] == 0 ? 0 : number_format((($sales[$current][$currentMonth] ?? 0)/$sales[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
    <tr>
        <td>Accumulated Year
        <td><?= '€  ' . number_format($salesAcc[$current_2][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current_1][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format($salesAcc[$current][$currentMonth] ?? 0, 0, ',', '.');  ?>
        <td><?= '€  ' . number_format(($salesAcc[$current][$currentMonth] ?? 0) - ($salesAcc[$current_1][$currentMonth] ?? 0), 0, ',', '.');  ?>
        <td><?= !isset($salesAcc[$current_1][$currentMonth]) || $salesAcc[$current_1][$currentMonth] == 0? 0 : number_format((($salesAcc[$current][$currentMonth] ?? 0)/$salesAcc[$current_1][$currentMonth]-1)*100, 2, ',', '.') . '%';  ?>
</table>
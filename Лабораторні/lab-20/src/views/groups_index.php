<h1>Список груп</h1>
<a href="/groups/create">Додати групу</a>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>Група</th>
        <th>Дія</th>
    </tr>
    <?php
    foreach ($groups as $group): ?>
        <tr>
            <td>
                <?= $group->columns->id ?>
            </td>
            <td>
                <?= $group->columns->title ?>
            </td>
            <td>
                <a href="/groups/<?= $group->columns->id ?>/show">Перейти</a>
            </td>
        </tr>
        <?php
    endforeach;
    ?>
</table>
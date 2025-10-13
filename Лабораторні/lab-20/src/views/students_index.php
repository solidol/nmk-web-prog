<h1>Список студентів</h1>

<table border='1'>
    <tr>
        <th>ID</th>
        <th>ПІБ</th>
        <th>Група</th>
        <th>Дія</th>
    </tr>
    <?php
    foreach ($students as $student): ?>
        <tr>
            <td>
                <?= $student->columns->id ?>
            </td>
            <td>
                <?= $student->columns->fullname ?>
            </td>
            <td>
                <?= $student->group()->columns->title ?>
            </td>
            <td>
                <a href="/students/<?= $student->columns->id ?>/show">Перейти до студента</a>
                <a href="/groups/<?= $student->group()->columns->id ?>/show">Перейти до групи</a>
            </td>
        </tr>
        <?php
    endforeach;
    ?>
</table>
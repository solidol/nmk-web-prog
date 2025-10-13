<h1>Студент</h1>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>ПІБ</th>
        <th>Група</th>
        <th>Відкрити групу</th>
    </tr>


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
            <a href="/groups/<?= $student->group()->columns->id ?>/show">Перейти до групи</a>
        </td>

    </tr>
</table>


</table>
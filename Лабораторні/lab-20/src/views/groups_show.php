<h1>Список груп</h1>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>Група</th>
    </tr>


    <tr>
        <td>
            <?= $group->columns->id ?>
        </td>
        <td>
            <?= $group->columns->title ?>
        </td>

    </tr>
</table>

<h1>Список студентів групи</h1>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>Ім'я користувача</th>
    </tr>
    <?php
    foreach ($group->students() as $user): ?>
        <tr>
            <td>
                <?= $user->columns->id ?>
            </td>
            <td>
                <?= $user->columns->fullname ?>
            </td>
        </tr>
        <?php
    endforeach;
    ?>

</table>
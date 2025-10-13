<h1>Список користувачів</h1>

<table border='1'>
    <tr>
        <th>ID</th>
        <th>Логін</th>
        <th>Електронна пошта</th>
        <th>Дія</th>
    </tr>
    <?php
    foreach ($users as $user): ?>
        <tr>
            <td>
                <?= $user->columns->id ?>
            </td>
            <td>
                <?= $user->columns->username ?>
            </td>
            <td>
                <?= $user->columns->email ?>
            </td>
            <td>
                <a href="/users/<?= $user->columns->id ?>/show">Перейти до користувача</a>
            </td>
        </tr>
        <?php
    endforeach;
    ?>
</table>
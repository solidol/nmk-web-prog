<h1>Користувач</h1>
<table border='1'>
    <tr>
        <th>ID</th>
        <th>Логін</th>
        <th>Електронна пошта</th>
    </tr>


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
    </tr>
</table>


</table>
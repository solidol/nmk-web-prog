
<?php

require_once dirname(__DIR__) . '/core/Model.php';

class User extends Model
{
    protected static string $defaultTable = 'users';
}

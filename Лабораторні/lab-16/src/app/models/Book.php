
<?php

require_once dirname(__DIR__) . '/core/Model.php';

class Book extends Model
{
    protected static string $defaultTable = 'books';
}

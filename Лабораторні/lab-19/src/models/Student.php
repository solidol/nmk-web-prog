<?php
class Student extends Model
{
    static $pdo;
    static $table = "students";

    public function group()
    {
        return $this->belongsTo("Group", 'group_id');
    }
}

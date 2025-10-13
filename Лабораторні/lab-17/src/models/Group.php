<?php
class Group extends Model
{
    static $pdo;
    static $table = "groups";

    public function students(){
        return $this->hasMany("Student", "group_id");
    }
}

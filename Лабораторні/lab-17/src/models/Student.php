<?php
class Student extends Model
{
    protected static string $defaultTable = 'students';

    public function group(): ?Group
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}

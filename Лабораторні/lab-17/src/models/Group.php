<?php
class Group extends Model
{
    protected static string $defaultTable = 'groups';

    public function students(): array
    {
        return $this->hasMany(Student::class, 'group_id');
    }
}

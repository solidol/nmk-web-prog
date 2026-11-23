<?php
require_once dirname(__DIR__) . '/app/core/Model.php';
require_once dirname(__DIR__) . '/app/models/Group.php';
require_once dirname(__DIR__) . '/app/models/Student.php';
$pdo = require dirname(__DIR__) . '/app/config/database.php';

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

header('Content-Type: text/html; charset=UTF-8');
$groups = Group::all($pdo);
$students = Student::all($pdo);
$membersByGroup = [];
foreach ($groups as $group) {
    $membersByGroup[$group->id] = $group->students();
}
$groupByStudent = [];
foreach ($students as $student) {
    $groupByStudent[$student->id] = $student->group();
}
require dirname(__DIR__) . '/app/views/groups/v_index.php';

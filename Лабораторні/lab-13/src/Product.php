<?php
class Product
{
    public $id;
    public $name;
    public $category;

    public function getLabel()
    {
        return $this->name . ' — ' . $this->category;
    }
}

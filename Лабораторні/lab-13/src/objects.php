<?php
class Product
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getLabel()
    {
        return 'Товар: ' . $this->name;
    }
}

$first = new Product('Хліб');
$second = new Product('Молоко');
$first->name = 'Житній хліб';
echo $second->getLabel(); // Товар: Молоко

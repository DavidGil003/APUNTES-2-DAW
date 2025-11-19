<?php

require_once 'Employee.php';

class Developer extends Employee
{
    public function __construct(string $name)
    {
        parent::__construct($name, 'Developer');

    }


    public function getName()
    {

        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }




}


?>
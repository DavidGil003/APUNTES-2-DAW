<?php

require_once 'Employee.php';

class Manager extends Employee
{
    protected int $teamSize;
    public function __construct(string $name, int $teamSize)
    {
        parent::__construct($name, 'Manager');
        $this->teamSize = $teamSize;

    }


    public function getName()
    {

        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }



    public function getRoleDescription(): string{


        return "Manages a team of {$this->teamSize} people";
    }


    public function getTeamSize(): int{
        return $this->teamSize;
    }

    public function setTeamSize(int $teamSize){
        $this->teamSize = $teamSize;
    }
}


?>
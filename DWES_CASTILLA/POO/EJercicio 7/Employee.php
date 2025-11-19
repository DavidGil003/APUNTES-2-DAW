<?php

class Employee
{

    public function __construct(protected $name, protected $role)
    {


    }

    public function getRoleDescription(): string
    {


        return "This is a general employee";


    }



}






?>
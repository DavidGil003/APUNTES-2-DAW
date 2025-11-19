<?php 
require_once 'Project.php';



$proyecto1 = new Project(1, "Nexus API Refactor");

$proyecto2 = new Project(2,"Onboarding OverHaul","In Progress");


echo "Proyecto 1: {$proyecto1->name}, ID: {$proyecto1->id}, Status: {$proyecto1->status}";
echo "<br>";

echo "Proyecto 2: {$proyecto2->name}, ID: {$proyecto2->id}, Status: {$proyecto2->status}";







?>
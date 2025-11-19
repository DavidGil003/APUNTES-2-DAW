<?php 
require_once 'TeamMember.php';



$miembro1 = new TeamMember("Ana García","ana.g@nexus.com","Developer");

$miembroCadena  = $miembro1->getProfile();



echo "". $miembro1->getProfile() . "";





?>
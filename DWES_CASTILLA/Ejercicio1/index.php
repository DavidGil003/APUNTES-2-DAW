<?php

require_once 'Database/db.php';
require_once 'Controllers/ComponenteController.php';

$controller = new ComponenteController($pdo);

$action = $_GET['action'] ?? 'list';

switch($action){


case 'add' : $controller->add();break;
case 'edit': $controller->update();break;
case 'delete': $controller->delete();break;
default: $controller->index();break;

}


?>





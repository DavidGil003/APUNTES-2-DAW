<?php
require_once 'db.php';

function getPaises() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT nombre, capital FROM paises ORDER BY nombre");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function existePais($pais) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM paises WHERE nombre = :nombre");
    $stmt->bindParam(':nombre', $pais);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addPais($pais, $capital) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO paises (nombre, capital) VALUES (:nombre, :capital)");
        $stmt->bindParam(':nombre', $pais);
        $stmt->bindParam(':capital', $capital);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}
function updateCapital($pais, $nuevaCapital) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE paises SET capital = :capital WHERE nombre = :nombre");
    $stmt->bindParam(':capital', $nuevaCapital);
    $stmt->bindParam(':nombre', $pais);
    return $stmt->execute();
}

function vaciarPaises() {
    global $pdo;
    return $pdo->exec("TRUNCATE TABLE paises");
}

?>
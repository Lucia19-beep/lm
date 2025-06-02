<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo "No hay sesión activa.";
    exit;
}

$usuario = $_SESSION['usuario'];

try {
    $bd = new PDO("mysql:host=localhost;dbname=pokemanager;charset=utf8", "root", "");
    $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE usuarios SET sobres = sobres + 2 WHERE usuario = ?";
    $stmt = $bd->prepare($sql);
    $stmt->execute([$usuario]);

    echo "Sobres actualizados correctamente.";
} catch (PDOException $e) {
    echo "Error al actualizar sobres: " . $e->getMessage();
}
?>

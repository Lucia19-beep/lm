<?php
include("inc/conectar_db.inc.php");
session_start();

$usuario = $_SESSION["usuario"];

try {
    
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $id_usuario = $stmt->fetchColumn();

    if ($id_usuario) {
       
        $stmt = $pdo->prepare("DELETE FROM coleccion WHERE id_usuario = ?");
        $stmt->execute([$id_usuario]);

       
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);

        session_destroy();
        echo "Tu cuenta y todos tus Pokémon han sido eliminados correctamente.";
    } else {
        echo "Usuario no encontrado.";
    }
} catch (PDOException $e) {
    echo "Error al eliminar: " . $e->getMessage();
}
?>



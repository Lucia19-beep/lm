<?php
session_start();
require_once 'conectar_db.inc.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'No has iniciado sesión']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$query = "UPDATE usuarios SET nombre = 'Usuario eliminado', ruta_foto_perfil = 'foto_generica.jpg' WHERE id = :id_usuario";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

try {
    $stmt->execute();
    session_unset();
    session_destroy();
    echo json_encode(['exito' => true, 'mensaje' => 'Cuenta eliminada correctamente']);
} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al eliminar cuenta: ' . $e->getMessage()]);
}
?>

<?php
session_start();
require_once 'conectar_db.inc.php';
$data = json_decode(file_get_contents('php://input'), true);
$usuario_id = $data['id'];


$query = "UPDATE usuarios SET nombre = 'Usuario eliminado', ruta_foto_perfil = 'foto_generica.jpg' WHERE id = :usuario_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

try {
    $stmt->execute();
    echo json_encode(['exito' => true, 'mensaje' => 'Cuenta eliminada']);
} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al eliminar cuenta: ' . $e->getMessage()]);
}
?>
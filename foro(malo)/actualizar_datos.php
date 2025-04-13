<?php
session_start();
require_once 'conectar_db.inc.php';

$data = json_decode(file_get_contents('php://input'), true);

$nombre = htmlspecialchars($data['nombre']);
$foto_perfil = $data['foto_perfil']; 
$contrasenya = $data['contrasenya'];
$usuario_id = $_SESSION['usuario_id'];


$query = "UPDATE usuarios SET nombre = :nombre, ruta_foto_perfil = :foto_perfil, contrasenya = :contrasenya WHERE id = :usuario_id";
$stmt = $conn->prepare($query);


if (!empty($contrasenya)) {
    $contrasenya = password_hash($contrasenya, PASSWORD_BCRYPT);
} else {
    $stmt->bindParam(':contrasenya', $contrasenya, PDO::PARAM_STR);
}

$stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
$stmt->bindParam(':foto_perfil', $foto_perfil, PDO::PARAM_STR); 
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

try {
    $stmt->execute();
    echo json_encode(['exito' => true, 'mensaje' => 'Datos actualizados']);
} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al actualizar: ' . $e->getMessage()]);
}
?>
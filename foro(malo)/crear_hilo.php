<?php
session_start();
require_once 'conectar_db.inc.php';

$data = json_decode(file_get_contents('php://input'), true);

// Obtener los datos del formulario para crear un nuevo hilo
$titulo = htmlspecialchars($data['titulo']);
$descripcion = htmlspecialchars($data['descripcion']);
$usuario_id = $_SESSION['usuario_id'];

$query = "INSERT INTO hilos (titulo, descripcion, usuario_id, fecha_creacion) VALUES (:titulo, :descripcion, :usuario_id, NOW())";

$stmt = $conn->prepare($query);
$stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
$stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

try {
    $stmt->execute();
    echo json_encode(['exito' => true, 'mensaje' => 'Hilo creado exitosamente']);
} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al crear hilo: ' . $e->getMessage()]);
}
?>
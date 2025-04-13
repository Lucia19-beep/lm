<?php
session_start();
require_once 'conectar_db.inc.php';

$titulo = htmlspecialchars($_POST['titulo']);
$descripcion = htmlspecialchars($_POST['descripcion']);
$id_usuario = $_SESSION['id_usuario'];

// Procesar la imagen
$nombreArchivo = $_FILES['ruta_foto_hilo']['name'];
$rutaTemporal = $_FILES['ruta_foto_hilo']['tmp_name'];
$carpetaDestino = 'img/';

$rutaFinal = $carpetaDestino . basename($nombreArchivo);

// Mover la imagen al destino
if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
    // Guardar el hilo con la ruta de la imagen
    $query = "INSERT INTO hilos (titulo, descripcion, ruta_foto_hilo, id_usuario, creado)
              VALUES (:titulo, :descripcion, :ruta_foto_hilo, :id_usuario, NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt->bindParam(':ruta_foto_hilo', $rutaFinal, PDO::PARAM_STR);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo json_encode(['exito' => true, 'mensaje' => 'Hilo creado exitosamente']);
    } catch (PDOException $e) {
        echo json_encode(['exito' => false, 'mensaje' => 'Error al crear hilo: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Error al subir la imagen']);
}
?>

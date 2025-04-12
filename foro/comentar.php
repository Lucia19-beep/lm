<?php
session_start();
require_once 'conectar_db.inc.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['id_usuario'])) {
    $idHilo = $_POST['hilo_id'];
    $texto = $_POST['comentario'];
    $idUsuario = $_SESSION['id_usuario'];

    try {
        $sql = "INSERT INTO comentarios (id_hilo, id_usuario, texto) VALUES (:hilo_id, :id_usuario, :texto)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":hilo_id", $idHilo);
        $stmt->bindParam(":id_usuario", $idUsuario);
        $stmt->bindParam(":texto", $texto);
        $stmt->execute();

        header("Location: hilo.php?id=" . $idHilo);
        exit;
    } catch (PDOException $e) {
        echo "Error al guardar el comentario: " . $e->getMessage();
    }
} else {
    echo "No tienes permiso para comentar.";
}
?>

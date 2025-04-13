<?php
session_start();
require_once 'conectar_db.inc.php';

if (isset($_POST['login'])) {
    $nombreInicioSesion = $_POST['usuario'];
    $contrasenyaInicioSesion = $_POST['contrasenya'];

    try {

        $sql = "SELECT * FROM usuarios WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":nombre", $nombreInicioSesion);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasenyaInicioSesion, $usuario['contrasenya'])) {
            // Inicio de sesión exitoso
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['token'] = bin2hex(random_bytes(16));
            echo "Inicio de sesión exitoso.<br>";
            header("Location:user.php");
            exit;
        } else {
            echo "Usuario no encontrado o contraseña incorrecta.<br>";
        }
    } catch (PDOException $e) {
        echo "Error de conexión o consulta: " . $e->getMessage();
    }
}
?>

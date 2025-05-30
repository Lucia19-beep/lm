<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "pokemanager");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["usuario"] ?? "";
    $contrasenya = $_POST["contrasenya"] ?? "";

    if ($email !== "" && $contrasenya !== "") {
        $sql = "SELECT id, usuario, contrasenya, foto, is_admin FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            if (password_verify($contrasenya, $fila["contrasenya"])) {
                $_SESSION["id"] = $fila["id"];
                $_SESSION["usuario"] = $fila["usuario"];
                $_SESSION["foto"] = $fila["foto"];
                $_SESSION["is_admin"] = $fila["is_admin"];

                header("Location: index.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Usuario no encontrado.";
        }
        $stmt->close();
    } else {
        $error = "Faltan datos del formulario.";
    }
}

$conexion->close();
?>
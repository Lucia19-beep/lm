<?php

$conexion = new mysqli("localhost", "root", "", "pokemanager");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// formulario
$usuario = $_POST["usuario"];
$email = $_POST["email"];
$email_repetido = $_POST["email_repetido"];
$contrasenya = $_POST["contrasenya"];
$contrasenya_repetida = $_POST["contrasenya_repetida"];
$edad = $_POST["edad"];

$errores = [];

if ($email !== $email_repetido) {
    $errores[] = "Los correos electrónicos no coinciden.";
}

if ($contrasenya !== $contrasenya_repetida) {
    $errores[] = "Las contraseñas no coinciden.";
}

if ($edad < 14) {
    $errores[] = "Debes tener al menos 14 años.";
}

if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,}$/', $contrasenya)) {
    $errores[] = "La contraseña debe tener al menos una letra, un número y 8 caracteres.";
}

$sql_usuario = "SELECT id FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql_usuario);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $errores[] = "El nombre de usuario ya existe.";
}
$stmt->close();

if (!empty($errores)) {
    foreach ($errores as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    echo "<p><a href='index.php'>Volver</a></p>";
    exit;
}

if (!empty($_FILES["foto"]["name"])) {
    $nombreFoto = basename($_FILES["foto"]["name"]);
    $rutaTemporal = $_FILES["foto"]["tmp_name"];
    $rutaDestino = "img/img/sprites/pngs/" . $nombreFoto;
    move_uploaded_file($rutaTemporal, $rutaDestino);
    $foto = $nombreFoto; 
} else {
    $foto = "profile_placeholder.png"; 
}


$contrasenyaHash = password_hash($contrasenya, PASSWORD_DEFAULT);

// Insertar en la base de datos
$sql_insertar = "INSERT INTO usuarios (usuario, email, contrasenya, edad, foto, is_admin)
                 VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql_insertar);
$is_admin = 0;
$stmt->bind_param("sssisi", $usuario, $email, $contrasenyaHash, $edad, $foto,$is_admin);
$stmt->execute();

// Guardar sesión
$_SESSION["id"] = $stmt->insert_id;
$_SESSION["usuario"] = $usuario;
$_SESSION["foto"] = $foto;

header("Location: index.php");
exit;

$stmt->close();
$conexion->close();
?>


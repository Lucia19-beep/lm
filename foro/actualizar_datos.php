<?php
session_start();
require_once 'conectar_db.inc.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Sesión no iniciada']);
    exit;
}

$usuario_id = $_SESSION['usuario']['id'];

// Obtener valores del formulario
$nombre = htmlspecialchars($_POST['nombre']);
$email = htmlspecialchars($_POST['email']);
$contrasena_actual = $_POST['contrasena'];
$nueva_contrasena = $_POST['nueva_contrasena'];

// Primero, verificar la contraseña actual
$stmt = $pdo->prepare("SELECT contrasenya FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario || !password_verify($contrasena_actual, $usuario['contrasenya'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Contraseña actual incorrecta']);
    exit;
}

// Procesar nueva imagen (si se subió)
$ruta_foto = null;
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
    $nombreArchivo = uniqid() . "_" . basename($_FILES['foto_perfil']['name']);
    $rutaDestino = "img/" . $nombreArchivo;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaDestino)) {
        $ruta_foto = $rutaDestino;
    } else {
        echo json_encode(['exito' => false, 'mensaje' => 'Error al subir la imagen']);
        exit;
    }
} else {
    $ruta_foto = $_SESSION['usuario']['ruta_foto_perfil']; // mantener la misma si no se cambia
}

// Si hay nueva contraseña, se actualiza; si no, se deja igual
if (!empty($nueva_contrasena)) {
    $nueva_contrasena = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
    $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, ruta_foto_perfil = :foto, contrasenya = :contrasena WHERE id = :id";
} else {
    $sql = "UPDATE usuarios SET nombre = :nombre, email = :email, ruta_foto_perfil = :foto WHERE id = :id";
}

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':foto', $ruta_foto);
$stmt->bindParam(':id', $usuario_id, PDO::PARAM_INT);

if (!empty($nueva_contrasena)) {
    $stmt->bindParam(':contrasena', $nueva_contrasena);
}

try {
    $stmt->execute();
    // Actualizamos la sesión con los nuevos datos
    $_SESSION['usuario']['nombre'] = $nombre;
    $_SESSION['usuario']['email'] = $email;
    $_SESSION['usuario']['ruta_foto_perfil'] = $ruta_foto;
    echo json_encode(['exito' => true, 'mensaje' => 'Datos actualizados correctamente']);
} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error en la actualización: ' . $e->getMessage()]);
}

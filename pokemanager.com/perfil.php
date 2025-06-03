<?php
if (!isset($_SESSION['id'])) {
  echo "Debes iniciar sesión.";
  exit;
}

require 'inc/conectar_db.inc.php'; 

$id = $_SESSION['id'];
$sql = "SELECT usuario, email, edad, fecha_registro, ultima_conexion, foto FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if ($usuario) {
  echo "<h2>Perfil de {$usuario['usuario']}</h2>";
  echo "<img src='img/img/sprites/pngs/{$usuario['foto']}' alt='Foto de perfil' width='90'>";
  echo "<p>Email: {$usuario['email']}</p>";
  echo "<p>Edad: {$usuario['edad']}</p>";
  echo "<p>Fecha de registro: {$usuario['fecha_registro']}</p>";
  echo "<p>Última conexión: {$usuario['ultima_conexion']}</p>";
  echo "<div class='boton-contenedor'><button onclick='eliminarCuenta()'>Eliminar cuenta</button></div>";
} else {
  echo "Usuario no encontrado.";
}
?>
<script src="js/perfil.js"></script>
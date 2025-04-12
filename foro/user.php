<?php
session_start(); // Asegúrate de iniciar la sesión al principio
require_once 'conectar_db.inc.php';

// Verificar si el ID de usuario está en la sesión
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index4.php'); // Redirigir si no hay sesión
    exit();
}
// Obtener el ID de usuario de la sesión
$usuario_id = $_SESSION['usuario_id'];

// Preparar la consulta para obtener los datos del usuario
$query = "SELECT id, nombre, email, ruta_foto_perfil FROM usuarios WHERE id = :usuario_id";
$stmt = $pdo->prepare($query); // Preparar la declaración PDO
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT); // Enlazar el parámetro
$stmt->execute(); // Ejecutar la consulta

// Obtener el resultado de la consulta
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario fue encontrado
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Datos de Usuario</h2>
    <img src="<?php echo htmlspecialchars($usuario['ruta_foto_perfil']); ?>" alt="Foto de perfil" width="100">
    <p>Nombre de usuario: <?php echo htmlspecialchars($usuario['nombre']); ?></p>
    <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>

    <button id="actualizarDatosBtn">Actualizar datos</button>
    <button id="eliminarCuentaBtn">Eliminar cuenta</button>

    <!-- Dialog para actualizar datos -->
    <dialog id="dialogoActualizarDatos" class="dialogo">
        <form action="" method="POST" class="formulario-actualizar-datos">
            <input type="text" name="nombre" id="nombre" placeholder="Nombre de usuario" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña actual" required>
            <input type="password" name="nueva_contrasena" id="nueva_contrasena" placeholder="Nueva contraseña">
            <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" required>
            <input type="submit" value="Actualizar datos">
        </form>
    </dialog>

    <!-- Dialog para borrar cuenta -->
    <dialog id="dialogoBorrarUsuario" class="dialogo">
        <form action="" method="POST" class="formulario-borrar-usuario">
            <input type="password" name="contrasena" id="contrasena" placeholder="Introduce tu contraseña" required>
            <input type="submit" value="Borrar cuenta">
        </form>
    </dialog>

    <!-- Dialog para crear nuevo hilo -->
    <dialog id="dialogoNuevoHilo" class="dialogo">
        <form action="includes/hilo.inc.php" method="POST" class="formulario-nuevo-hilo" enctype="multipart/form-data">
            <input type="text" name="titulo" id="titulo" placeholder="Título del hilo" required>
            <textarea name="descripcion" id="descripcion" cols="70" rows="5" placeholder="Descripción del hilo" required></textarea>
            <input type="file" name="ruta_foto_hilo" id="ruta_foto_hilo" accept="image/*" required>
            <input type="submit" value="Crear hilo">
        </form>
    </dialog>

    <script>
        document.getElementById('actualizarDatosBtn').addEventListener('click', function() {
            document.getElementById('dialogoActualizarDatos').showModal();
        });

        document.getElementById('formulario-actualizar-datos').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            fetch('actualizar_datos.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.mensaje);
                if (data.exito) {
                    location.reload();
                }
            });
        });
    </script>

</body>
</html>

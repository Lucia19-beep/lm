<?php
session_start(); 
require_once 'conectar_db.inc.php';

// Verificar si el ID de usuario está en la sesión
if (!isset($_SESSION['id_usuario'])) {
    header('Location: index.php'); 
    exit();
}

// Obtener el ID de usuario de la sesión
$id_usuario = $_SESSION['id_usuario'];

// Preparar la consulta para obtener los datos del usuario
$query = "SELECT id, nombre, email, ruta_foto_perfil FROM usuarios WHERE id = :id_usuario";
$stmt = $pdo->prepare($query); 
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT); // Enlazar el parámetro
$stmt->execute(); 
// Obtener el resultado de la consulta
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario fue encontrado
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

// Consulta para obtener los hilos creados por el usuario
$query_hilos = "SELECT * FROM hilos WHERE id_usuario = :id_usuario ORDER BY creado DESC";
$stmt_hilos = $pdo->prepare($query_hilos);
$stmt_hilos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt_hilos->execute();
$hilos = $stmt_hilos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <h2>Datos de Usuario</h2>
    <img src="<?php echo htmlspecialchars($usuario['ruta_foto_perfil']); ?>" alt="Foto de perfil" width="100">
    <p>Nombre de usuario: <?php echo htmlspecialchars($usuario['nombre']); ?></p>
    <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>

    <button id="actualizarDatosBtn">Actualizar datos</button>
    <button id="eliminarCuentaBtn">Eliminar cuenta</button>
    <button id="crearHilo">Crear nuevo Hilo</button>
    <button id="logoutBtn">Cerrar sesión</button>

    <h3>Hilos creados por <?php echo htmlspecialchars($usuario['nombre']); ?>:</h3>
    <ul>
    <?php if ($hilos): ?>
        <?php foreach ($hilos as $hilo): ?>
            <li>
                <a href="hilo.php?id=<?php echo $hilo['id']; ?>">
                    <strong><?php echo htmlspecialchars($hilo['titulo']); ?></strong>
                </a>
                <p><?php echo htmlspecialchars($hilo['descripcion']); ?></p>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No has creado ningún hilo.</p>
    <?php endif; ?>
    </ul>

    <!-- Dialog para actualizar datos -->
    <dialog id="dialogoActualizarDatos" class="dialogo">
        <form id="formulario-actualizar-datos" action="" method="POST" class="formulario-actualizar-datos">
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
        <form action="crear_hilo.php" method="POST" class="formulario-nuevo-hilo" enctype="multipart/form-data">
            <input type="text" name="titulo" id="titulo" placeholder="Título del hilo" required>
            <textarea name="descripcion" id="descripcion" cols="70" rows="5" placeholder="Descripción del hilo" required></textarea>
            <input type="file" name="ruta_foto_hilo" id="ruta_foto_hilo" accept="image/*" required>
            <input type="submit" value="Crear hilo">
        </form>
    </dialog>

   <script>
    document.getElementById('actualizarDatosBtn').addEventListener('click', () => {
    document.getElementById('dialogoActualizarDatos').showModal();
    });

    document.querySelector('.formulario-actualizar-datos').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('actualizar_datos.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        alert(data.mensaje);
        if (data.exito) location.reload();
        });
    });
    document.getElementById('eliminarCuentaBtn').addEventListener('click', () => {
    document.getElementById('dialogoBorrarUsuario').showModal();
});

document.querySelector('.formulario-borrar-usuario').addEventListener('submit', function(e) {
    e.preventDefault();

    const contrasena = document.getElementById('contrasena').value; 
    fetch('eliminar_usuario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ contrasena: contrasena })
    })
    .then(r => r.json())
    .then(data => {
        alert(data.mensaje);
        if (data.exito) {
            window.location.href = 'index.php';
        }
    });
});
    document.getElementById('crearHilo').addEventListener('click', () => {
        document.getElementById('dialogoNuevoHilo').showModal();
    });

    // Manejo del formulario de nuevo hilo
    document.querySelector('.formulario-nuevo-hilo').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('crear_hilo.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            alert(data.mensaje);
            if (data.exito) {
                location.reload(); 
            }
        });
    });

    //logout
    document.getElementById('logoutBtn').addEventListener('click', function() {
    window.location.href = 'logout.php';  
    });

</script>
</body>
</html>

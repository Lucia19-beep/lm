<?php
session_start();

$conexion = new mysqli("localhost", "root", "", "lm");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
if (!isset($_SESSION['usuario'])) {
    header("Location: index4.php");
    exit;
}

// Mostrar los 10 hilos + nuevos
$consultaHilos = "SELECT id, id_usuario, titulo, ruta_foto_hilo FROM hilos ORDER BY creado DESC LIMIT 10";
$resultadoHilos = $conexion->query($consultaHilos);

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ForoLucia</title>
    <!-- fuente -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Honk&family=Noto+Serif+Ahom&family=Rubik+Dirt&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="stylesheet.css">  
   
</head>
<body>
    <h1 class="titulo">Foro de Lucía</h1>
    <div class="formularios">
    <section>
        <h1>Registro</h1>
        <form action="registro.inc.php" method="post" enctype="multipart/form-data" 
        class="formulario-registro">
            <label for="text">Nombre de usuario</label>
            <input type="text" name="usuario" required>
            <br><br>
            <label for="text">Email</label>
            <input type="email" name="email" required>
            <br><br>
            <label for="text">Contraseña</label>
            <input type="password" name="contrasenya" required>
            <br><br>
            <label>Foto de perfil</label>
            <input type="file" name="foto" required>
            <br><br>
            <button name="registro">Registrarse</button>
        </form>
    </section>
    <section>
        <h1>Iniciar Sesión</h1>
        <form action="login.inc.php" method="post" class="formulario-login">
        <label for="text">Nombre de usuario</label>
            <input type="text" name="usuario" required>
            <br><br>
            <label for="text">Contraseña</label>
            <input type="password" name="contrasenya" required>
            <br><br>
            <button name="login">Iniciar Sesión</button>
        </form>
    </section>
</div>
    <?php
        require_once 'conectar_db.inc.php';
        $consultaHilos = "SELECT * FROM hilos LIMIT 10";
        
    try {
    $sql = "SELECT id, titulo, ruta_foto_hilo FROM hilos ORDER BY creado DESC LIMIT 10";
    $stmt = $pdo->query($sql);
    $hilos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<h2>Últimos hilos</h2>';
    echo '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';

    for ($i = 0; $i < count($hilos); $i++) {
        $hilo = $hilos[$i];
        echo '<div style="border: 1px solid #ccc; padding: 10px; width: 200px;">';
        echo '<a href="hilo.php?id=' . $hilo['id'] . '" style="text-decoration: none; color: black;">';
        echo '<img src="' . htmlspecialchars($hilo['ruta_foto_hilo']) . '" alt="Foto del hilo" style="width: 100%; height: auto;"><br>';
        echo '<strong>' . htmlspecialchars($hilo['titulo']) . '</strong>';
        echo '</a>';
        echo '</div>';
    }

    echo '</div>';
    } catch (PDOException $e) {
    echo "Error al obtener los hilos: " . $e->getMessage();
    }
    ?>

</body>
</html>

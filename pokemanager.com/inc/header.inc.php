<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$conexion = new mysqli("localhost", "root", "", "pokemanager");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Lucía">
    <meta name="description" content="Gestor de entrenadores y pokémon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PokeManager</title>
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
    <img src="/lm/pokemanager.com/img/img/logo.png" alt="Logo del sitio" class="logo" width="250px">

  <?php if (isset($_SESSION['usuario'])): ?>
    <div class="saludo-usuario" style="position: relative; float: right; cursor: pointer;">
        Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?>
        <img src="<?php echo htmlspecialchars('/lm/pokemanager.com/img/img/sprites/pngs/' . ($_SESSION['foto'] ?? 'profile_placeholder.png')); ?>"
             alt="Foto de perfil"
             style="width: 50px; height: 40px; border-radius: 50%; vertical-align: middle; margin-left: 5px;">

        <div class="logout-menu" style="position: absolute; top: 100%; right: 0; background: #4CAF50; padding: 5px 10px; border-radius: 4px; 
        white-space: nowrap;">
            <a href="logout.php" style="color: white; text-decoration: none;">Cerrar sesión</a>
        </div>
    </div>
    <?php else: ?>
        <div class="contenedor-login">
            <form action="/lm/pokemanager.com/login.php" method="post" class="formulario-login">
                <input type="email" name="usuario" placeholder="Email" required>
                <input type="password" name="contrasenya" placeholder="Contraseña" required>
                <button name="login">Iniciar sesión</button>
            </form>
        </div>
        <div class="registro-enlace">
            <a href="#" id="abrir-registro">¿No tienes cuenta? Regístrate</a>
        </div>
    <?php endif; ?>
</header>

    <dialog id="dialogo-registro">
        <form action="/lm/pokemanager.com/signup.php" method="post" enctype="multipart/form-data" class="formulario-registro">
            <h2>Registro de entrenador Pokémon</h2>
            <label>Nombre de usuario</label>
            <input type="text" name="usuario" required>
            <br><br>

            <label>Email</label>
            <input type="email" name="email" required>
            <br><br>

            <label>Repetir Email</label>
            <input type="email" name="email_repetido" required>
            <br><br>

            <label>Contraseña</label>
            <input type="password" name="contrasenya" required>
            <br><br>

            <label>Repetir Contraseña</label>
            <input type="password" name="contrasenya_repetida" required>
            <br><br>

            <label>Edad</label>
            <input type="number" name="edad" min="14" required>
            <br><br>

            <label>Foto de perfil</label>
            <input type="file" name="foto">
            <br><br>

            <div class="botones">
                <button type="button" id="cerrar-registro">Cancelar</button>
                <button type="submit" name="registro">Registrarse</button>
            </div>
        </form>
    </dialog>
    <script src="js/header.js"></script>
</body>
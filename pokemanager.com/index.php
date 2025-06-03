<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PokeManager</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/coleccion.css">

</head>
<body>

<?php 
session_start();
include(__DIR__ . "/inc/header.inc.php"); 
?>

<main>
<?php
if (!isset($_SESSION["usuario"])) {
    echo "<h2>¡Bienvenido a PokéManager!</h2>";
    echo "<p>Únete ahora para coleccionar, combatir y convertirte en el mejor entrenador.</p>";

} else {
    // Mostrar menú de pestañas
    echo '
    <nav class="menu-tabs">
        <a href="?pestaña=sobres">Sobres</a>
        <a href="?pestaña=coleccion">Colección</a>
        <a href="?pestaña=combate">Combate</a>
        <a href="?pestaña=perfil">Perfil</a>';
    
    if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"]) {
        echo '<a href="?pestaña=admin">Administrador</a>';
    }

    echo '</nav>';

    if (isset($_GET["pestaña"])) {
    $permitidas = ["sobres", "coleccion", "combate", "perfil", "admin"];
    $pestaña = $_GET["pestaña"];
    if (in_array($pestaña, $permitidas)) {
        if ($pestaña === "combate") {
            include("combate.html.php"); // ESTE muestra el HTML del combate
        } else {
            include("$pestaña.php"); // los demás cargan como siempre
        }
    } else {
        echo "<p>Pestaña no válida.</p>";
    }
} else {
    echo "<p>Selecciona una pestaña del menú para comenzar.</p>";
}
}
?>
</main>

<?php include(__DIR__ . "/inc/footer.inc.php"); ?>

<script src="js/header.js"></script>
</body>
</html>

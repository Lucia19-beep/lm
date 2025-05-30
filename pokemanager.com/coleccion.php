<?php


if (!isset($_SESSION["id"])) {
    echo "<p>Debes iniciar sesión para ver tu colección.</p>";
    exit;
}

$conexion = new mysqli("localhost", "root", "", "pokemanager");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$idUsuario = $_SESSION["id"];

// Consulta para obtener los Pokémon del usuario ordenados por id_pokemon
$sql = "
    SELECT p.id, p.name, p.icon_path, c.fecha 
    FROM coleccion c 
    INNER JOIN pokemon p ON c.id_pokemon = p.id
    WHERE c.id_usuario = ? 
    ORDER BY p.id
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();

echo "<h2>Mi Colección de Pokémon</h2>";

if ($resultado->num_rows === 0) {
    echo "<p>No tienes ningún Pokémon en tu colección.</p>";
} else {
    echo "<div class='coleccion-container'>";
    while ($poke = $resultado->fetch_assoc()) {
        echo "<div class='pokemon-item'>";
        echo "<img src='img/" . $poke["icon_path"] . "' width='80' alt='" . htmlspecialchars($poke["name"]) . "'>";
        echo "<p>" . htmlspecialchars($poke["name"]) . "</p>";
        echo "<small>Obtenido el: " . $poke["fecha"] . "</small>";
        echo "</div>";
    }
    echo "</div>";
}
?>

<?php
$conexion = new mysqli("localhost", "root", "", "pokemanager");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if (!isset($_SESSION["id"])) {
    echo "<p>Inicia sesión para abrir sobres.</p>";
    exit;
}

$idUsuario = $_SESSION["id"];
$hoy = date("Y-m-d");

// Obtener la última conexión
$sqlUltima = "SELECT ultima_conexion FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sqlUltima);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

$ultimaConexion = $fila["ultima_conexion"] ?? $hoy;
$diasSinConectar = floor((strtotime($hoy) - strtotime($ultimaConexion)) / (60 * 60 * 24));
$sobresDisponibles = max(4, $diasSinConectar);

// Inicializamos sobres abiertos hoy en sesión
if (!isset($_SESSION["sobres_abiertos"])) {
    $_SESSION["sobres_abiertos"] = 0;
}

// Actualizamos la última conexión a hoy
$updateConexion = $conexion->prepare("UPDATE usuarios SET ultima_conexion = ? WHERE id = ?");
$updateConexion->bind_param("si", $hoy, $idUsuario);
$updateConexion->execute();

// Si se abre un sobre
if (isset($_POST["abrir_sobre"])) {
    if ($_SESSION["sobres_abiertos"] < $sobresDisponibles) {
        echo "<h3>Pokémon obtenidos:</h3>";
        for ($i = 0; $i < 5; $i++) {
            $sqlPokemon = "SELECT id, name AS nombre, icon_path AS imagen FROM pokemon ORDER BY RAND() LIMIT 1";
            $res = $conexion->query($sqlPokemon);
            $poke = $res->fetch_assoc();

            echo '<div class="pokemon-item"><img src="img/' . $poke["imagen"] . '" width="100"><p>' . $poke["nombre"] . '</p></div>';

            $insert = $conexion->prepare("INSERT INTO coleccion (id_usuario, id_pokemon, fecha) VALUES (?, ?, ?)");
            $insert->bind_param("iis", $idUsuario, $poke["id"], $hoy);
            $insert->execute();
        }
        $_SESSION["sobres_abiertos"]++;
    } else {
        echo "<p>No tienes sobres disponibles.</p>";
    }
}

// Mostrar botón si quedan sobres por abrir hoy
$sobresRestantes = $sobresDisponibles - $_SESSION["sobres_abiertos"];

if ($sobresRestantes > 0) {
    echo "<p>Tienes $sobresRestantes sobres por abrir.</p>";
    echo "<form method='post'><button name='abrir_sobre'>Abrir sobre</button></form>";
} else {
    echo "<p>No tienes sobres disponibles por hoy.</p>";
}
?>

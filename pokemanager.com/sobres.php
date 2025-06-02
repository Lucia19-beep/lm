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

// Comprobamos si hay que añadir sobres por días sin conexión
$sqlUltima = "SELECT ultima_conexion, sobres FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sqlUltima);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

$ultimaConexion = $fila["ultima_conexion"] ?? $hoy;
$sobres = (int)$fila["sobres"];
$diasSinConectar = floor((strtotime($hoy) - strtotime($ultimaConexion)) / (60 * 60 * 24));

// Añadimos sobres por días sin conexión (mínimo 1)
if ($diasSinConectar > 0) {
    $sobres += max(1, $diasSinConectar);
    $stmt = $conexion->prepare("UPDATE usuarios SET sobres = ?, ultima_conexion = ? WHERE id = ?");
    $stmt->bind_param("isi", $sobres, $hoy, $idUsuario);
    $stmt->execute();
}

// Si se abre un sobre
if (isset($_POST["abrir_sobre"])) {
    if ($sobres > 0) {
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

        // Restar 1 sobre
        $sobres--;
        $stmt = $conexion->prepare("UPDATE usuarios SET sobres = ? WHERE id = ?");
        $stmt->bind_param("ii", $sobres, $idUsuario);
        $stmt->execute();
    } else {
        echo "<p>No tienes sobres disponibles.</p>";
    }
}

// Mostrar botón si quedan sobres
if ($sobres > 0) {
    echo "<p>Tienes $sobres sobres disponibles.</p>";
    echo "<form method='post'><button name='abrir_sobre'>Abrir sobre</button></form>";
} else {
    echo "<p>No tienes sobres disponibles.</p>";
}
?>

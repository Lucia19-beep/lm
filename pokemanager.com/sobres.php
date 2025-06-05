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

if (isset($_SESSION["pokemon_obtenidos"])) {
    echo "<h3>Pokémon obtenidos:</h3>";
    foreach ($_SESSION["pokemon_obtenidos"] as $poke) {
        echo '<div class="pokemon-item"><img src="img/' . $poke["imagen"] . '" width="100"><p>' . $poke["nombre"] . '</p></div>';
    }
    unset($_SESSION["pokemon_obtenidos"]);
}

$sqlUltima = "SELECT ultima_conexion, sobres FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sqlUltima);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$fila = $resultado->fetch_assoc();

$ultimaConexion = $fila["ultima_conexion"];
$sobres = (int)$fila["sobres"];

if (!$ultimaConexion || $ultimaConexion == "0000-00-00") {
    // usuario nuevo -> 1 sobre
    $ultimaConexion = date("Y-m-d", strtotime($hoy . " -1 day"));
}

$diasSinConectar = floor((strtotime($hoy) - strtotime($ultimaConexion)) / (60 * 60 * 24));

if ($diasSinConectar > 0) {
    $sobres += $diasSinConectar;
    $stmt = $conexion->prepare("UPDATE usuarios SET sobres = ?, ultima_conexion = ? WHERE id = ?");
    $stmt->bind_param("isi", $sobres, $hoy, $idUsuario);
    $stmt->execute();
}

if (isset($_POST["abrir_sobre"])) {
    if ($sobres > 0) {
        $yaTiene = [];
        $sqlYaTiene = "SELECT id_pokemon FROM coleccion WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sqlYaTiene);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($fila = $result->fetch_assoc()) {
            $yaTiene[] = $fila["id_pokemon"];
        }

        if (count($yaTiene) > 0) {
            $ids = implode(",", $yaTiene); //implode ->conjunto de datos en un texto juntito
            $sql = "SELECT id, name AS nombre, icon_path AS imagen FROM pokemon WHERE id NOT IN ($ids)";
            $result = $conexion->query($sql);
        } else {
            $sql = "SELECT id, name AS nombre, icon_path AS imagen FROM pokemon";
            $result = $conexion->query($sql);
        }

        $pokemonsDisponibles = [];
        while ($fila = $result->fetch_assoc()) {
            $pokemonsDisponibles[] = $fila;
        }

        if (count($pokemonsDisponibles) === 0) {
            echo "<p>¡Ya tienes todos los Pokémon disponibles! No puedes conseguir más.</p>";
        } else {
            shuffle($pokemonsDisponibles);//shuffle -> aleatorio
            $aInsertar = array_slice($pokemonsDisponibles, 0, 5); //trocito de array

            echo "<h3>Pokémon obtenidos:</h3>";
            foreach ($aInsertar as $poke) {
                echo '<div class="pokemon-item"><img src="img/' . $poke["imagen"] . '" width="100"><p>' . $poke["nombre"] . '</p></div>';
                $insert = $conexion->prepare("INSERT INTO coleccion (id_usuario, id_pokemon, fecha) VALUES (?, ?, ?)");
                $insert->bind_param("iis", $idUsuario, $poke["id"], $hoy);
                $insert->execute();
            }

            $sobres--;
            $stmt = $conexion->prepare("UPDATE usuarios SET sobres = ? WHERE id = ?");
            $stmt->bind_param("ii", $sobres, $idUsuario); //unir variables
            $stmt->execute();
        }

        $_SESSION["pokemon_obtenidos"] = $aInsertar;
        header("Location: index.php?pestaña=sobres");
        exit;
    } else {
        echo "<p>No tienes sobres disponibles.</p>";
    }
}

if ($sobres > 0) {
    echo "<p>Tienes $sobres sobres disponibles.</p>";
    echo "<form method='post'><button name='abrir_sobre'>Abrir sobre</button></form>";
} else {
    echo "<p>No tienes sobres disponibles.</p>";
}
?>


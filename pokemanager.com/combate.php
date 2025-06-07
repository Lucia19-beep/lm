<?php
session_start();
ob_clean(); // BORRA cualquier cosa antes del JSON
header("Content-Type: application/json");
ini_set("display_errors", 1);
error_reporting(E_ALL);


if (!isset($_SESSION["usuario"]) || !isset($_SESSION["id"])) {
    echo json_encode(["error" => "No hay sesión iniciada"]);
    exit;
}

$idUsuario = $_SESSION["id"];

$conexion = new mysqli("localhost", "root", "", "pokemanager");
if ($conexion->connect_error) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}


$misPokes = $conexion->prepare("
    SELECT p.id, p.Name, p.`Type 1`, p.`Type 2`, p.HP, p.Attack, p.Defense, p.`Sp. Atk`, p.`Sp. Def`, p.Speed, p.icon_path
    FROM coleccion c
    JOIN pokemon p ON c.id_pokemon = p.id
    WHERE c.id_usuario = ?
    ORDER BY RAND()
    LIMIT 6
");
$misPokes->bind_param("i", $idUsuario);
$misPokes->execute();
$resultado1 = $misPokes->get_result();
$equipo1 = [];
while ($row = $resultado1->fetch_assoc()) {
    $equipo1[] = $row;
}


$rival = $conexion->prepare("
    SELECT u.id 
    FROM usuarios u
    WHERE u.id != ?
      AND (SELECT COUNT(*) FROM coleccion c WHERE c.id_usuario = u.id) >= 6
    ORDER BY RAND()
    LIMIT 1
");

$rival->bind_param("i", $idUsuario);
$rival->execute();
$resRival = $rival->get_result();
if ($resRival->num_rows == 0) {
    echo json_encode(["error" => "No hay rivales disponibles"]);
    exit;
}
$idRival = $resRival->fetch_assoc()["id"];


$pokeRival = $conexion->prepare("
    SELECT p.id, p.Name, p.`Type 1`, p.`Type 2`, p.HP, p.Attack, p.Defense, p.`Sp. Atk`, p.`Sp. Def`, p.Speed, p.icon_path
    FROM coleccion c
    JOIN pokemon p ON c.id_pokemon = p.id
    WHERE c.id_usuario = ?
    ORDER BY RAND()
    LIMIT 6
");
$pokeRival->bind_param("i", $idRival);
$pokeRival->execute();
$resultado2 = $pokeRival->get_result();
$equipo2 = [];
while ($row = $resultado2->fetch_assoc()) {
    $equipo2[] = $row;
}

if (count($equipo1) < 6 || count($equipo2) < 6) {
    echo json_encode(["error" => "Faltan Pokémon para uno de los equipos"]);
    exit;
}

echo json_encode([
    "jugador" => $equipo1,
    "rival" => $equipo2
]);
exit;


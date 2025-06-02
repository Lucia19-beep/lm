<?php
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1) {
    echo "<p>No tienes permiso para ver esta sección.</p>";
    exit;
}

include("inc/conectar_db.inc.php");

// Si se envió el formulario para actualizar
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["actualizar_usuario"])) {
    $id = $_POST["id"];
    $usuario = trim($_POST["usuario"]);
    $email = trim($_POST["email"]);
    $is_admin = isset($_POST["is_admin"]) ? 1 : 0;

    $sql = "UPDATE usuarios SET usuario=?, email=?, is_admin=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssii", $usuario, $email, $is_admin, $id);
    $stmt->execute();
    $stmt->close();
    echo "<p>Usuario actualizado correctamente.</p>";
}

// Mostrar lista de usuarios
$resultado = $conexion->query("SELECT id, usuario, email, is_admin FROM usuarios");

echo "<h2>Panel de administración</h2>";
echo "<table border='1' cellpadding='8'>
        <tr><th>ID</th><th>Usuario</th><th>Email</th><th>Admin</th><th>Editar</th></tr>";

while ($fila = $resultado->fetch_assoc()) {
    echo "<tr>
            <td>{$fila['id']}</td>
            <td>{$fila['usuario']}</td>
            <td>{$fila['email']}</td>
            <td>" . ($fila['is_admin'] ? 'Sí' : 'No') . "</td>
            <td>
                <form method='post'>
                    <input type='hidden' name='id' value='{$fila['id']}'>
                    <input type='text' name='usuario' value='{$fila['usuario']}' required>
                    <input type='email' name='email' value='{$fila['email']}' required>
                    <label><input type='checkbox' name='is_admin' " . ($fila['is_admin'] ? "checked" : "") . "> Admin</label>
                    <button type='submit' name='actualizar_usuario'>Actualizar</button>
                </form>
            </td>
          </tr>";
}
echo "</table>";

$conexion->close();
?>

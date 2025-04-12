<?php
session_start();
require_once 'conectar_db.inc.php';

$idHilo = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idHilo > 0) {
    try {
        $consultaHilo = "SELECT * FROM hilos WHERE id = :id";
        $stmtHilo = $pdo->prepare($consultaHilo);
        $stmtHilo->bindParam(":id", $idHilo);
        $stmtHilo->execute();

        $hilo = $stmtHilo->fetch(PDO::FETCH_ASSOC);

        if ($hilo) {
            echo '<img src="' . htmlspecialchars($hilo['ruta_foto_hilo']) . '" width="200">';
            echo '<h2>' . htmlspecialchars($hilo['titulo']) . '</h2>';
            echo '<p>' . htmlspecialchars($hilo['descripcion']) . '</p>';

            $consultaComentarios = "SELECT u.ruta_foto AS foto_perfil, u.nombre, c.texto 
                                    FROM comentarios c 
                                    JOIN usuarios u ON c.id_usuario = u.id 
                                    WHERE c.id_hilo = :idHilo 
                                    ORDER BY c.creado ASC";

            $stmtComentarios = $pdo->prepare($consultaComentarios);
            $stmtComentarios->bindParam(":idHilo", $idHilo);
            $stmtComentarios->execute();

            $comentarios = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);

            foreach ($comentarios as $comentario) {
                echo '<div class="comentario">';
                echo '<img src="' . htmlspecialchars($comentario['foto_perfil']) . '" width="50">';
                echo '<strong>' . htmlspecialchars($comentario['nombre']) . '</strong>';
                echo '<p>' . htmlspecialchars($comentario['texto']) . '</p>';
                echo '</div>';
            }

            if (isset($_SESSION['usuario'])) {
                echo '<form method="post" action="comentar.php">';
                echo '<input type="hidden" name="hilo_id" value="' . htmlspecialchars($idHilo) . '">';
                echo '<textarea name="comentario" required></textarea>';
                echo '<button type="submit">Comentar</button>';
                echo '</form>';
            } else {
                echo '<p>Regístrate o inicia sesión para poder comentar</p>';
            }

        } else {
            echo "El hilo no existe.";
        }
    } catch (PDOException $e) {
        echo "Error al consultar: " . $e->getMessage();
    }
} else {
    echo "ID de hilo no válido.";
}
?>



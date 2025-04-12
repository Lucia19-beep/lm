
<?php
session_start();
require_once 'conectar_db.inc.php';

if (isset($_POST['registro'])) {
    $nombreRegistro = $_POST['usuario'];
    $emailRegistro = $_POST['email'];
    $contrasenyaRegistro = $_POST['contrasenya'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $foto = $_FILES['foto'];
        $carpetaDestino = "img/";
        $nombreImagen = uniqid() . "-" . basename($foto["name"]);
        $rutaFinal = $carpetaDestino . $nombreImagen;

        $imageInfo = getimagesize($foto['tmp_name']);
        if ($imageInfo !== false) {
            if (move_uploaded_file($foto["tmp_name"], $rutaFinal)) {
                $hash = password_hash($contrasenyaRegistro, PASSWORD_DEFAULT);

                try {
                    

                    $textoInsert = "INSERT INTO usuarios (nombre, email, contrasenya, ruta_foto_perfil) 
                                    VALUES (:nombre, :email, :contrasenya, :foto)";
                    $stmt = $pdo->prepare($textoInsert);
                    $stmt->bindParam(":nombre", $nombreRegistro);
                    $stmt->bindParam(":email", $emailRegistro);
                    $stmt->bindParam(":contrasenya", $hash);
                    $stmt->bindParam(":foto", $rutaFinal);

                    $stmt->execute();

                    echo "Registro exitoso.";
                } catch (PDOException $e) {
                    echo " Error al registrar: " . $e->getMessage();
                }
            } else {
                echo " Error al mover la imagen.";
            }
        } else {
            echo "El archivo no es una imagen válida.";
        }
    } else {
        echo "Por favor, sube una foto de perfil.";
    }
}
?>

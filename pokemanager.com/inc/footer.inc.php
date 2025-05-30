<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<footer style="background-color: #4CAF50; color: white; padding: 1em; display: flex; justify-content: space-between; align-items: center;">
    <div>
        Desarrollado por Lucía © <?php echo date("Y"); ?>
    </div>

    <?php if (isset($_SESSION['usuario'])): ?>
        <div style="position: relative;">
            <div class="perfil-hover">
                <span style="margin-right: 10px;">Hola, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
                <img src="<?php echo htmlspecialchars($_SESSION['foto_perfil'] ?? 'img/img/profile_placeholder.png'); ?>"
                     alt="Foto de perfil"
                     style="width: 40px; height: 40px; border-radius: 50%;">
                <div id="logoutMenu" class="logout-menu">
                    <a href="logout.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</footer>

<script src="js/footer.js"></script>

</body>
</html>

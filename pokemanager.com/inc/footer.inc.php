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
        </div>
    <?php endif; ?>
</footer>

<script src="js/footer.js"></script>

</body>
</html>

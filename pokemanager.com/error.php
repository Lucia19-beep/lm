<?php
session_start();
include("header.inc.php");
?>

<main style="background-color: #f9f3e4; min-height: 70vh; display: flex; justify-content: center; align-items: center;">
    <div style="background-color: white; border-radius: 10px; padding: 40px; text-align: center; width: 60%; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #4CAF50; font-size: 28px;">Ooops... ¡algo ha fallado!</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 20px 0;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </p>
        <?php endif; ?>

        <img src="img/img/sad_pikachu.jpg" alt="Sad Pikachu" style="max-width: 200px; margin-top: 20px;">
    </div>
</main>

<?php
include("footer.inc.php");
?>

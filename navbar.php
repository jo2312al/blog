<?php
// Iniciar sesión solo si no está activa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container" style="margin-left: 5%;">
        <a class="navbar-brand" href="index.php">CANACINTRA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categorias.php">Categorías</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sobrenosotros.php">Sobre nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacto.php">Contáctanos</a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="btn btn-custom btn-md">Cerrar sesión</a>
                    <?php else: ?>
                        <a href="iniciar.php" class="btn btn-custom btn-md me-2">Iniciar sesión</a>
                        <a href="registro.php" class="btn btn-custom btn-md">Registrar</a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>
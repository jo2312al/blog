<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">

</head>
<body>

       <!-- Navigation Bar -->
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
                        <a class="nav-link active" href="categorias.php">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sobrenosotros.php">Sobre nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">Contáctanos</a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="iniciar.php" class="btn btn-primary btn-md me-2">Iniciar sesión</a>
                        <a href="registro.php" class="btn btn-primary btn-md">Registrar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sign In Form -->
    <div class="signin-container">
        <!-- Logo -->
        <div class="logo">
            <h1>CANACINTRA</h1>
            <p>Tabasco</p>
        </div>
        <!-- Form -->
        <form>
            <div class="mb-3">
                <input type="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-signin">Registrar</button>
        </form>
    </div>
    <!-- Footer -->
    <footer class="mt-auto">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">CANACINTRA</h3>
                </div>
                <div>
                    <a href="#" class="me-3">Enlace adicional</a>
                    <a href="#" class="me-3">Enlace adicional</a>
                    <a href="#">Enlace adicional</a>
                </div>
                <div>
                    <small>© CANACINTRA 2025</small>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
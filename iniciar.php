<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Ajuste para que el footer siempre esté al final */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Asegura que el body ocupe al menos el 100% de la altura de la ventana */
            background-color: #f8f9fa;
        }
        .main-content {
            flex: 1 0 auto; /* Hace que el contenido principal crezca para ocupar el espacio disponible */
        }
        .footer {
            background-color: #4b0082;
            color: white;
            padding: 1rem 0;
            flex-shrink: 0; /* Evita que el footer se encoja */
            width: 100%;
        }
    </style>
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

    <!-- Contenido principal -->
    <div class="main-content">
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
                    <input type="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-signin">Iniciar sesión</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h3>CANACINTRA</h3>
                </div>
                <div class="col-md-3">
                    <a href="#" class="text-white">Additional Link</a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="text-white">Additional Link</a>
                </div>
                <div class="col-md-3 text-end">
                    <p>© Your Company 2022, we love you!</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros | Canacintra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            margin: 0; /* Evitar márgenes por defecto */
        }
        footer {
            background-color: #6a2982;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        footer a {
            color: white;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
<?php include 'navbar.php'; ?>

    <!-- Sobre Nosotros -->
    <section class="py-5 flex-grow-1">
        <div class="container">
            <h2 class="mb-4">Sobre Nosotros</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="bg-secondary" style="height: 300px;"></div>
                </div>
                <div class="col-md-8">
                    <h3>Canacintra</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed consequat, risus quis auctor posuere, quam magna posuere ipsum, sed vulputate urna nibh sit amet tellus...</p>
                    <h5>Síguenos en redes sociales</h5>
                    <p>
                        <a href="#" class="text-decoration-none me-2">📘</a>
                        <a href="#" class="text-decoration-none me-2">🐦</a>
                        <a href="#" class="text-decoration-none">📸</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
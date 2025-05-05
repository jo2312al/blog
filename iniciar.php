<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Prevenir caché
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Debug: Verificar inclusión del archivo
if (!file_exists('crud/user.php')) {
    die('Error: No se encontró crud/user.php. Verifica la ruta del archivo.');
}
require_once 'crud/user.php';

// Debug: Verificar existencia de la clase y el método
if (!class_exists('user')) {
    die('Error: La clase user no está definida en crud/user.php.');
}
if (!method_exists('user', 'login')) {
    die('Error: El método login no está definido en la clase user.');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($identifier) && !empty($password)) {
        echo "Debug: Identificador recibido: $identifier<br>";
        echo "Debug: Contraseña recibida: $password<br>";
        $user = user::login($identifier, $password);
        var_dump($user); // Mostrar los datos del usuario encontrado
        if ($user) {
            // Comparación sin hash para depuración
            if ($user['password'] === $password) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                echo "Debug: Sesión iniciada para user_id: " . $user['id'] . "<br>";
                header('Location: index.php');
                exit;
            } else {
                $error = 'Contraseña incorrecta (comparación en texto plano).';
                echo "Debug: Contraseña no coincide: " . $user['password'] . " vs " . $password . "<br>";
            }
        } else {
            $error = 'Usuario/Correo no encontrado.';
            echo "Debug: No se encontró usuario para: $identifier<br>";
        }
    } else {
        $error = 'Por favor, complete todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .main-content {
            flex: 1 0 auto;
        }
        .footer {
            background-color: #4b0082;
            color: white;
            padding: 1rem 0;
            flex-shrink: 0;
            width: 100%;
        }
        .signin-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .logo h1 {
            margin: 0;
            font-size: 2rem;
            color: #4b0082;
        }
        .logo p {
            margin: 0;
            font-size: 1rem;
            color: #6c757d;
        }
        .btn-signin {
            width: 100%;
            background-color: #4b0082;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .btn-signin:hover {
            background-color: #6a0dad;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <?php include 'navbar.php'; ?>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Sign In Form -->
        <div class="signin-container">
            <!-- Logo -->
            <div class="logo">
                <h1>CANACINTRA</h1>
                <p>Tabasco</p>
            </div>
            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <!-- Form -->
            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" class="form-control" name="identifier" placeholder="Usuario o Correo" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
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
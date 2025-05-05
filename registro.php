<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Canacintra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include 'navbar.php'; ?>

    <!-- Sign In Form -->
    <div class="signin-container">
        <!-- Logo -->
        <div class="logo">
            <h1>CANACINTRA</h1>
            <p>Tabasco</p>
        </div>
        <!-- Form -->
        <?php
        require_once 'crud/user.php';

        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validar que las contraseñas coincidan
            if ($password !== $confirm_password) {
                $mensaje = '<div class="alert alert-danger">Las contraseñas no coinciden.</div>';
            } else {
                // Verificar si el email o username ya existen
                $usuarioExistente = DB::queryFirstRow("SELECT * FROM user WHERE email = %s OR username = %s", $email, $username);
                if ($usuarioExistente) {
                    $mensaje = '<div class="alert alert-danger">El email o username ya están registrados.</div>';
                } else {
                    // Crear el usuario
                    $data = [
                        'email' => $email,
                        'username' => $username,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'status' => 'activo', // Estado por defecto
                        'fk_rol' => 2 // Rol por defecto (por ejemplo, 2 para usuario normal)
                    ];
                    try {
                        user::create($data);
                        $mensaje = '<div class="alert alert-success">Usuario registrado exitosamente. <a href="iniciar.php">Inicia sesión</a>.</div>';
                    } catch (Exception $e) {
                        $mensaje = '<div class="alert alert-danger">Error al registrar: ' . $e->getMessage() . '</div>';
                    }
                }
            }
        }
        ?>
        <?php if ($mensaje): ?>
            <?php echo $mensaje; ?>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
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
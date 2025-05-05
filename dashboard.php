<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Canacintra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .content {
            margin-right: 0;
            transition: margin-right 0.3s;
        }
        .sidebar {
            position: fixed;
            right: -250px;
            top: 0;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            transition: right 0.3s;
            z-index: 1000;
        }
        .sidebar.active {
            right: 0;
        }
        .sidebar .nav-link {
            color: white;
            padding: 10px 20px;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
        }
        #sidebarToggle {
            position: fixed;
            right: 10px;
            top: 10px;
            z-index: 1100;
            background-color: #6f42c1;
            border: none;
        }
    </style>
</head>
<body>
    <button id="sidebarToggle" class="btn btn-purple"><i class="fas fa-bars"></i></button>
    <div class="sidebar" id="sidebar">
        <h5 class="text-center">Menú</h5>
        <nav class="nav flex-column">
            <a class="nav-link" href="gestion_usuarios.php">Gestionar Usuarios</a>
            <a class="nav-link" href="reportes.php">Reportes y Gráficas</a>
            <a class="nav-link" href="gestion_comentarios.php">Gestionar Comentarios</a>
        </nav>
    </div>
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="dashboard.php">Canacintra - Admin</a>
            </div>
        </nav>
        <div class="container my-4">
            <h2 class="text-center mb-4">Bienvenido al Panel de Administración</h2>
            <p class="text-center">Selecciona una opción del menú lateral para comenzar.</p>
        </div>
    </div>
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p>© CANACINTRA 2025</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            const content = document.querySelector('.content');
            if (document.getElementById('sidebar').classList.contains('active')) {
                content.style.marginRight = '250px';
            } else {
                content.style.marginRight = '0';
            }
        });
    </script>
</body>
</html>
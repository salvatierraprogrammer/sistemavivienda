<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    // El usuario ha iniciado sesión, obtener su nombre de usuario y rol
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "Usuario Desconocido";
    $userRole = $_SESSION['user_role'];
    
    // Establecer una cookie de sesión
    $session_duration = 30 * 24 * 60 * 60; // Duración de la cookie en segundos (30 días)
    setcookie('session_token', session_id(), time() + $session_duration, '/'); // Asociar la cookie con la sesión actual
} else {
    // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit; // Asegúrate de salir del script después de la redirección
}

// Detectar la página actual
$current_page = basename($_SERVER['PHP_SELF']);
?>
<?php
// session_start();

// Verificar si el usuario ha iniciado sesión
// if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
//     $userRole = $_SESSION['user_role'];
//     $current_page = basename($_SERVER['PHP_SELF']);

//     // Si el usuario es operador y está en una página diferente a 'panel_operador.php', redirigirlo
//     if ($userRole == 1 && $current_page != 'panel_operador.php') {
//         header("Location: panel_operador.php");
//         exit; // Asegúrate de salir del script después de la redirección
//     }
//     // Si el usuario es administrador y está en una página diferente a 'index.php', redirigirlo
//     elseif ($userRole == 2 && $current_page != 'index.php') {
//         header("Location: index.php");
//         exit; // Asegúrate de salir del script después de la redirección
//     }

//     $username = isset($_SESSION['username']) ? $_SESSION['username'] : "Usuario Desconocido";

//     // Establecer una cookie de sesión
//     $session_duration = 30 * 24 * 60 * 60; // Duración de la cookie en segundos (30 días)
//     setcookie('session_token', session_id(), time() + $session_duration, '/');
// } else {
//     // El usuario no ha iniciado sesión, redirigirlo a la página de inicio de sesión
//     header("Location: login.php");
//     exit; // Asegúrate de salir del script después de la redirección
// }
?>



<!DOCTYPE html>
<html lang="es-ar">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Sistema Vivienda</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!--datables CSS básico-->
    <!-- <link rel="stylesheet" type="text/css" href="vendor/datatables/datatables.min.css"/> -->
    <!--datables estilo bootstrap 4 CSS-->  
    <!-- <link rel="stylesheet"  type="text/css" href="vendor/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css">       -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="css/medicacion.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="image/vnd.icon" href="../img/icons.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</head>
<style>


</style>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="panel_operador.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fa-solid fa-house-chimney"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Vivienda <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            
            <!-- Nav Item - Dashboard -->
            <?php if ($userRole == 2): ?>
                <li class="nav-item <?php echo ($current_page === 'index.php') ? 'active' : ''; ?>">
                    <a class="nav-link <?php echo ($current_page === 'index.php') ? 'bg-primary text-white' : ''; ?>" href="index.php">
                        <i class="fas fa-fw fa-user-cog"></i>
                        <span>Administrador</span>
                    </a>
                </li>
                <li class="nav-item <?php echo ($current_page === 'administradores.php') ? 'active' : ''; ?>">
                    <a class="nav-link <?php echo ($current_page === 'administradores.php') ? 'bg-primary text-white' : ''; ?>" href="administradores.php">
                        <i class="fas fa-fw fa-user-cog"></i>
                        <span>Moderadores</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($userRole == 2): ?>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRegistros"
                       aria-expanded="true" aria-controls="collapseRegistros">
                        <i class="fa-solid fa-address-card"></i>
                        <span>Registros</span>
                    </a>
                    <div id="collapseRegistros" class="collapse" aria-labelledby="headingRegistros" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Registros:</h6>
                            <a class="collapse-item <?php echo ($current_page === 'usuarios.php') ? 'active bg-success text-white' : ''; ?>" href="usuarios.php">
                                <i class="fas fa-fw fa-user"></i>
                                Usuarios
                            </a>
                            <a class="collapse-item <?php echo ($current_page === 'op.php') ? 'active bg-success text-white' : ''; ?>" href="op.php">
                                <i class="fas fa-fw fa-users"></i>
                                Operadores
                            </a>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMedicacion"
                       aria-expanded="true" aria-controls="collapseMedicacion">
                        <i class="fas fa-pills"></i>
                        <span>Medicación</span>
                    </a>
                    <div id="collapseMedicacion" class="collapse" aria-labelledby="headingMedicacion" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Registros de medicación:</h6>
                            <a class="collapse-item <?php echo ($current_page === 'medicacion_nom.php') ? 'active bg-success text-white' : ''; ?>" href="medicacion_nom.php">
                                <i class="fas fa-fw fa-user"></i>
                                Medicación
                            </a>
                            <a class="collapse-item <?php echo ($current_page === 'horarios_med.php') ? 'active bg-success text-white' : ''; ?>" href="horarios_med.php">
                                <i class="fas fa-fw fa-clock"></i>
                                Horarios
                            </a>
                            <a class="collapse-item <?php echo ($current_page === 'farmacologico.php') ? 'active bg-success text-white' : ''; ?>" href="farmacologico.php">
                                <i class="fas fa-fw fa-notes-medical"></i>
                                Plan Farmacológico
                            </a>
                        </div>
                    </div>
                </li>
            <?php endif; ?>

            

            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
            <li class="nav-item">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    <span>Salir</span>
                </a>
            </li>
        </ul>
        <!-- End of Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $username; ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" alt="User Profile Image">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Salir
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
           

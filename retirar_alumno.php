<?php
include("conexion.php");

$estudiante = null;
$mensaje = "";

// 1. PROCESAR RETIRO DEL ESTUDIANTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'retirar') {
    $cedula_retirar = $_POST['Cedula_alumno'];
    try {
        $stmt = $conexion->prepare("UPDATE alumnos SET condicion = 'Retirado' WHERE \"Cedula_alumno\" = :cedula");
        $stmt->execute(['cedula' => $cedula_retirar]);
        $mensaje = "<div style='color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Estudiante retirado exitosamente. Se ha actualizado su estado a <strong>Retirado</strong>.</div>";
    } catch (PDOException $e) {
        $mensaje = "<div style='color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Error al retirar estudiante: " . $e->getMessage() . "</div>";
    }
}

// 2. BUSCAR ESTUDIANTE
if (isset($_GET['busqueda']) && !empty(trim($_GET['busqueda']))) {
    $busqueda = "%" . trim($_GET['busqueda']) . "%";
    try {
        $sql_search = "SELECT *, \"Sección\" AS Seccion_clean FROM alumnos 
                       WHERE (\"Cedula_alumno\" LIKE :b1 
                          OR \"Nombres\" LIKE :b2 
                          OR \"Apellidos\" LIKE :b3
                          OR (\"Nombres\" || ' ' || \"Apellidos\") LIKE :b4)
                       AND (condicion IS NULL OR condicion != 'Retirado') 
                       LIMIT 1";
                       
        $stmt = $conexion->prepare($sql_search);
        $stmt->execute(['b1' => $busqueda, 'b2' => $busqueda, 'b3' => $busqueda, 'b4' => $busqueda]);
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$estudiante) {
            $mensaje = "<div style='color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>No se encontró ningún estudiante activo con los datos ingresados.</div>";
        }
    } catch (PDOException $e) {
        $mensaje = "<div style='color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Error en la búsqueda.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retirar Alumno - Sistema de Constancias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html, body { height: 100%; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #F4F7F6; color: #212529; display: flex; overflow: hidden; }
        .sidebar { width: 250px; background-color: #FFFFFF; padding: 15px; box-shadow: 2px 0 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; height: 100vh; box-sizing: border-box; flex-shrink: 0; overflow-y: auto; }
        .sidebar-logo { font-size: 1.0rem; font-weight: bold; color: #007bff; margin-bottom: 20px; text-align: center; }
        .sidebar-logo img { width: 55px; height: 55px; object-fit: contain; display: block; margin: 0 auto 8px auto; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; }
        .sidebar-item { margin-bottom: 6px; }
        .sidebar-link { text-decoration: none; color: #495057; display: flex; align-items: center; padding: 9px 12px; border-radius: 8px; font-size: 0.95rem; transition: background-color 0.2s; }
        .sidebar-link i { margin-right: 10px; width: 20px; text-align: center; }
        .sidebar-link:hover, .sidebar-link.active { background-color: #E6F7FF; color: #0056b3; font-weight: 600; }
        .submenu { list-style: none; padding-left: 25px; margin: 4px 0 8px 0; }
        .main-content { flex: 1; height: 100vh; padding: 20px 30px; background-color: #F4F7F6; overflow-y: auto; box-sizing: border-box; }
        .card { border: 1px solid #dee2e6; padding: 25px; border-radius: 10px; background-color: #FFFFFF; max-width: 550px; box-shadow: 0 2px 5px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 0.9rem; }
        .form-group input { width: 100%; padding: 9px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem; box-sizing: border-box; background-color: #fdfdfd; }
        .btn { padding: 9px 16px; text-decoration: none; border-radius: 6px; display: inline-block; cursor: pointer; border: none; font-weight: 600; }
        .btn-buscar { background-color: #007bff; color: white; margin-top: 8px; }
        .btn-retirar { background-color: #dc3545; color: white; width: 100%; text-align: center; font-size: 1rem; padding: 11px; }
        .btn-retirar:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <nav class="sidebar">
        <div>
            <div class="sidebar-logo"><img src="logo_derecha.png" alt="Logo">Sistema de Constancias</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item"><a href="index.php" class="sidebar-link"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="sidebar-item"><a href="egresados.php" class="sidebar-link"><i class="fas fa-user-graduate"></i> Egresados</a></li>
                <li class="sidebar-item"><a href="retirados.php" class="sidebar-link"><i class="fas fa-user-slash"></i> Retirados</a></li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link active"><i class="fas fa-users"></i> Gestión Alumnos</a>
                    <ul class="submenu">
                        <li><a href="agregar_alumno.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Registrar</a></li>
                        <li><a href="alumnos.php" class="sidebar-link"><i class="fas fa-search"></i> Consultar</a></li>
                        <li><a href="modificar_alumno.php" class="sidebar-link"><i class="fas fa-edit"></i> Modificar</a></li>
                        <li><a href="retirar_alumno.php" class="sidebar-link active"><i class="fas fa-user-minus"></i> Retirar</a></li>
                    </ul>
                </li>
                <li class="sidebar-item"><a href="calendario.php" class="sidebar-link"><i class="fas fa-calendar-alt"></i> Calendario</a></li>
                <li class="sidebar-item"><a href="configuracion.php" class="sidebar-link"><i class="fas fa-cogs"></i> Configuración</a></li>
            </ul>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-item"><a href="procesar.php?action=logout" class="sidebar-link" style="color: #dc3545;" onclick="return confirm('¿Desea cerrar sesión?')"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </nav>
    <main class="main-content">
        <h2><i class="fas fa-user-slash text-danger"></i> Retirar Alumno</h2>
        <p class="text-muted">Busca al estudiante por cédula o nombre completo para efectuar el retiro.</p>
        <hr style="margin-bottom: 20px; border: 0; border-top: 1px solid #dee2e6;">
        <?php if (!empty($mensaje)) echo $mensaje; ?>
        <div class="card" style="max-width: 100%;">
            <form method="GET" action="retirar_alumno.php">
                <div class="form-group" style="margin-bottom: 0;">
                    <label for="busqueda">Buscar Estudiante:</label>
                    <div style="display: flex; gap: 10px; margin-top: 5px;">
                        <input type="text" name="busqueda" id="busqueda" placeholder="Ej. 21916934001" value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>" required>
                        <button type="submit" class="btn btn-buscar"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($estudiante): ?>
            <div class="card">
                <h3>Información del Estudiante</h3>
                <form method="POST" action="retirar_alumno.php">
                    <input type="hidden" name="accion" value="retirar">
                    <div class="form-group"><label>Cédula:</label><input type="text" name="Cedula_alumno" value="<?php echo htmlspecialchars($estudiante['Cedula_alumno'] ?? ''); ?>" readonly></div>
                    <div class="form-group"><label>Nombres:</label><input type="text" value="<?php echo htmlspecialchars($estudiante['Nombres'] ?? ''); ?>" readonly></div>
                    <div class="form-group"><label>Apellidos:</label><input type="text" value="<?php echo htmlspecialchars($estudiante['Apellidos'] ?? ''); ?>" readonly></div>
                    <div class="form-group"><label>Grado y Sección:</label><input type="text" value="<?php echo htmlspecialchars($estudiante['Grado'] . ' - ' . $estudiante['Sección']); ?>" readonly></div>
                    <div class="form-group"><label>Representante:</label><input type="text" value="<?php echo htmlspecialchars($estudiante['Nombre_representante'] ?? ''); ?>" readonly></div>
                    <button type="submit" class="btn btn-retirar" onclick="return confirm('¿Está seguro de retirar a este estudiante?');">
                        <i class="fas fa-trash-alt"></i> Confirmar Retiro
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
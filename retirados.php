<?php
include("conexion.php");
$mensaje = "";

// PROCESAR REINCORPORACIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'reincorporar') {
    $cedula_reincorporar = $_POST['Cedula_alumno'];
    try {
        $stmt = $conexion->prepare("UPDATE alumnos SET condicion = 'Activo' WHERE \"Cedula_alumno\" = :cedula");
        $stmt->execute(['cedula' => $cedula_reincorporar]);
        $mensaje = "<div style='color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>¡Estudiante reincorporado exitosamente!</div>";
    } catch (PDOException $e) {
        $mensaje = "<div style='color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Error: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes Retirados - Sistema de Constancias</title>
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
        .card { background: #FFFFFF; padding: 25px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .table-responsive { width: 100%; overflow-x: auto; border: 1px solid #dee2e6; border-radius: 6px; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 1200px; background-color: #FFFFFF; }
        th, td { border: 1px solid #dee2e6; padding: 11px; text-align: left; white-space: nowrap; }
        th { background-color: #dc3545; color: white; position: sticky; top: 0; z-index: 10; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f3f5; }
        .btn-reincorporar { background-color: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 0.85rem; }
        .btn-reincorporar:hover { background-color: #218838; }
    </style>
</head>
<body>
    <nav class="sidebar">
        <div>
            <div class="sidebar-logo"><img src="logo_derecha.png" alt="Logo">Sistema de Constancias</div>
            <ul class="sidebar-menu">
                <li class="sidebar-item"><a href="index.php" class="sidebar-link"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="sidebar-item"><a href="egresados.php" class="sidebar-link"><i class="fas fa-user-graduate"></i> Egresados</a></li>
                <li class="sidebar-item"><a href="retirados.php" class="sidebar-link active"><i class="fas fa-user-slash"></i> Retirados</a></li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link"><i class="fas fa-users"></i> Gestión Alumnos</a>
                    <ul class="submenu">
                        <li><a href="agregar_alumno.php" class="sidebar-link"><i class="fas fa-plus-circle"></i> Registrar</a></li>
                        <li><a href="alumnos.php" class="sidebar-link"><i class="fas fa-search"></i> Consultar</a></li>
                        <li><a href="modificar_alumno.php" class="sidebar-link"><i class="fas fa-edit"></i> Modificar</a></li>
                        <li><a href="retirar_alumno.php" class="sidebar-link"><i class="fas fa-user-minus"></i> Retirar</a></li>
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
        <div class="card">
            <h2><i class="fas fa-user-slash text-danger"></i> Listado de Estudiantes Retirados</h2>
            <p class="text-muted">Registro de estudiantes retirados que pueden ser reincorporados.</p>
            <hr style="margin-bottom: 15px; border: 0; border-top: 1px solid #eee;">
            <?php if (!empty($mensaje)) echo $mensaje; ?>
            <div class="table-responsive">
                <table>
                    <tr>
                        <th style="text-align: center;">Acción</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Cédula Alumno</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th>Representante</th>
                    </tr>
                    <?php
                    try {
                        $resultado = $conexion->query("SELECT * FROM alumnos WHERE condicion = 'Retirado'");
                        if ($resultado && $resultado->rowCount() > 0) {
                            while($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td style='text-align: center;'>
                                            <form method='POST' action='retirados.php' style='margin: 0;'>
                                                <input type='hidden' name='accion' value='reincorporar'>
                                                <input type='hidden' name='Cedula_alumno' value='" . htmlspecialchars($fila['Cedula_alumno']) . "'>
                                                <button type='submit' class='btn-reincorporar' onclick=\"return confirm('¿Desea reincorporar a este estudiante?');\">
                                                    <i class='fas fa-undo-alt'></i> Reincorporar
                                                </button>
                                            </form>
                                        </td>
                                        <td>" . htmlspecialchars($fila['Nombres'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($fila['Apellidos'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($fila['Cedula_alumno'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($fila['Grado'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($fila['Sección'] ?? '') . "</td>
                                        <td>" . htmlspecialchars($fila['Nombre_representante'] ?? '') . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; color: #6c757d; padding: 20px;'>No hay estudiantes retirados registrados.</td></tr>";
                        }
                    } catch (PDOException $e) {
                        echo "<tr><td colspan='7' style='text-align: center; color: red;'>Error al cargar datos.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
<?php
$database_url = getenv('DATABASE_URL');

if ($database_url) {
    // Conexión para Neon (PostgreSQL) en la nube
    $db = parse_url($database_url);
    $dsn = "pgsql:host=" . $db['host'] . ";port=" . ($db['port'] ?? 5432) . ";dbname=" . ltrim($db['path'], '/');
    try {
        $conexion = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die("Error de conexión a la nube: " . $e->getMessage());
    }
} else {
    // Conexión local (puedes ajustarla si usas PostgreSQL o MySQL localmente)
    $servidor = "localhost";
    $usuario_db = "root";
    $clave_db = "";
    $nombre_bd = "escuela_carnevali";

    try {
        $conexion = new PDO("mysql:host=$servidor;dbname=$nombre_bd;charset=utf8mb4", $usuario_db, $clave_db, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die("Error de conexión local: " . $e->getMessage());
    }
}
?>
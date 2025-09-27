<?php
// Parámetros de conexión
$host = "localhost";     // o la IP de tu servidor
$port = "5432";          // puerto por defecto de PostgreSQL
$dbname = "ClickMatic";     // nombre de la base de datos
$user = "postgres";    // usuario de la BD
$password = "123"; // contraseña del usuario

try {
    // Cadena DSN (Data Source Name)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

    // Crear la conexión con PDO
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Modo de errores: excepciones
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Resultados como array asociativo
    ]);

    echo "✅ Conexión exitosa a PostgreSQL con PDO";

} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
<?php
// servicios.php - controlador: carga la vista y reemplaza el placeholder del navbar
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Cargar las funciones de redirecciones / componentes
require_once __DIR__ . '/../../Recursos/PHP/redirecciones.php';

$viewPath = __DIR__ . '/../HTML/servicios.html';
if (!file_exists($viewPath)) {
    http_response_code(500);
    echo "Error: vista no encontrada: $viewPath";
    exit;
}

// Capturar la salida de la vista
ob_start();
include $viewPath;
$html = ob_get_clean();

// Generar el navbar (si la función existe) y reemplazar el placeholder
$navbar = '';
if (function_exists('loadNavbar')) {
    ob_start();
    loadNavbar();
    $navbar = ob_get_clean();
}

$html = str_replace('<!-- NAVBAR_PLACEHOLDER -->', $navbar, $html);

echo $html;

?>

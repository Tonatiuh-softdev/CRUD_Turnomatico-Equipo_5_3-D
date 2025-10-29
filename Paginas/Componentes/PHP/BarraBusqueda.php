<?php
// Endpoint para búsqueda tipo "autocomplete".
// Devuelve JSON: arreglo de objetos { label: "Texto mostrado", url: "url a navegar" }

require_once __DIR__ . '/../../Recursos/PHP/redirecciones.php';

// Usamos la función centralizada para obtener la conexión
$conn = loadConexion();

header('Content-Type: application/json; charset=utf-8');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
    echo json_encode([]);
    exit;
}

$like = "%" . $q . "%";

// Tablas y columnas que queremos buscar. Agrega más si las tienes.
$searchTargets = [
    'usuarios' => [ 'col' => 'nombre', 'url' => '../PHP/clientes.php?search=' ],
    'servicios' => [ 'col' => 'nombre', 'url' => '../PHP/servicios.php?search=' ],
];

$results = [];

foreach ($searchTargets as $table => $info) {
    $tableEsc = $conn->real_escape_string($table);
    // Verificar que la tabla exista (evita errores si no está en la BD)
    $check = $conn->query("SHOW TABLES LIKE '" . $tableEsc . "'");
    if (!$check || $check->num_rows === 0) continue;

    $col = $info['col'];

    // Preparar la consulta de forma segura
    $sql = "SELECT id, `" . $conn->real_escape_string($col) . "` AS label FROM `" . $tableEsc . "` WHERE `" . $conn->real_escape_string($col) . "` LIKE ? LIMIT 8";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $like);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $results[] = [
                'label' => $row['label'],
                // url base + id como parámetro, cada proyecto puede adaptar esto
                'url' => $info['url'] . urlencode($row['id'])
            ];
        }
        $stmt->close();
    }
}

// Responder JSON
echo json_encode($results, JSON_UNESCAPED_UNICODE);
exit;

?>
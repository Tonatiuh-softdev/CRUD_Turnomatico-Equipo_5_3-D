<?php
require '../PHP/redirecciones.php';
$conn = loadConexion(); // ✅ Crea la conexión

$result = $conn->query("SELECT id, password FROM usuarios");

while ($row = $result->fetch_assoc()) {
    $hashed = password_hash($row['password'], PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE usuarios SET password=? WHERE id=?");
    $update->bind_param("si", $hashed, $row['id']);
    $update->execute();
}



echo "Contraseñas encriptadas correctamente";
?>


<?php
session_start();
include __DIR__ . "/../../Recursos/PHP/conexion.php";

// Si la petición no es POST, enviar al HTML del formulario
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../HTML/login_Cliente.html");
  exit;
}

$mensaje = "";

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email && $password) {
  $sql = "SELECT * FROM usuarios WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($user["rol"] !== "cliente") {
      $mensaje = "⚠️ Solo los clientes pueden iniciar sesión aquí.";
      header("Location: ../HTML/login_Cliente.html?msg=" . urlencode($mensaje));
      exit;
    } elseif (password_verify($password, $user["password"])) {
      $_SESSION["usuario"] = $user["nombre"];
      $_SESSION["rol"] = "cliente";
      header("Location: pantalla_espera.php");
      exit;
    } else {
      $mensaje = "⚠️ Contraseña incorrecta.";
      header("Location: ../HTML/login_Cliente.html?msg=" . urlencode($mensaje));
      exit;
    }
  } else {
    $mensaje = "⚠️ Usuario no encontrado.";
    header("Location: ../HTML/login_Cliente.html?msg=" . urlencode($mensaje));
    exit;
  }
  $stmt->close();
} else {
  $mensaje = "⚠️ Completa todos los campos.";
  header("Location: ../HTML/login_Cliente.html?msg=" . urlencode($mensaje));
  exit;
}


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos</title>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f5f5f5;
    }

    /* Barra superior */
    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #eceae7ff;
        padding: 10px 20px;
        border-bottom: 1px solid #ddd;
    }

    header .logo {
        display: flex;
        align-items: center;
        font-weight: bold;
    }

    header .logo span {
        margin-left: 10px;
        font-size: 14px;
    }

    header .user {
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: bold;
    }

    header .time {
        font-size: 12px;
        color: #666;
        text-align: right;
    }

    /* Layout general */
    .container {
        display: flex;
        height: calc(100vh - 50px);
    }

    /* Sidebar */
    aside {
        width: 220px;
        background: #9cb6d6ff;
        color: #fff;
        padding: 15px 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    aside a {
        display: flex;
        align-items: center;
        padding: 40px;
        border-radius: 5px;
        color: #000000ff;
        text-decoration: none;
        font-size: 14px;
    }

    aside a:hover {
        background: #88a1c3ff;
    }

    /* Sección de empleados */
    main {
        flex: 1;
        padding: 20px;
        background: #fff;
        overflow-y: auto;
    }

    h2 {
        margin-top: 0;
    }

    form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    form input, form button {
        padding: 8px;
        font-size: 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fafafa;
    }

    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    table th {
        background: #747e8bff;
        color: white;
    }

    .btn-eliminar {
        background: red;
        color: white;
        border: none;
        padding: 5px 8px;
        cursor: pointer;
        border-radius: 4px;
    }

    .btn-eliminar:hover {
        background: darkred;
    }
</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="https://img.icons8.com/ios-filled/50/000000/company.png" width="30"/>
        <span>Nombre de<br>nuestra empresa</span>
    </div>
    <div class="user">
        <span>👤 Administrador</span>
        <div class="time">
            01:26 am<br>
            25 de Agosto 2025
        </div>
    </div>
</header>

<div class="container">
<?php
require 'navbar.php';
?>

    <main>
        <h2>👥 Administrar Empleados</h2>

        <!-- Formulario agregar -->
        <form id="formEmpleado">
            <input type="text" id="nombre" placeholder="Nombre" required>
            <input type="text" id="puesto" placeholder="Puesto" required>
            <button type="submit">➕ Agregar</button>
        </form>

        <!-- Tabla empleados -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Puesto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaEmpleados">
                <!-- Los empleados aparecerán aquí -->
            </tbody>
        </table>
    </main>
</div>

<script>
    let empleados = [];
    let id = 1;

    const form = document.getElementById("formEmpleado");
    const tabla = document.getElementById("tablaEmpleados");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const nombre = document.getElementById("nombre").value;
        const puesto = document.getElementById("puesto").value;

        empleados.push({ id: id++, nombre, puesto});
        mostrarEmpleados();

        form.reset();
    });

    function mostrarEmpleados() {
        tabla.innerHTML = "";
        empleados.forEach(emp => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${emp.id}</td>
                <td>${emp.nombre}</td>
                <td>${emp.puesto}</td>
                <td><button class="btn-eliminar" onclick="eliminarEmpleado(${emp.id})">Eliminar</button></td>
            `;
            tabla.appendChild(fila);
        });
    }

    function eliminarEmpleado(id) {
        empleados = empleados.filter(emp => emp.id !== id);
        mostrarEmpleados();
    }
</script>
</body>
</html>

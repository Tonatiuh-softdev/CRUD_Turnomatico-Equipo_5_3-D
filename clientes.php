<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Turnos - Clientes</title>
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

    /* Sección de clientes */
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

    .btn-status {
        background: #2b3d57;
        color: white;
        border: none;
        padding: 5px 8px;
        cursor: pointer;
        border-radius: 4px;
    }

    .btn-status:hover {
        background: #3f5675;
    }
</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
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
        <h2>🔧 Administrar Clientes</h2>

        <!-- Formulario agregar -->
        <form id="formCliente">
            <input type="text" id="nombreCliente" placeholder="Nombre del cliente" required>
            <button type="submit">➕ Agregar Cliente</button>
        </form>

        <!-- Tabla clientes -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaClientes">
                <!-- Los clientes aparecerán aquí -->
            </tbody>
        </table>
    </main>
</div>

<script>
    let clientes = [];
    let id = 1;

    const form = document.getElementById("formCliente");
    const tabla = document.getElementById("tablaClientes");

    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const nombre = document.getElementById("nombreCliente").value;

        clientes.push({ id: id++, nombre, status: "Activo" });
        mostrarClientes();

        form.reset();
    });

    function mostrarClientes() {
        tabla.innerHTML = "";
        clientes.forEach(cli => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${cli.id}</td>
                <td>${cli.nombre}</td>
                <td>
                    <button class="btn-status" onclick="cambiarStatus(${cli.id})">${cli.status}</button>
                </td>
                <td><button class="btn-eliminar" onclick="eliminarCliente(${cli.id})">Eliminar</button></td>
            `;
            tabla.appendChild(fila);
        });
    }

    function eliminarCliente(id) {
        clientes = clientes.filter(cli => cli.id !== id);
        mostrarClientes();
    }

    function cambiarStatus(id) {
        clientes = clientes.map(cli => {
            if (cli.id === id) {
                cli.status = (cli.status === "Activo") ? "Inactivo" : "Activo";
            }
            return cli;
        });
        mostrarClientes();
    }
</script>
</body>
</html>

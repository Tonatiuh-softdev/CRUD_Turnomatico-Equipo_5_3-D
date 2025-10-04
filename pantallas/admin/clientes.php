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
        <img src="../../img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
        <span>ClickMatic</span>
    </div>
    <div class="user">
        <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 20px; height: 20px;">
  <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
</svg> Administrador</span>
        <div class="time">
            01:26 am<br>
            25 de Agosto 2025
        </div>
    </div>
</header>

<div class="container">
   <?php
require '../../elementos/redirecciones.php';
loadNavbar();
?>

    <main>
        <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 22px; height: 22px;">
  <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
</svg>

 Administrar Clientes</h2>

        <!-- Formulario agregar -->
        <form id="formCliente">
            <input type="text" id="nombreCliente" placeholder="Nombre del cliente" required>
            <button type="submit">
 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 18px; height: 18px;">
  <path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM2.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM18.75 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
</svg> Agregar Cliente</button>
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

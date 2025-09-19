
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


    /* Sección de cajas */
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
    .btn-configurar {
    background: #007bff;
    color: white;
    border: none;
    padding: 5px 8px;
    cursor: pointer;
    border-radius: 4px;
}

.btn-configurar:hover {
    background: #0056b3;
}

</style>
</head>
<body>
<header>
    <div class="logo">
        <img src="/img/Captura de pantalla 2025-09-11 115134.png" width="70"/>
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
  <path d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 0 1-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004ZM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 0 1-.921.42Z" />
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v.816a3.836 3.836 0 0 0-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 0 1-.921-.421l-.879-.66a.75.75 0 0 0-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 0 0 1.5 0v-.81a4.124 4.124 0 0 0 1.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 0 0-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 0 0 .933-1.175l-.415-.33a3.836 3.836 0 0 0-1.719-.755V6Z" clip-rule="evenodd" />
</svg>
 Administrar Cajas</h2>

        <!-- Formulario agregar -->
        <form id="formCaja">
            <input type="text" id="numeroCaja" placeholder="Nombre" required>
            <input type="text" id="ubicacion" placeholder="Servicio" required>
            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 18px; height: 18px;">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
</svg> Agregar</button>
        </form>

        <!-- Tabla cajas -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicios</th>
                    <th>Configurar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody id="tablaCajas">
                <!-- Las cajas aparecerán aquí -->
            </tbody>
        </table>
    </main>
</div>

<script>
    // Datos y contadores
    let cajas = [];
    let id = 1;

    const form = document.getElementById("formCaja");
    const tabla = document.getElementById("tablaCajas");

    // Evento para agregar caja
    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const numeroCaja = document.getElementById("numeroCaja").value;
        const ubicacion = document.getElementById("ubicacion").value;

        cajas.push({ id: id++, numeroCaja, ubicacion });
        mostrarCajas();
        form.reset();
    });

    // Mostrar tabla
    function mostrarCajas() {
        tabla.innerHTML = "";
        cajas.forEach(caja => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${caja.id}</td>
                <td>${caja.numeroCaja}</td>
                <td>
                    <button class="btn-configurar" onclick="configurarCaja(${caja.id})">Configurar</button>
                </td>
                <td>
                    <button class="btn-eliminar" onclick="eliminarCaja(${caja.id})">Eliminar</button>
                </td>
            `;
            tabla.appendChild(fila);
        });
    }

    // Configurar/editar caja
    function configurarCaja(id) {
        const caja = cajas.find(c => c.id === id);
        if (!caja) return;

        const nuevoNumero = prompt("Editar Nombre de la Caja:", caja.numeroCaja);
        const nuevoUbicacion = prompt("Editar Servicio:", caja.ubicacion);

        if (nuevoNumero !== null && nuevoUbicacion !== null) {
            caja.numeroCaja = nuevoNumero;
            caja.ubicacion = nuevoUbicacion;
            mostrarCajas();
        }
    }

    // Eliminar caja
    function eliminarCaja(id) {
        cajas = cajas.filter(c => c.id !== id);
        mostrarCajas();
    }
</script>

</body>
</html>

const overlay = document.querySelector('.overlay');
const modal = document.querySelector('.modal');
const cerrar = document.querySelector('.cerrar');

cerrar.addEventListener('click', cerrarModal);
overlay.addEventListener('click', cerrarModal);

// Generar turno visitante
async function abrirModalVisitante() {
    try {
        const response = await fetch("../../Recursos/PHP/generar_turno.php?tipo=visitante");
        const turno = await response.text();
        document.getElementById("turnoModal").textContent = turno;
        document.getElementById("nombreModal").textContent = "Visitante";
        overlay.classList.add('active');
        modal.classList.add('active');
    } catch (error) {
        console.error("Error al generar turno visitante:", error);
    }
}

// Generar turno cliente logueado
async function abrirModalCliente() {
    try {
        const response = await fetch("../../Recursos/PHP/generar_turno.php?tipo=cliente");
        const turno = await response.text();
        document.getElementById("turnoModal").textContent = turno;
        document.getElementById("nombreModal").textContent = "<?php echo htmlspecialchars($nombreCliente); ?>";
        overlay.classList.add('active');
        modal.classList.add('active');

        // 🔹 Destruir sesión al sacar el turno (para que otro cliente pueda iniciar)
        await fetch("./logout_cliente.php");
    } catch (error) {
        console.error("Error al generar turno cliente:", error);
    }
}

function cerrarModal() {
    modal.classList.add('closing');
    overlay.classList.remove('active');
    setTimeout(() => {
        modal.classList.remove('active', 'closing');
    }, 600);
}
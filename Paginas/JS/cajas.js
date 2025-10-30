// Modal agregar
const modal = document.getElementById("modalCaja");
function abrirModal() { modal.style.display = "flex"; }
function cerrarModal() { modal.style.display = "none"; }
window.addEventListener("click", e => { if(e.target === modal) cerrarModal(); });

// Modal editar
const modalEditar = document.getElementById("editarCajaModal");
const formEditar = document.getElementById("formEditarCaja");
function cerrarEditarModal() { modalEditar.style.display = "none"; }
window.addEventListener("click", e => { if(e.target === modalEditar) cerrarEditarModal(); });

// Botones editar con data-*
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const numero = this.dataset.numero;
        const estado = this.dataset.estado;
        const servicio = this.dataset.servicio;

        // Asignar valores al modal
        document.getElementById('editar_id').value = id;
        document.getElementById('editar_numero').value = numero;
        document.getElementById('editar_estado').value = estado;

        const selectServicio = document.getElementById('editar_servicio');
        let found = false;
        for (const opt of selectServicio.options) {
            if (opt.text.trim() === servicio.trim()) {
                opt.selected = true;
                found = true;
                break;
            }
        }
        if (!found) selectServicio.value = "";

        // Mostrar modal
        modalEditar.style.display = 'flex';
    });
});

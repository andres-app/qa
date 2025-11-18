<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="modalLabel">Registro de Módulo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id_modulo" name="id_modulo">

                    <div class="mb-3">
                        <label class="form-label">Nombre del Módulo (*)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                               placeholder="Ej: Registro de Expediente Digital" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Guardar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

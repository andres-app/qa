<!-- Modal de Iteraciones -->
<div id="iter_modal" class="modal fade" tabindex="-1" aria-labelledby="iterLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Encabezado -->
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-semibold" id="iterLabel">Iteraciones del Caso de Prueba</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <!-- Cuerpo -->
            <div class="modal-body">
                <input type="hidden" id="id_caso_iter" name="id_caso_iter">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-semibold">Historial de Iteraciones</h6>
                    <button type="button" id="btn_nueva_iteracion" class="btn btn-sm btn-primary">
                        <i class="bx bx-plus-circle"></i> Nueva Iteración
                    </button>

                </div>

                <div id="timeline_iteraciones" class="border-start border-2 ps-3">
                    <p class="text-muted">No hay iteraciones registradas aún.</p>
                </div>
            </div>
        </div>
    </div>
</div>
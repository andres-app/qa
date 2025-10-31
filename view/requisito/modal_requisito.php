<!-- Modal para el registro y edición de requisitos -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">

        <!-- Formulario de registro / edición -->
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <!-- Encabezado -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel">Registro de Requisito</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body">
                    <input type="hidden" id="id_requisito" name="id_requisito">

                    <!-- Fila 1: Código y Nombre -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código (*)</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ej: RQF-GPR-01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre (*)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre corto o título del requisito" required>
                        </div>
                    </div>

                    <!-- Fila 2: Versión -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="version" class="form-label">Versión</label>
                            <input type="text" class="form-control" id="version" name="version" placeholder="Ej: 1.0">
                        </div>
                    </div>

                    <!-- Fila 3: Descripción -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción del Requisito</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Describa la funcionalidad o detalle del requisito"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pie -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                </div>

            </div>
        </form>
    </div>
</div>

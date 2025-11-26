<!-- Modal para el registro y edición de Documentación -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <!-- Encabezado -->
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white fw-semibold" id="modalLabel">Registro de Documentación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body">
                    <input type="hidden" id="id_documento_mnt" name="id_documentacion">
                    <!-- Nombre -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="nombre" class="form-label">Nombre del Documento (*)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ej: Manual de Usuario" required>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                placeholder="Descripción breve del documento"></textarea>
                        </div>
                    </div>

                    <!-- Fecha de recepción -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_recepcion" class="form-label">Fecha de Recepción (*)</label>
                            <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion"
                                required>
                        </div>

                        <!-- Tipo de documento -->
                        <div class="col-md-6 mb-3">
                            <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                            <select class="form-select" id="tipo_documento" name="tipo_documento">
                                <option value="">Seleccione</option>
                                <option value="Carta">Carta</option>
                                <option value="Documento">Documento</option>
                                <option value="Memorando">Memorando</option>
                                <option value="Resolución">Resolución</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <!-- Estado oculto -->
                    <input type="hidden" id="estado" name="estado" value="1">

                </div>

                <!-- Pie del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x-circle"></i> Cerrar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> Guardar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
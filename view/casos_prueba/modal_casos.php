<!-- Modal para el registro y edición de Casos de Prueba -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <!-- Encabezado -->
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white fw-semibold" id="modalLabel">Registro de Caso de Prueba</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body">
                    <input type="hidden" id="id_caso_prueba" name="id_caso_prueba">

                    <!-- Fila 1: Código y Nombre -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo" class="form-label">Código (*)</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" readonly required>

                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nombre" class="form-label">Nombre del Caso (*)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ej: Validar registro exitoso de expediente" required>
                        </div>
                    </div>

                    <!-- Fila 2: Requerimiento y Tipo de prueba -->
                    <div class="row">
                        <div class="col-md-6 mb-3 position-relative">
                            <label for="buscarRequerimiento" class="form-label">Requerimiento Asociado (*)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bx bx-search"></i></span>
                                <input type="text" id="buscarRequerimiento" class="form-control"
                                    placeholder="Buscar por código...">
                            </div>
                            <ul id="resultadosRequerimiento" class="list-group position-absolute w-100 shadow-sm"
                                style="max-height:200px; overflow-y:auto; z-index:1050; display:none;">
                            </ul>
                            <input type="hidden" id="id_requerimiento" name="id_requerimiento" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tipo_prueba" class="form-label">Tipo de Prueba</label>
                            <select class="form-select" id="tipo_prueba" name="tipo_prueba">
                                <option value="">Seleccione</option>
                                <option value="Funcional">Funcional</option>
                                <option value="No Funcional">No Funcional</option>
                                <option value="Regresión">Regresión</option>
                                <option value="Integración">Integración</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 3: Versión, Especialidad, Elaborado por -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="version" class="form-label">Versión</label>
                            <select class="form-select" id="version" name="version">
                                <option value="">Seleccione</option>
                                <option value="1.0">1.0</option>
                                <option value="2.0">2.0</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="especialidad_id" class="form-label">Especialidad</label>
                            <select class="form-select" id="especialidad_id" name="especialidad_id" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="elaborado_por" class="form-label">Elaborado por</label>
                            <input type="text" class="form-control" id="elaborado_por" name="elaborado_por"
                                value="Equipo de Calidad" readonly>
                        </div>
                    </div>

                    <!-- Fila 4: Descripción -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="descripcion" class="form-label">Descripción del Caso</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                placeholder="Describa los pasos o propósito del caso de prueba"></textarea>
                        </div>
                    </div>

                    <!-- Estado oculto -->
                    <input type="hidden" id="estado_ejecucion" name="estado_ejecucion" value="Pendiente">
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
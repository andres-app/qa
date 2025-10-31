<!-- Modal para el registro y edición de requerimientos -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- modal-xl para una vista amplia -->
        
        <!-- Formulario de registro / edición -->
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <!-- Encabezado -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalLabel">Registro de Requerimiento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body">

                    <!-- Campo oculto: ID del requerimiento -->
                    <input type="hidden" id="id_requerimiento" name="id_requerimiento">

                    <!-- Fila 1: Código y Nombre -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">Código (*)</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ej: RFU-GPR-01" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre (*)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del requerimiento" required>
                        </div>
                    </div>

                    <!-- Fila 2: Tipo y Prioridad -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo" class="form-label">Tipo de Requerimiento</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="">Seleccione</option>
                                <option value="Funcional">Funcional</option>
                                <option value="No Funcional">No Funcional</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="prioridad" class="form-label">Prioridad</label>
                            <select class="form-control" id="prioridad" name="prioridad">
                                <option value="Alta">Alta</option>
                                <option value="Media" selected>Media</option>
                                <option value="Baja">Baja</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 3: Estado de Validación y Versión -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado_validacion" class="form-label">Estado de Validación</label>
                            <select class="form-control" id="estado_validacion" name="estado_validacion">
                                <option value="Pendiente">Pendiente</option>
                                <option value="Aprobado">Aprobado</option>
                                <option value="Observado">Observado</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="version" class="form-label">Versión</label>
                            <input type="text" class="form-control" id="version" name="version" placeholder="Ej: 1.0">
                        </div>
                    </div>

                    <!-- Fila 4: Funcionalidad -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="funcionalidad" class="form-label">Descripción de la Funcionalidad</label>
                            <textarea class="form-control" id="funcionalidad" name="funcionalidad" rows="3" placeholder="Describa la funcionalidad principal del requerimiento"></textarea>
                        </div>
                    </div>

                </div> <!-- Fin del cuerpo -->

                <!-- Pie del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                </div>

            </div> <!-- Fin del modal-content -->
        </form>
    </div>
</div>

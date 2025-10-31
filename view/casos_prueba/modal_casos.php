<!-- Modal para el registro y edición de Casos de Prueba -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <!-- Encabezado -->
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white fw-semibold" id="modalLabel">Registro de Caso de Prueba</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo -->
                <div class="modal-body">
                    <input type="hidden" id="id_caso_prueba" name="id_caso_prueba">

                    <!-- Fila 1: Código y Nombre -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="codigo" class="form-label">Código (*)</label>
                            <input type="text" class="form-control" id="codigo" name="codigo"
                                placeholder="Ej: CP-GPR-01" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="nombre" class="form-label">Nombre del Caso (*)</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                placeholder="Ej: Validar registro exitoso de expediente" required>
                        </div>
                    </div>

                    <!-- Fila 2: Requerimiento asociado y Tipo de prueba -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_requerimiento" class="form-label">Requerimiento Asociado (*)</label>
                            <select class="form-control select2" id="id_requerimiento" name="id_requerimiento" required>
                                <option value="">Seleccione un requerimiento</option>
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tipo_prueba" class="form-label">Tipo de Prueba</label>
                            <select class="form-control" id="tipo_prueba" name="tipo_prueba">
                                <option value="">Seleccione</option>
                                <option value="Funcional">Funcional</option>
                                <option value="No Funcional">No Funcional</option>
                                <option value="Regresión">Regresión</option>
                                <option value="Integración">Integración</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 3: Resultado esperado y versión -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="resultado_esperado" class="form-label">Resultado Esperado</label>
                            <input type="text" class="form-control" id="resultado_esperado" name="resultado_esperado"
                                placeholder="Ej: El sistema muestra mensaje de confirmación.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="version" class="form-label">Versión</label>
                            <input type="text" class="form-control" id="version" name="version" placeholder="Ej: 1.0">
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

                    <!-- Fila 5: Elaborado por y Especialidad -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="elaborado_por" class="form-label">Elaborado Por</label>
                            <input type="text" class="form-control" id="elaborado_por" name="elaborado_por"
                                value="<?php echo $_SESSION['usu_nombre'] ?? ''; ?>" placeholder="Nombre del responsable">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="especialidad_id" class="form-label">Especialidad</label>
                            <select class="form-control" id="especialidad_id" name="especialidad_id">
                                <option value="">Seleccione</option>
                                <option value="1">Laboral</option>
                                <option value="2">Civil</option>
                                <option value="3">Familia</option>
                                <option value="4">Contencioso Administrativo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 6: Estado ejecución y Resultado -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="estado_ejecucion" class="form-label">Estado de Ejecución</label>
                            <select class="form-control" id="estado_ejecucion" name="estado_ejecucion">
                                <option value="">Seleccione</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="En ejecución">En ejecución</option>
                                <option value="Completado">Completado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="resultado" class="form-label">Resultado</label>
                            <select class="form-control" id="resultado" name="resultado">
                                <option value="">Seleccione</option>
                                <option value="Exitoso">Exitoso</option>
                                <option value="Fallido">Fallido</option>
                                <option value="En revisión">En revisión</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 7: Fecha de ejecución y observaciones -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_ejecucion" class="form-label">Fecha de Ejecución</label>
                            <input type="date" class="form-control" id="fecha_ejecucion" name="fecha_ejecucion">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="1"
                                placeholder="Anote observaciones relevantes"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Pie del modal -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                </div>

            </div>
        </form>
    </div>
</div>

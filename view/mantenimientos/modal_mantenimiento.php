<!-- Modal para el registro y edición de mantenimientos -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- modal-xl para hacer el modal más grande -->
        
        <!-- Formulario para el registro o edición de mantenimientos -->
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <!-- Encabezado del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Registro de Mantenimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="modal-body">

                    <!-- Campo oculto para el ID del mantenimiento (se usa en caso de edición) -->
                    <input type="hidden" id="id" name="id">

                    <!-- Fila 1: Vehículo ID y Fecha del mantenimiento -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_id" class="form-label">ID del Vehículo (*)</label>
                            <input class="form-control" type="number" name="vehiculo_id" id="vehiculo_id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_mantenimiento" class="form-label">Fecha de Mantenimiento (*)</label>
                            <input class="form-control" type="date" name="fecha_mantenimiento" id="fecha_mantenimiento" required>
                        </div>
                    </div>

                    <!-- Fila 2: Kilometraje actual y Precio del mantenimiento -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kilometraje_actual" class="form-label">Kilometraje Actual (*)</label>
                            <input class="form-control" type="number" name="kilometraje_actual" id="kilometraje_actual" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="precio" class="form-label">Precio (*)</label>
                            <input class="form-control" type="number" name="precio" id="precio" required>
                        </div>
                    </div>

                    <!-- Fila 3: Fecha del próximo mantenimiento y Kilometraje próximo mantenimiento -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fecha_proximo_mantenimiento" class="form-label">Fecha Próximo Mantenimiento</label>
                            <input class="form-control" type="date" name="fecha_proximo_mantenimiento" id="fecha_proximo_mantenimiento">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="km_proximo_mantenimiento" class="form-label">Kilometraje Próximo Mantenimiento</label>
                            <input class="form-control" type="number" name="km_proximo_mantenimiento" id="km_proximo_mantenimiento">
                        </div>
                    </div>

                    <!-- Fila 4: Detalle del mantenimiento y Observaciones -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="detalle" class="form-label">Detalle</label>
                            <textarea class="form-control" name="detalle" id="detalle"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" id="observaciones"></textarea>
                        </div>
                    </div>

                    <!-- Fila 5: Estado de mantenimiento (Realizado o Pendiente) -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="realizado" class="form-label">Estado del Mantenimiento</label>
                            <select class="form-control" name="realizado" id="realizado">
                                <option value="0">Pendiente</option>
                                <option value="1">Realizado</option>
                            </select>
                        </div>
                    </div>

                </div> <!-- Fin del cuerpo del modal -->

                <!-- Pie del modal (botones de acción) -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

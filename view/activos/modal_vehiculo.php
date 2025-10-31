<!-- Modal para el registro y edición de vehículos -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- modal-xl para hacer el modal más grande -->
        
        <!-- Formulario para el registro o edición de vehículos -->
        <form method="post" id="mnt_form">
            <div class="modal-content">

                <!-- Encabezado del modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Registro de Vehículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="modal-body">

                    <!-- Campo oculto para el ID del vehículo (se usa en caso de edición) -->
                    <input type="hidden" id="vehiculo_id" name="vehiculo_id">

                    <!-- Fila 1: Placa y Marca del vehículo -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_placa" class="form-label">Placa (*)</label>
                            <input class="form-control" type="text" name="vehiculo_placa" id="vehiculo_placa" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_marca" class="form-label">Marca (*)</label>
                            <input class="form-control" type="text" name="vehiculo_marca" id="vehiculo_marca" required>
                        </div>
                    </div>

                    <!-- Fila 2: Modelo y Año del vehículo -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_modelo" class="form-label">Modelo (*)</label>
                            <input class="form-control" type="text" name="vehiculo_modelo" id="vehiculo_modelo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_anio" class="form-label">Año (*)</label>
                            <input class="form-control" type="number" name="vehiculo_anio" id="vehiculo_anio" required>
                        </div>
                    </div>

                    <!-- Fila 3: Color y Motor del vehículo -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_color" class="form-label">Color</label>
                            <input class="form-control" type="text" name="vehiculo_color" id="vehiculo_color">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_motor" class="form-label">Motor</label>
                            <input class="form-control" type="text" name="vehiculo_motor" id="vehiculo_motor">
                        </div>
                    </div>

                    <!-- Fila 4: Tipo de combustible y Tipo de vehículo -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_combustible" class="form-label">Combustible</label>
                            <select class="form-control" name="vehiculo_combustible" id="vehiculo_combustible">
                                <option value="Gasolina">Gasolina</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Gas">Gas</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_tipo" class="form-label">Tipo de Vehículo</label>
                            <select class="form-control" name="vehiculo_tipo" id="vehiculo_tipo">
                            <option value="Camioneta">Camioneta</option>
                                <option value="Sedán">Sedán</option>
                                <option value="Hatchback">Hatchback</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fila 5: Póliza del vehículo y estado (activo/inactivo) -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_poliza" class="form-label">Póliza</label>
                            <input class="form-control" type="text" name="vehiculo_poliza" id="vehiculo_poliza">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vehiculo_estado" class="form-label">Estado</label>
                            <select class="form-control" name="vehiculo_estado" id="vehiculo_estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
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

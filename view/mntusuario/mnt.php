<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" id="mnt_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="usu_id" name="usu_id">

                    <div class="mb-3">
                        <label for="form-label" class="form-label">Nombre y Apellido (*)</label>
                        <input class="form-control" type="text" name="usu_nomape" id="usu_nomape" required>
                    </div>

                    <div class="mb-3">
                        <label for="form-label" class="form-label">Correo Electronico (*)</label>
                        <input class="form-control" type="email" name="usu_correo" id="usu_correo" required>
                    </div>

                    <div class="mb-3">
                        <label for="form-label" class="form-label">Rol (*)</label>
                        <select class="form-select" name="rol_id" id="rol_id" placeholder="Seleccionar" required>
                            <option value="">Seleccionar</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="usu_pass" class="form-label">Contraseña (*)</label>
                        <input type="password" class="form-control" name="usu_pass" id="usu_pass" required>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btnguardar"
                        class="btn btn-primary waves-effect waves-light">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
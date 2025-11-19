<!-- Modal Actividad -->
<div id="mnt_modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <form method="post" id="mnt_form">
      <div class="modal-content">

        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="modalLabel">Nueva Actividad</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" id="id_actividad" name="id_actividad">

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">N° Actividad</label>
              <input type="text" id="id_actividad_visible" class="form-control bg-light border-0 fw-bold" readonly>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Fecha Recepción</label>
              <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Prioridad</label>
              <select class="form-control" id="prioridad" name="prioridad">
                <option value="Alta">Alta</option>
                <option value="Media" selected>Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Colaborador Responsable</label>
              <select class="form-control" id="colaborador_id" name="colaborador_id">
                <option value="<?php echo $_SESSION['usu_id']; ?>">
                  <?php echo $_SESSION['usu_nomape']; ?>
                </option>
              </select>

            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Tipo de Actividad</label>
              <select class="form-control" id="actividad" name="actividad">
                <option value="Revisión">Revisión</option>
                <option value="Elaboración">Elaboración</option>
                <option value="Análisis">Análisis</option>
                <option value="Coordinación">Coordinación</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>

      </div>
    </form>
  </div>
</div>
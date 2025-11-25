<div class="modal fade" id="modal_estado">
  <div class="modal-dialog">
    <form id="form_estado">
      <div class="modal-content">

        <div class="modal-header bg-info text-white">
          <h5 class="modal-title">Actualizar Estado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" id="estado_id" name="id_actividad">

          <label>Nuevo Estado</label>
          <select id="estado" name="estado" class="form-control"></select>

          <div id="observacion_box" class="mt-3" style="display:none;">
            <label>Observación</label>
            <textarea id="observacion" class="form-control"></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-info">Guardar</button>
        </div>

      </div>
    </form>
  </div>
</div>

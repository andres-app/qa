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

          <label>Estado</label>
          <select id="estado" name="estado" class="form-control">
            <option>Pendiente</option>
            <option>En Progreso</option>
            <option>Atendido</option>
            <option>Cerrado</option>
          </select>

          <label class="mt-3">Fecha Inicio</label>
          <input type="datetime-local" id="fecha_inicio" class="form-control">

          <label class="mt-3">Fecha Respuesta</label>
          <input type="datetime-local" id="fecha_respuesta" class="form-control">

          <label class="mt-3">Observación</label>
          <textarea id="observacion" class="form-control" rows="2"></textarea>

        </div>

        <div class="modal-footer">
          <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
          <button type="submit" class="btn btn-info">Guardar</button>
        </div>

      </div>
    </form>
  </div>
</div>

<!-- Modal para el registro y edición de actividades -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form method="post" id="mnt_form">
      <div class="modal-content">

        <!-- 🔹 Encabezado -->
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="modalLabel">Registro de Actividad</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- 🔸 Cuerpo -->
        <div class="modal-body">

          <!-- ID oculto -->
          <input type="hidden" id="id_actividad" name="id_actividad">

          <!-- 🟦 1️⃣ Información Principal -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">N° Actividad (*)</label>
              <input type="text" id="id_actividad_visible"
                     class="form-control bg-light border-0 fw-bold ps-2"
                     readonly>
              <small class="text-muted">Se genera automáticamente.</small>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Fecha Recepción</label>
              <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Fecha Inicio</label>
              <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
            </div>
          </div>

          <!-- 🟫 2️⃣ Colaborador asignado -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="colaborador_id" class="form-label">Colaborador Responsable</label>
              <select class="form-control" id="colaborador_id" name="colaborador_id" required>
                <option value="">Seleccione...</option>
              </select>
              <small class="text-muted">Seleccione al responsable de ejecutar la actividad.</small>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Fecha Respuesta</label>
              <input type="date" class="form-control" id="fecha_respuesta" name="fecha_respuesta">
            </div>
          </div>

          <!-- 🟩 3️⃣ Actividad -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Tipo de Actividad</label>
              <select class="form-control" id="actividad" name="actividad" required>
                <option value="Revisión">Revisión</option>
                <option value="Elaboración">Elaboración</option>
                <option value="Análisis">Análisis</option>
                <option value="Coordinación">Coordinación</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Prioridad</label>
              <select class="form-control" id="prioridad" name="prioridad" required>
                <option value="Alta">Alta</option>
                <option value="Media" selected>Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>
          </div>

          <!-- 🟧 4️⃣ Descripción -->
          <div class="row">
            <div class="col-md-12 mb-3">
              <label for="descripcion" class="form-label">Descripción de la Actividad</label>
              <textarea class="form-control" id="descripcion" name="descripcion"
                        rows="3" placeholder="Describa la tarea, proceso o actividad a realizar."></textarea>
            </div>
          </div>

          <!-- 🟨 5️⃣ Estado y avance -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Estado</label>
              <select class="form-control" id="estado" name="estado">
                <option value="Pendiente">Pendiente</option>
                <option value="En Progreso">En Progreso</option>
                <option value="Atendido">Atendido</option>
                <option value="Cerrado">Cerrado</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Avance (%)</label>
              <input type="number" min="0" max="100" id="avance" name="avance"
                     class="form-control" value="0">
              <small class="text-muted">Debe estar entre 0% y 100%.</small>
            </div>
          </div>

        </div>

        <!-- 🟩 Pie del modal -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>

      </div>
    </form>
  </div>
</div>

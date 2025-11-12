<!-- Modal para el registro y edición de incidencias -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form method="post" id="mnt_form">
      <div class="modal-content">

        <!-- Encabezado -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-white" id="modalLabel">Registro de Incidencia</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Cuerpo -->
        <div class="modal-body">
          <input type="hidden" id="id_incidencia" name="id_incidencia">

          <!-- 🟦 Fila 1: N° Incidencia y Actividad -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="numero_incidencia" class="form-label">N° Incidencia (*)</label>
              <input type="text" class="form-control" id="numero_incidencia" name="numero_incidencia"
                placeholder="Ej: INC-2025-001" required>
            </div>

            <div class="col-md-6 mb-3">
              <label for="actividad" class="form-label">Actividad</label>
              <input type="text" class="form-control" id="actividad" name="actividad"
                placeholder="Ej: Pruebas de validación módulo Audiencias">
            </div>
          </div>

          <!-- 🟩 Fila 2: Documentación y Módulo -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="documentacion" class="form-label">Documentación Asociada</label>
              <input type="text" class="form-control" id="documentacion" name="documentacion"
                placeholder="Ej: Caso de Prueba CP-GRAB-02 o Carta 083-2025-Softplan">
            </div>

            <div class="col-md-6 mb-3">
              <label for="modulo" class="form-label">Módulo del Sistema</label>
              <input type="text" class="form-control" id="modulo" name="modulo"
                placeholder="Ej: Grabaciones Judiciales, Audiencias, Requerimientos">
            </div>
          </div>

          <!-- 🟨 Fila 3: Descripción y Acción Recomendada -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="descripcion" class="form-label">Descripción de la Incidencia o Requerimiento</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                placeholder="Describa brevemente la incidencia detectada durante las pruebas"></textarea>
            </div>

            <div class="col-md-6 mb-3">
              <label for="accion_recomendada" class="form-label">Acción a tomar y/o Recomendación</label>
              <textarea class="form-control" id="accion_recomendada" name="accion_recomendada" rows="3"
                placeholder="Indique la acción sugerida o corrección esperada por desarrollo"></textarea>
            </div>
          </div>

          <!-- 🟧 Fila 4: Fechas -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="fecha_recepcion" class="form-label">Fecha de Recepción</label>
              <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion">
            </div>

            <div class="col-md-4 mb-3">
              <label for="fecha_registro" class="form-label">Fecha de Registro</label>
              <input type="date" class="form-control" id="fecha_registro" name="fecha_registro">
            </div>

            <div class="col-md-4 mb-3">
              <label for="fecha_respuesta" class="form-label">Fecha de Respuesta</label>
              <input type="date" class="form-control" id="fecha_respuesta" name="fecha_respuesta">
            </div>
          </div>

          <!-- 🟪 Fila 5: Prioridad, Tipo de Incidencia y Estado -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="prioridad" class="form-label">Prioridad</label>
              <select class="form-control" id="prioridad" name="prioridad">
                <option value="Alta">Alta</option>
                <option value="Media" selected>Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label for="tipo_incidencia" class="form-label">Tipo de Incidencia</label>
              <select class="form-control" id="tipo_incidencia" name="tipo_incidencia">
                <option value="">Seleccione</option>
                <option value="Funcional">Funcional</option>
                <option value="Interfaz">Interfaz</option>
                <option value="Validación">Validación</option>
                <option value="Integración">Integración</option>
                <option value="Base de Datos">Base de Datos</option>
                <option value="Otro">Otro</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label for="estado_incidencia" class="form-label">Estado de la Incidencia</label>
              <select class="form-control" id="estado_incidencia" name="estado_incidencia">
                <option value="Pendiente" selected>Pendiente</option>
                <option value="En revisión">En revisión</option>
                <option value="Resuelta">Resuelta</option>
                <option value="Cerrada">Cerrada</option>
              </select>
            </div>
          </div>

          <!-- 🟫 Fila 6: Base de Datos, Versión y Analista -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="base_datos" class="form-label">Base de Datos</label>
              <input type="text" class="form-control" id="base_datos" name="base_datos" placeholder="Ej: SAJ_QA, SAJ_PRD">
            </div>

            <div class="col-md-4 mb-3">
              <label for="version_origen" class="form-label">Versión Origen</label>
              <input type="text" class="form-control" id="version_origen" name="version_origen" placeholder="Ej: 1.0.3">
            </div>

            <div class="col-md-4 mb-3">
              <label for="analista" class="form-label">Analista QA</label>
              <input type="hidden" id="analista_id" name="analista_id" value="<?= $_SESSION['usu_id']; ?>">
              <input type="text" class="form-control" id="analista" name="analista"
                value="<?= $_SESSION['usu_nomape']; ?>" readonly>
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

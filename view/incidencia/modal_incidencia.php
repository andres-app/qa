<!-- Modal para el registro y edición de incidencias -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form method="post" id="mnt_form">
      <div class="modal-content">

        <!-- Encabezado -->
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="modalLabel">Registro de Incidencia</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Cuerpo -->
        <div class="modal-body">
          <!-- ID oculto para edición -->
          <input type="hidden" id="id_incidencia" name="id_incidencia">

          <!-- 🟦 Fila 1: N° Incidencia (ID) y Fecha de Registro -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="id_incidencia_visible" class="form-label">N° Incidencia (*)</label>
              <input type="text" class="form-control bg-light border-0 fw-bold ps-2" id="id_incidencia_visible"
                style="font-size: 1rem" readonly>

              <small class="text-muted">El número se genera automáticamente (ID autoincremental).</small>
            </div>

            <div class="col-md-6 mb-3">
              <label for="fecha_registro" class="form-label">Fecha de Registro</label>
              <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" readonly>
            </div>
          </div>

          <!-- 🟩 Fila 2: Documentación Asociada -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="id_documentacion" class="form-label">Documentación Asociada</label>
              <select class="form-control" id="id_documentacion" name="id_documentacion" required>
                <option value="">Seleccione documentación</option>
              </select>
              <small class="text-muted">Seleccione un documento base existente (por ejemplo: Informe, Carta o
                Acta).</small>
            </div>

            <div class="col-md-6 mb-3">
              <label for="fecha_recepcion" class="form-label">Fecha de Recepción</label>
              <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" readonly>
            </div>
          </div>

          <!-- 🟨 Fila 3: Actividad y Módulo del Sistema -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="actividad" class="form-label">Actividad</label>
              <select class="form-control" id="actividad" name="actividad" required>
                <option value="Revisión" selected>Revisión</option>
                <option value="Elaboración">Elaboración</option>
                <option value="Análisis">Análisis</option>
                <option value="Coordinación">Coordinación</option>
              </select>
            </div>


            <div class="col-md-6 mb-3">
              <label for="modulo" class="form-label">Módulo del Sistema</label>
              <select class="form-control" id="modulo" name="modulo" required>
                <option value="">Seleccione</option>
                <option value="EJENP">EJENP</option>
                <option value="Casos de Prueba">Casos de Prueba</option>
                <option value="Grabaciones Judiciales">Grabaciones Judiciales</option>
                <option value="Programación de Audiencias">Programación de Audiencias</option>
                <option value="Actuaciones Judiciales">Actuaciones Judiciales</option>
              </select>
            </div>
          </div>

          <!-- 🟧 Fila 4: Descripción y Acción Recomendada -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="descripcion" class="form-label">Descripción de la Incidencia</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                placeholder="Detalle la incidencia detectada durante las pruebas."></textarea>
            </div>

            <div class="col-md-6 mb-3">
              <label for="accion_recomendada" class="form-label">Acción para tomar y/o Recomendación</label>
              <textarea class="form-control" id="accion_recomendada" name="accion_recomendada" rows="3"
                placeholder="Indique la acción sugerida o corrección esperada por desarrollo."></textarea>
            </div>
          </div>

          <!-- 🟪 Fila 5: Prioridad, Tipo, Estado y Fecha de Respuesta -->
          <div class="row">
            <div class="col-md-3 mb-3">
              <label for="prioridad" class="form-label">Prioridad</label>
              <select class="form-control" id="prioridad" name="prioridad">
                <option value="Alta">Alta</option>
                <option value="Media" selected>Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>

            <div class="col-md-3 mb-3">
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

            <div class="col-md-3 mb-3">
              <label for="estado_incidencia" class="form-label">Estado</label>
              <select class="form-control" id="estado_incidencia" name="estado_incidencia" readonly disabled>
                <option value="Pendiente" selected>Pendiente</option>
              </select>
              <input type="hidden" name="estado_incidencia" value="Pendiente">
            </div>
          </div>

          <!-- 🟫 Fila 6: Base de Datos, Versión y Analista -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="base_datos" class="form-label">Base de Datos</label>
              <input type="text" class="form-control" id="base_datos" name="base_datos"
                placeholder="Ej: SAJ_QA, SAJ_PRD">
            </div>

            <div class="col-md-4 mb-3">
              <label for="version_origen" class="form-label">Versión Origen</label>
              <input type="text" class="form-control" id="version_origen" name="version_origen" placeholder="Ej: 1.0.4">
            </div>

            <div class="col-md-4 mb-3">
              <label for="analista" class="form-label">Analista QA</label>
              <input type="hidden" id="analista_id" name="analista_id" value="<?= $_SESSION['usu_id']; ?>">
              <input type="text" class="form-control" id="analista" value="<?= $_SESSION['usu_nomape']; ?>" readonly>
            </div>
          </div>
        </div>

        <!-- Pie del modal -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </form>
  </div>
</div>
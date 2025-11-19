<!-- Modal para el registro y edición de incidencias -->
<div id="mnt_modal" class="modal fade" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form method="post" id="mnt_form">
      <div class="modal-content">

        <!-- 🔹 Encabezado -->
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="modalLabel">Registro de Incidencia</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- 🔸 Cuerpo -->
        <div class="modal-body">
          <input type="hidden" id="id_incidencia" name="id_incidencia">

          <!-- 🔽 Opciones Avanzadas (ligero y casual) -->
          <!-- 🔽 Opciones Avanzadas (claro, sin fondo, con indicador) -->
          <div class="mb-3">

            <button class="btn btn-link text-decoration-none p-0 fw-semibold" type="button" data-bs-toggle="collapse"
              data-bs-target="#opcionesAvanzadas">

              <i class="bi bi-gear-fill me-1"></i>
              <span class="text-primary">Opciones avanzadas</span>
              <i class="bi bi-chevron-down ms-1"></i>

              <small class="text-muted ms-2">(....)</small>
            </button>

            <div class="collapse mt-2" id="opcionesAvanzadas">

              <div class="p-2">

                <div class="row">

                  <!-- Fecha Registro -->
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Fecha de Registro</label>
                    <input type="date" class="form-control" id="fecha_registro" name="fecha_registro" readonly>
                  </div>

                  <!-- Estado -->
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Estado</label>
                    <input type="text" class="form-control" value="Pendiente" readonly>
                    <input type="hidden" name="estado_incidencia" id="estado_incidencia" value="Pendiente">
                  </div>

                  <!-- Base de Datos -->
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Base de Datos</label>
                    <input type="text" class="form-control" id="base_datos" name="base_datos"
                      placeholder="Ej: SAJ_QA, SAJ_PRD">
                  </div>

                  <!-- Analista -->
                  <div class="col-md-4 mb-3">
                    <label class="form-label">Analista QA Responsable</label>
                    <input type="hidden" id="analista_id" name="analista_id" value="<?= $_SESSION['usu_id']; ?>">
                    <input type="text" class="form-control" id="analista" value="<?= $_SESSION['usu_nomape']; ?>"
                      readonly>
                  </div>

                </div>

              </div>
            </div>
          </div>



          <!-- 🟦 1️⃣ Identificación -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">ID (*)</label>
              <input type="text" class="form-control bg-light border-0 fw-bold" id="id_incidencia_visible" readonly>
              <small class="text-muted">Se genera automáticamente</small>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Módulo del Sistema</label>
              <select class="form-control" id="id_modulo" name="id_modulo" required>
                <option value="">Seleccione...</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Versión del Sistema</label>
              <select class="form-control" id="version_origen" name="version_origen">
                <option value="1.0" selected>1.0</option>
                <option value="2.0">2.0</option>
                <option value="3.0">3.0</option>
              </select>
            </div>
          </div>

          <!-- 🟩 2️⃣ Documentación asociada -->
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Documentación Asociada</label>
              <select class="form-control" id="id_documentacion" name="id_documentacion" required>
                <option value="">Seleccione documentación</option>
              </select>
              <small class="text-muted">Ej: Informe, Carta, Acta</small>
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label">N° Incidencia</label>
              <input type="text" class="form-control bg-light border-0 fw-bold" id="correlativo_doc"
                name="correlativo_doc" readonly>
              <small class="text-muted">Correlativo interno según la documentación</small>
            </div>


            <div class="col-md-3 mb-3">
              <label class="form-label">Fecha de Recepción</label>
              <input type="date" class="form-control" id="fecha_recepcion" name="fecha_recepcion" readonly>
            </div>
          </div>

          <!-- 🟨 3️⃣ Actividad y tipo -->
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Actividad</label>
              <select class="form-control" id="actividad" name="actividad" required>
                <option value="Revision" selected>Revisión</option>
                <option value="Elaboración">Elaboración</option>
                <option value="Análisis">Análisis</option>
                <option value="Coordinación">Coordinación</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label">Tipo de Incidencia</label>
              <select class="form-control" id="tipo_incidencia" name="tipo_incidencia">
                <option value="">Seleccione...</option>
                <option value="Documentacion">Documentación</option>
                <option value="Funcional">Funcional</option>
                <option value="Validación">Validación</option>
                <option value="Integración">Integración</option>
                <option value="Base de Datos">Base de Datos</option>
                <option value="Otro">Otro</option>
              </select>
            </div>
            <!-- 🟪 4️⃣ Clasificación -->
            <div class="col-md-4 mb-3">
              <label class="form-label">Prioridad</label>
              <select class="form-control" id="prioridad" name="prioridad">
                <option value="Alta">Alta</option>
                <option value="Media" selected>Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>
          </div>





          <!-- 🟧 6️⃣ Descripción -->
          <div class="row">
            <div class="col-md-12 mb-3">
              <label class="form-label">Descripción de la Incidencia</label>
              <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                placeholder="Detalle la incidencia detectada durante las pruebas."></textarea>
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Acción Recomendada / Correctiva</label>
              <textarea class="form-control" id="accion_recomendada" name="accion_recomendada" rows="3"
                placeholder="Indique la acción sugerida o corrección esperada por desarrollo."></textarea>
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Imágenes adjuntas (pegar con CTRL+V)</label>

              <div id="dropZone" class="dropzone-placeholder">
                <div class="dz-icon">
                  <i class="bx bx-image-alt"></i>
                </div>
                <p class="dz-text">Arrastra o pega tus imágenes aquí (CTRL + V)</p>

                <div id="preview" class="dz-preview"></div>

                <!-- Aquí guardaremos temporalmente las imágenes en base64 -->
                <input type="hidden" id="imagenes_base64" name="imagenes_base64">
              </div>
            </div>
          </div>
        </div>



        <!-- 🟩 Pie -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>

      </div>
    </form>
  </div>
</div>
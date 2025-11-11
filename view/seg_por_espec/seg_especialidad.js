$(document).ready(function () {

  console.log("📢 [DEBUG] Iniciando módulo seg_especialidad.js...");

  // --- Cargar combo de especialidades
  $.ajax({
    url: "../../controller/seg_especialidad.php?op=combo_especialidad",
    type: "POST",
    dataType: "json",
    success: function (especialidades) {
      console.log("✅ [DEBUG] Especialidades recibidas:", especialidades);

      $("#filtro_especialidad").empty().append('<option value="">Todas</option>');

      if (Array.isArray(especialidades) && especialidades.length > 0) {
        especialidades.forEach(e => {
          $("#filtro_especialidad").append(
            `<option value="${e.id_especialidad}">${e.nombre}</option>`
          );
        });
      } else {
        console.warn("⚠️ [DEBUG] No se recibieron especialidades válidas");
      }

      // ============================
      // ✅ NUEVO BLOQUE: aplicar filtro desde la URL
      // ============================
      const params = new URLSearchParams(window.location.search);
      const especialidad = params.get("especialidad"); // <-- 'Laboral', 'Civil', etc.

      if (especialidad) {
        console.log(`🎯 [DEBUG] Filtro automático detectado: ${especialidad}`);

        // Buscar en el select la opción cuyo texto coincida
        $("#filtro_especialidad option").filter(function () {
          return $(this).text().trim().toLowerCase() === especialidad.toLowerCase();
        }).prop("selected", true);

        // Disparar evento de cambio para recargar la tabla
        $("#filtro_especialidad").trigger("change");
      }
    },
    error: function (xhr) {
      console.error("❌ [DEBUG] Error al cargar especialidades:", xhr.responseText);
    }
  });

  // --- Inicializar DataTable
  console.log("📢 [DEBUG] Inicializando DataTable...");
  const tabla = $("#casos_table").DataTable({
    ajax: {
      url: "../../controller/seg_especialidad.php?op=listar",
      type: "POST",
      dataType: "json",
      data: function (d) {
        d.id_especialidad = $("#filtro_especialidad").val();
        d.estado = $("#filtro_estado").val();
        console.log("📤 [DEBUG] Filtros enviados:", d);
      },
      dataSrc: function (json) {
        console.log("📥 [DEBUG] Respuesta del servidor:", json);
        if (json && json.aaData) return json.aaData;
        console.warn("⚠️ [DEBUG] No hay datos o estructura incorrecta");
        return [];
      },
      error: function (xhr, status, error) {
        console.error("❌ [DEBUG] Error en AJAX listar:", error, xhr.responseText);
      }
    },
    columns: [
      { data: "especialidad", title: "Especialidad" },
      { data: "codigo_requerimiento", title: "Código Req." },
      {
        data: "nombre_requerimiento",
        title: "Requerimiento",
        render: function (data) {
          if (!data) return "";
          const textoCorto = data.length > 20 ? data.substring(0, 20) + "…" : data;
          return `<span title="${data.replace(/"/g, '&quot;')}">${textoCorto}</span>`;
        }
      },
      { data: "codigo_caso", title: "Código Caso" },
      {
        data: "nombre_caso",
        title: "Nombre Caso",
        render: function (data) {
          if (!data) return "";
          const textoCorto = data.length > 20 ? data.substring(0, 20) + "…" : data;
          return `<span title="${data.replace(/"/g, '&quot;')}">${textoCorto}</span>`;
        }
      },
      { data: "version", title: "Versión" },
      { data: "estado_badge", title: "Estado", orderable: false, searchable: false },
      { data: "responsable", title: "Responsable" },
      { data: "fecha_registro", title: "Fecha Registro" }
    ],
    responsive: false,
    scrollX: true,
    language: { url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json" },
    dom: "Bfrtip",
    buttons: ["excelHtml5", "pdfHtml5", "csvHtml5", "copyHtml5"]
  });

  // --- Recargar tabla al cambiar filtros
  $("#filtro_especialidad, #filtro_estado").on("change", function () {
    console.log("🔄 [DEBUG] Filtros cambiaron, recargando DataTable...");
    tabla.ajax.reload();
  });
});

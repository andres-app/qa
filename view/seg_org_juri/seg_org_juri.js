$(document).ready(function () {

  console.log("📢 [DEBUG] Iniciando módulo seg_org_juri.js...");

  // --- Cargar combo de órganos
  $.ajax({
    url: "../../controller/seg_org_juri.php?op=combo_organo",
    type: "POST",
    dataType: "json",
    success: function (organos) {
      console.log("✅ [DEBUG] Órganos recibidos:", organos);

      $("#filtro_organo").empty().append('<option value="">Todos</option>');

      if (Array.isArray(organos) && organos.length > 0) {
        organos.forEach(o => {
          $("#filtro_organo").append(
            `<option value="${o.id_organo}">${o.organo_jurisdiccional || o.nombre}</option>`
          );
        });
      } else {
        console.warn("⚠️ [DEBUG] No se recibieron órganos válidos");
      }

      // ✅ Luego de cargar el combo, aplicar el filtro si viene por GET
      const params = new URLSearchParams(window.location.search);
      const id_organo = params.get("id_organo");
      const nombre = params.get("nombre");

      if (id_organo) {
        $("#filtro_organo").val(id_organo).trigger("change");
        console.log(`🎯 [DEBUG] Filtro aplicado automáticamente: ${id_organo} (${nombre})`);
      }
    },
    error: function (xhr) {
      console.error("❌ [DEBUG] Error al cargar órganos:", xhr.responseText);
    }
  });


  // --- Inicializar DataTable
  console.log("📢 [DEBUG] Inicializando DataTable...");
  const tabla = $("#requisito_table").DataTable({
    ajax: {
      url: "../../controller/seg_org_juri.php?op=listar",
      type: "POST",
      dataType: "json",
      data: function (d) {
        d.id_organo = $("#filtro_organo").val();
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
      { data: "organo_jurisdiccional", title: "Órgano Jurisdiccional" },
      { data: "codigo_requerimiento",  title: "Código Req." },
      {
        data: "nombre_requerimiento",
        title: "Requerimiento",
        render: function (data, type, row) {
          if (!data) return "";
          const textoCorto = data.length > 10 ? data.substring(0, 20) + "…" : data;
          return `<span title="${data.replace(/"/g, '&quot;')}">${textoCorto}</span>`;
        }
      },
      { data: "codigo_caso",  title: "Código Caso" },
      {
        data: "nombre_caso",
        title: "Nombre Caso",
        render: function (data, type, row) {
          if (!data) return "";
          const textoCorto = data.length > 10 ? data.substring(0, 20) + "…" : data;
          return `<span title="${data.replace(/"/g, '&quot;')}">${textoCorto}</span>`;
        }
      },
      { data: "version",      title: "Versión" },
      { data: "estado_badge", title: "Estado", orderable: false, searchable: false },
      { data: "responsable",  title: "Responsable" },
      { data: "fecha_registro", title: "Fecha Registro" }
    ],
    responsive: false, // 🔒 No colapsa en desktop
    scrollX: true,     // 🔁 Scroll horizontal si hay muchas columnas
    language: { url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json" },
    dom: "Bfrtip",
    buttons: ["excelHtml5", "pdfHtml5", "csvHtml5", "copyHtml5"]
  });
  
  

  // --- Recargar tabla al cambiar filtros
  $("#filtro_organo, #filtro_estado").on("change", function () {
    console.log("🔄 [DEBUG] Filtros cambiaron, recargando DataTable...");
    tabla.ajax.reload();
  });
});

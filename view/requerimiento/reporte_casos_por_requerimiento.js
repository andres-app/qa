$(document).ready(function() {
    let tabla = $("#reporte_casos_req_table").DataTable({
        aProcessing: true,
        aServerSide: true,
        dom: "Bfrtip",
        buttons: ["copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
        ajax: {
            url: "../../controller/reporte.php?op=reporte_casos_por_requerimiento",
            type: "GET",
            dataType: "json",
            error: function() {
                Swal.fire("Error", "No se pudo cargar el análisis de casos", "error");
            }
        },
        columns: [
            { 
                data: null,
                render: function (data) {
                    // 🔹 Combina código + descripción
                    const textoCompleto = `${data.codigo_requerimiento} ${data.nombre_requerimiento}`;

                    // 🔹 Límite de palabras
                    const limitePalabras = 20;
                    const palabras = textoCompleto.split(" ");

                    // 🔹 Texto recortado
                    const textoCorto = palabras.length > limitePalabras 
                        ? palabras.slice(0, limitePalabras).join(" ") + "…" 
                        : textoCompleto;

                    // 🔹 Tooltip al pasar el mouse
                    return `<span title="${textoCompleto}">${textoCorto}</span>`;
                }
            },
            { 
                data: "total_casos",
                className: "text-center fw-semibold"
            }
        ],
        order: [[1, "desc"]],
        responsive: false,   // evita colapso
        autoWidth: false,
        scrollX: true,
        iDisplayLength: 25,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
        }
    });

    // 🔄 Botón refrescar
    $("#btnRefrescar").on("click", function() {
        tabla.ajax.reload(null, false);
    });
});

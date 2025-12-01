$(document).ready(function () {

    cargarCronogramas();

    $("#selectCronograma").on("change", function () {
        cargarActividades();
    });

    $("#vista").on("change", function () {
        cargarActividades();
    });

});

/* ========================
   Cargar lista cronogramas
======================== */
function cargarCronogramas() {
    $.get("../../controller/cronograma.php?op=listar_cronogramas", function (data) {
        data = JSON.parse(data);

        let html = `<option value="">Seleccione...</option>`;
        data.forEach(c => {
            html += `<option value="${c.idcronograma}">${c.nombre}</option>`;
        });

        $("#selectCronograma").html(html);
    });
}

/* ========================
   Cargar actividades
======================== */
function cargarActividades() {
    let id = $("#selectCronograma").val();
    let vista = $("#vista").val();

    if (id === "") {
        $("#contenedorTabla").html("<div class='alert alert-info'>Seleccione un cronograma.</div>");
        return;
    }

    $.post("../../controller/cronograma.php?op=listar_actividades",
        { idcronograma: id },
        function (data) {
            let actividades = JSON.parse(data);
            generarTabla(actividades, vista);
        }
    );
}

/* ========================
   Generar tabla Gantt 
======================== */
function generarTabla(actividades, vista) {

    if (actividades.length === 0) {
        $("#contenedorTabla").html("<div class='alert alert-warning'>No hay actividades registradas.</div>");
        return;
    }

    // Obtener rango de fechas mínimo y máximo
    let fechas = actividades.flatMap(a => [a.fecha_inicio_prev, a.fecha_fin_prev]);
    let min = fechas.sort()[0];
    let max = fechas.sort().reverse()[0];

    min = new Date(min);
    max = new Date(max);

    let html = `<table class="table table-bordered table-sm gantt">`;

    /* === Encabezado === */
    html += `<thead><tr><th>Actividad</th><th>Responsable</th>`;

    if (vista === "mes") {
        let f = new Date(min);
        while (f <= max) {
            html += `<th>${f.toLocaleString("es-ES",{month:"short"})} ${f.getFullYear()}</th>`;
            f.setMonth(f.getMonth() + 1);
        }
    } else {
        let f = new Date(min);
        while (f <= max) {
            html += `<th>${f.getDate()}/${f.getMonth()+1}</th>`;
            f.setDate(f.getDate() + 1);
        }
    }

    html += `</tr></thead><tbody>`;

    /* === Filas === */
    actividades.forEach(a => {

        html += `<tr>
                    <td style="padding-left:${a.nivel * 20}px">
                        ${a.nombre}
                    </td>
                    <td>${a.responsable ?? ""}</td>`;

        let fi = new Date(a.fecha_inicio_prev);
        let ff = new Date(a.fecha_fin_prev);

        let cursor = new Date(min);

        while (cursor <= max) {

            if (cursor >= fi && cursor <= ff) {
                html += `<td style="background:${a.color};"></td>`;
            } else {
                html += `<td></td>`;
            }

            if (vista === "mes") cursor.setMonth(cursor.getMonth() + 1);
            else cursor.setDate(cursor.getDate() + 1);
        }

        html += `</tr>`;
    });

    html += `</tbody></table>`;

    $("#contenedorTabla").html(html);
}

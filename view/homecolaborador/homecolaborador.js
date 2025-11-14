// =======================
//  FUNCIONES AUXILIARES
// =======================
function gradient(ctx, color1, color2) {
  const grad = ctx.createLinearGradient(0, 0, 0, 300);
  grad.addColorStop(0, color1);
  grad.addColorStop(1, color2);
  return grad;
}

// Esperar DOM listo
document.addEventListener("DOMContentLoaded", () => {

  // ==============================
  //  DEBUG opcional
  // ==============================
  console.log("✅ analisisData:", analisisData);
  console.log("✅ dataOrgano:", dataOrgano);
  console.log("✅ dataEspecialidad:", dataEspecialidad);

  // ==============================
  //  GRÁFICO: ÓRGANO JURISDICCIONAL
  // ==============================
  const ctxOrg = document.getElementById("chartCasosOrgano")?.getContext("2d");
  if (ctxOrg && dataOrgano?.labels?.length > 0) {
    const chartOrg = new Chart(ctxOrg, {
      type: "pie",
      data: {
        labels: dataOrgano.labels,
        datasets: [{
          data: dataOrgano.valores,
          backgroundColor: [
            "rgba(96,165,250,0.8)",
            "rgba(147,197,253,0.8)",
            "rgba(191,219,254,0.8)",
            "rgba(219,234,254,0.8)",
            "rgba(59,130,246,0.8)",
            "rgba(37,99,235,0.8)"
          ],
          borderColor: "#fff",
          borderWidth: 2
        }]
      },
      options: {
        onClick: (evt, activeEls) => {
          if (!activeEls.length) return;

          const index = activeEls[0].index;
          const id_organo = dataOrgano.ids[index];
          const nombre = dataOrgano.labels[index];

          // ✅ Redirigir al módulo seg_org_juri con parámetros GET
          const url = `../seg_org_juri/index.php?id_organo=${id_organo}&nombre=${encodeURIComponent(nombre)}`;
          window.location.href = url;
        },

        plugins: {
          legend: { position: "bottom" },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const value = ctx.raw;
                const total = ctx.chart._metasets[0].total;
                const pct = ((value / total) * 100).toFixed(1);
                return `${ctx.label}: ${value} (${pct}%)`;
              }
            }
          }
        }
      }
    });
  }



  // ==============================
  //  MODAL DETALLE DE ÓRGANO
  // ==============================
  function mostrarModalDetalle(nombre, data) {
    $("#modalDetalleOrgano .modal-title").text(`Detalle de Casos - ${nombre}`);

    $("#tablaDetalleOrgano").DataTable({
      destroy: true,
      data: data,
      columns: [
        { data: "codigo_requerimiento", title: "Código Req." },
        { data: "nombre_requerimiento", title: "Requerimiento" },
        { data: "codigo_caso", title: "Código Caso" },
        { data: "nombre_caso", title: "Nombre Caso" },
        { data: "version", title: "Versión" },
        {
          data: "estado_ejecucion",
          title: "Estado",
          render: function (data) {
            const colores = {
              "Pendiente": "badge bg-warning text-dark",
              "Observado": "badge bg-danger",
              "Completado": "badge bg-success"
            };
            return `<span class="${colores[data] || 'badge bg-secondary'}">${data}</span>`;
          }
        },
        { data: "usuario_registro", title: "Registrado por" },
        { data: "fecha_registro", title: "Fecha" }
      ],
      language: { url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json" },
      dom: "Bfrtip",
      buttons: ["excelHtml5", "pdfHtml5", "csvHtml5", "copyHtml5"]
    });

    $("#modalDetalleOrgano").modal("show");
  }


  // ==============================
  //  GRÁFICO: ESPECIALIDAD
  // ==============================
  const ctxEsp = document.getElementById("chartEspecialidad")?.getContext("2d");
  if (ctxEsp && dataEspecialidad?.labels?.length > 0) {
    const comp = [...dataEspecialidad.completado];
    const obs = [...dataEspecialidad.observado];
    const pen = [...dataEspecialidad.pendiente];

    new Chart(ctxEsp, {
      type: "bar",
      data: {
        labels: dataEspecialidad.labels,
        datasets: [
          { // 🟧 Pendiente (al fondo)
            label: "Pendiente",
            data: pen,
            backgroundColor: "rgba(249,115,22,0.9)", // naranja fuerte
            borderColor: "rgba(249,115,22,1)",
            borderWidth: 1,
            minBarLength: 3,
            order: 1
          },
          { // 🔴 Observado (encima)
            label: "Observado",
            data: obs,
            backgroundColor: "rgba(239,68,68,0.9)", // rojo vivo
            borderColor: "rgba(239,68,68,1)",
            borderWidth: 1,
            minBarLength: 3,
            order: 2
          },
          { // 🟩 Completado (arriba del todo)
            label: "Completado",
            data: comp,
            backgroundColor: "rgba(34,197,94,0.9)", // verde brillante
            borderColor: "rgba(34,197,94,1)",
            borderWidth: 1,
            minBarLength: 3,
            order: 3
          }
        ]
      },
      options: {
        responsive: true,
        interaction: { mode: "index", intersect: false },
        plugins: {
          legend: { position: "bottom" },
          tooltip: {
            callbacks: {
              label: (ctx) => {
                const i = ctx.dataIndex;
                const total = comp[i] + obs[i] + pen[i];
                const v = ctx.raw ?? 0;
                const pct = total ? ((v / total) * 100).toFixed(2) : "0.00";
                return `${ctx.dataset.label}: ${v} (${pct}%)`;
              }
            }
          }
        },
        scales: {
          x: { stacked: true },
          y: { stacked: true, beginAtZero: true }
        }
      }
    });
  }




  // ==============================
  //  GRÁFICO: LÍNEA DE TIEMPO
  // ==============================
  const ctxLinea = document.getElementById("chartLineaTiempo")?.getContext("2d");
  if (ctxLinea) {
    new Chart(ctxLinea, {
      type: "line",
      data: {
        labels: ["1-Jul", "1-Ago", "1-Sep", "1-Oct", "1-Nov", "1-Dic"],
        datasets: [{
          label: "Requerimientos",
          data: [10, 25, 40, 55, 75, 100],
          borderColor: "#60a5fa",
          backgroundColor: "rgba(96,165,250,0.2)",
          fill: true,
          tension: 0.4
        }]
      },
      options: { plugins: { legend: { display: false } } }
    });
  }

  // ==============================
  //  GRÁFICO: AVANCE POR REQUERIMIENTO
  // ==============================
  const ctxAvance = document.getElementById("chartAvance")?.getContext("2d");
  if (ctxAvance) {
    new Chart(ctxAvance, {
      type: "bar",
      data: {
        labels: ["Startvi", "Consultas", "Registro"],
        datasets: [
          { label: "Completado", data: [5, 8, 6], backgroundColor: gradient(ctxAvance, "#93c5fd", "#bfdbfe") },
          { label: "Observado", data: [10, 7, 8], backgroundColor: gradient(ctxAvance, "#a5c8dd", "#dbeafe") },
          { label: "Pendiente", data: [3, 5, 2], backgroundColor: gradient(ctxAvance, "#d1d5db", "#e5e7eb") }
        ]
      },
      options: {
        plugins: { legend: { position: "bottom" } },
        scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
      }
    });
  }

  // ==============================
  //  GRÁFICO: ANÁLISIS FUNCIONALIDAD
  // ==============================
  const ctxFuncCanvas = document.getElementById("chartAnalisisFuncionalidad");
  if (ctxFuncCanvas && analisisData && analisisData.length > 0) {

    const ctxFunc = ctxFuncCanvas.getContext("2d");

    const organos = [...new Set(analisisData.map(r => r.organo_jurisdiccional))];
    const funcionalidades = [...new Set(analisisData.map(r => r.funcionalidad))];
    const colores = ["#60a5fa", "#34d399", "#fbbf24", "#f87171", "#a78bfa"];

    const datasets = funcionalidades.map((func, i) => ({
      label: func,
      data: organos.map(o => {
        const match = analisisData.find(r => r.organo_jurisdiccional === o && r.funcionalidad === func);
        return match ? match.total_requerimientos : 0;
      }),
      backgroundColor: colores[i % colores.length],
      borderRadius: 6,
      barPercentage: 0.6
    }));

    new Chart(ctxFunc, {
      type: "bar",
      data: { labels: organos, datasets },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: "bottom" },
          title: {
            display: true,
            text: "Requerimientos por Órgano Jurisdiccional y Funcionalidad",
            color: "#1f2937",
            font: { size: 14, weight: "600" }
          }
        },
        scales: {
          x: { ticks: { color: "#6b7280" }, grid: { display: false } },
          y: { beginAtZero: true, ticks: { color: "#6b7280" }, grid: { color: "#f3f4f6" } }
        }
      }
    });
  } else {
    console.warn("⚠️ No hay datos para el gráfico de análisis funcionalidad");
  }


  // ===============================================
// 🔌 Plugin para mostrar los valores encima de los puntos
// ===============================================
const mostrarNumerosLinea = {
  id: "mostrarNumerosLinea",
  afterDatasetsDraw(chart) {
    const { ctx } = chart;
    const dataset = chart.data.datasets[0];
    const meta = chart.getDatasetMeta(0);

    ctx.save();
    ctx.font = "bold 13px sans-serif";
    ctx.fillStyle = "#1E3A8A"; // color del texto
    ctx.textAlign = "center";
    ctx.textBaseline = "bottom";

    meta.data.forEach((point, index) => {
      const value = dataset.data[index];
      if (value !== undefined && value !== null) {
        ctx.fillText(value, point.x, point.y - 8); // posición del número
      }
    });

    ctx.restore();
  }
};

/// ======================================================
// 🔌 Plugin para mostrar valores
// ======================================================
const mostrarValores = {
  id: "mostrarValores",
  afterDatasetsDraw(chart) {
    const { ctx } = chart;

    chart.data.datasets.forEach((dataset, i) => {
      const meta = chart.getDatasetMeta(i);
      meta.data.forEach((bar, index) => {
        const value = dataset.data[index];
        ctx.save();
        ctx.font = "12px sans-serif";
        ctx.fillStyle = "#000";
        ctx.textAlign = "left";
        ctx.fillText(value, bar.x + 10, bar.y + 4);
        ctx.restore();
      });
    });
  }
};

// ======================================================
//  📊 GRÁFICO: INCIDENCIAS POR DOCUMENTACIÓN (BARRAS HORIZONTALES)
// ======================================================
if (document.getElementById("chartDocumento")) {
  new Chart(document.getElementById("chartDocumento"), {
    type: "bar",
    data: {
      labels: docLabels,
      datasets: [{
        label: "Incidencias",
        data: docData,
        backgroundColor: "rgba(59,130,246,0.7)",
        borderColor: "#3B82F6",
        borderWidth: 1,
        borderRadius: 8
      }]
    },
    options: {
      indexAxis: "y",
      responsive: true,
      plugins: {
        legend: { display: false },
        mostrarValores: {} // <-- activar el plugin
      },
      scales: {
        x: { beginAtZero: true },
        y: {
          ticks: {
            autoSkip: false,
            maxRotation: 0,
            minRotation: 0
          }
        }
      }
    },
    plugins: [mostrarValores]  // <-- registrar plugin
  });
}



  // ======================================================
// 🔌 Plugin para mostrar valores en Doughnut
// ======================================================
const doughnutLabels = {
  id: "doughnutLabels",
  afterDatasetsDraw(chart) {
    const { ctx, data } = chart;

    chart.getDatasetMeta(0).data.forEach((arc, index) => {
      const valor = data.datasets[0].data[index];
      const pos = arc.tooltipPosition();

      ctx.save();
      ctx.font = "12px sans-serif";
      ctx.fillStyle = "#000";
      ctx.textAlign = "center";
      ctx.textBaseline = "middle";
      ctx.fillText(valor, pos.x, pos.y);
      ctx.restore();
    });
  }
};

// ======================================================
//  📊 GRÁFICO: INCIDENCIAS POR MÓDULO (DOUGHNUT)
// ======================================================
if (document.getElementById("chartModulo")) {
  new Chart(document.getElementById("chartModulo"), {
    type: "doughnut",
    data: {
      labels: modLabels,
      datasets: [{
        data: modData,
        backgroundColor: [
          "#3B82F6", "#10B981", "#F59E0B",
          "#EF4444", "#8B5CF6", "#14B8A6"
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: "bottom" },
        doughnutLabels: {} // activar plugin
      }
    },
    plugins: [doughnutLabels] // registrar plugin
  });
}



// ======================================================
//  📊 GRÁFICO: INCIDENCIAS POR MES (LINE CHART) — VERSIÓN PRO
// ======================================================
if (document.getElementById("chartMes")) {

  const ctxMes = document.getElementById("chartMes").getContext("2d");

  new Chart(ctxMes, {
    type: "line",
    data: {
      labels: mesLabelsBonitos,
      datasets: [{
        label: "Incidencias",
        data: mesData,
        borderColor: "#2563EB",
        borderWidth: 3,
        tension: 0.35,
        fill: true,
        backgroundColor: "rgba(37, 99, 235, 0.12)",
        pointRadius: 6,
        pointHoverRadius: 9,
        pointBackgroundColor: "#1E40AF",
        pointBorderColor: "#1E3A8A",
        pointBorderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { 
          position: "top",
          labels: {
            boxWidth: 20,
            padding: 15
          }
        },
        tooltip: {
          backgroundColor: "#1E3A8A",
          titleColor: "#fff",
          bodyColor: "#fff",
          padding: 10,
          cornerRadius: 6
        }
      },
      scales: {
        x: {
          ticks: {
            color: "#444",
            padding: 8
          },
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: "#666",
            stepSize: 10
          },
          grid: {
            color: "rgba(0,0,0,0.07)",
            borderDash: [4, 4]
          }
        }
      }
    },
    plugins: [mostrarNumerosLinea]  // 👈 AQUÍ ACTIVAMOS LOS NÚMEROS
  });
}




});


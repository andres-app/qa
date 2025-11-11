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
      new Chart(ctxEsp, {
        type: "bar",
        data: {
          labels: dataEspecialidad.labels,
          datasets: [
            { label: "Completado", data: dataEspecialidad.completado, backgroundColor: "rgba(96,165,250,0.8)" },
            { label: "Observado", data: dataEspecialidad.observado, backgroundColor: "rgba(147,197,253,0.8)" },
            { label: "Pendiente", data: dataEspecialidad.pendiente, backgroundColor: "rgba(219,234,254,0.9)" }
          ]
        },
        options: {
          plugins: { legend: { position: "bottom" } },
          scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } },
          responsive: true,
    
          // 🧭 NUEVA OPCIÓN DE CLIC PARA REDIRIGIR
          onClick: (evt, activeEls) => {
            if (!activeEls.length) return; // si no se clickeó ninguna barra
            const chart = activeEls[0].element.$context.chart;
            const index = activeEls[0].index;
            const especialidad = chart.data.labels[index];
    
            // Redirigir al módulo seg_por_espec con la especialidad seleccionada
            const url = `../seg_por_espec/index.php?especialidad=${encodeURIComponent(especialidad)}`;
            console.log("🔗 Redirigiendo a:", url);
            window.location.href = url;
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
  });
  
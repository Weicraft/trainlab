// Configurar worker de PDF.js
pdfjsLib.GlobalWorkerOptions.workerSrc = "pdfjs/pdf.worker.js";

let pdfDoc = null;
let pageNum = 1;
let pageCount = 0;
const canvas = document.getElementById("pdf-canvas");
const ctx = canvas.getContext("2d");
const contador = document.getElementById("contador");
const completado = document.getElementById("completado");
const fecha = new Date();
const fechaLocal = fecha.getFullYear() + '-' +
                   String(fecha.getMonth()+1).padStart(2,'0') + '-' +
                   String(fecha.getDate()).padStart(2,'0') + ' ' +
                   String(fecha.getHours()).padStart(2,'0') + ':' +
                   String(fecha.getMinutes()).padStart(2,'0') + ':' +
                   String(fecha.getSeconds()).padStart(2,'0');
let yaRegistradoInicio = false;

// Cargar PDF
pdfjsLib.getDocument(PDF_URL).promise.then((doc) => {
  pdfDoc = doc;
  pageCount = pdfDoc.numPages;
  contador.textContent = `${pageNum} / ${pageCount}`;
  renderPage(pageNum);
});

function renderPage(num) {
  pdfDoc.getPage(num).then((page) => {
    const viewport = page.getViewport({ scale: 1.5 });
    canvas.height = viewport.height;
    canvas.width = viewport.width;
    const renderContext = { canvasContext: ctx, viewport: viewport };

    page.render(renderContext).promise.then(() => {
      contador.textContent = `${pageNum} / ${pageCount}`;

      // Mostrar/ocultar botón "Finalizar"
      if (pageNum === pageCount) {
        document.getElementById("finalizar").style.display = "inline-block";
      } else {
        document.getElementById("finalizar").style.display = "none";
      }
    });
  });
}

// 👇 Interacciones: registrar inicio en el primer clic
document.getElementById("next").addEventListener("click", () => {
  if (!yaRegistradoInicio) {
    fetch("../backend/registrar_inicio.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_particip: PARTICIPANTE_ID,
        id_content: CONTENIDO_ID,
        fecha_local: fechaLocal
      }),
    });
    yaRegistradoInicio = true;
  }

  if (pageNum < pageCount) {
    pageNum++;
    renderPage(pageNum);
  }
});

document.getElementById("prev").addEventListener("click", () => {
  if (!yaRegistradoInicio) {
    fetch("../backend/registrar_inicio.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_particip: PARTICIPANTE_ID,
        id_content: CONTENIDO_ID,
        fecha_local: fechaLocal
      }),
    });
    yaRegistradoInicio = true;
  }

  if (pageNum > 1) {
    pageNum--;
    renderPage(pageNum);
  }
});

// Botón Finalizar
document.getElementById("finalizar").addEventListener("click", () => {
  completado.style.display = "block";
  fetch("../backend/registrar_fin.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      id_particip: PARTICIPANTE_ID,
      id_content: CONTENIDO_ID,
      fecha_local: fechaLocal
    }),
  });
});

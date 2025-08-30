<canvas id="pdf-canvas"></canvas>
<script src="pdfjs/pdf.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = "pdfjs/pdf.worker.js";

const url = "presentaciones/curso2_presentacion5.pdf"; // prueba con un PDF real

pdfjsLib.getDocument(url).promise.then(doc => {
  doc.getPage(1).then(page => {
    const viewport = page.getViewport({ scale: 1.5 });
    const canvas = document.getElementById("pdf-canvas");
    const ctx = canvas.getContext("2d");
    canvas.height = viewport.height;
    canvas.width = viewport.width;
    page.render({ canvasContext: ctx, viewport: viewport });
  });
});
</script>

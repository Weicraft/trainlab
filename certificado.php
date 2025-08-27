<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Certificado de Aprobación</title>
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
      <?php include 'templates/cssindex.php' ?>
</head>

<body>
  <div class="certificate">
    <div class="header">
      <div class="logo">
        <img src="build/img/logo.png" alt="logo">
      </div>
      <div class="org">
        <div class="name">NiBel Gestión y Consultoría E.I.R.L.</div>
      </div>
    </div>

    <div class="title">
      <h1>Certificado de Aprobación</h1>
      <p class="subtitle">Tras haber participado en el curso ofrecido por nuestra organización:</p>
      <p class="course-name">Cálculo de Punto de Equilibrio</p>
    </div>

    <div class="recipient">
      <div class="label">Otorgado a</div>
      <div class="name">Weimar Muro Almeida</div>
    </div>

    <div class="body-text">
      <p>Por haber concluido satisfactoriamente el curso con compromiso, dedicación y desempeño. En mérito a ello, se expide el presente certificado como reconocimiento oficial de su esfuerzo, logro y excelencia académica.</p>
    </div>

    <div class="meta">
      <div class="left">
        <div class="small">Fecha de emisión</div>
        <div>25 de agosto de 2025</div>

        <div class="small">Firma autorizada</div>
        <div class="sig">
          <img src="build/img/firma.png" alt="firma">
        </div>

        <div class="certifier-name">Dr. Juan Pérez</div>
        <div class="certifier-role">Director Académico</div>
      </div>

      <div class="center-seal">
        <img src="build/img/medalla.png" alt="">
      </div>

      <div class="right">
        <div class="small">Código de certificado</div>
        <div>NB-2025-0001</div>
        <div id="qrcode"></div>
        <div class="small">Validez</div>
        <div>Un(01) año</div>
      </div>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="build/js/certificado.js"></script>
</body>

</html>

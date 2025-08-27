<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Certificado de Aprobación</title>
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f7f6f3;
      --paper: #ffffff;
      --accent: #b08b4f;
      /* dorado suave */
      --muted: #6b6b6b;
      --title: #1b1b1b;
    }

    html,
    body {
      height: 100%;
    }

    body {
      margin: 0;
      font-family: 'Montserrat', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
      background: linear-gradient(180deg, var(--bg) 0%, #efeeee 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px;
    }

    .certificate {
      width: 794px;
      /* tamaño aproximado A4 vertical 210x297mm scaled */
      max-width: calc(100% - 64px);
      background: var(--paper);
      padding: 48px 64px;
      box-shadow: 0 12px 30px rgba(20, 20, 20, 0.12);
      border-radius: 8px;
      border: 6px solid transparent;
      position: relative;
      box-sizing: border-box;
    }

    .certificate::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      border-radius: 6px;
      padding: 6px;
      background: linear-gradient(135deg, rgba(176, 139, 79, 0.12), rgba(176, 139, 79, 0.02));
      pointer-events: none;
    }

    .header {
      display: flex;
      gap: 24px;
      align-items: center;
      margin-bottom: 18px;
    }

    .logo {
      height: 40px;
      /* altura fija */
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      border-radius: 8px;
      /* opcional, para esquinas redondeadas */
      background: #fff;
      /* fondo blanco si lo quieres */
      border: none;
      /* quitar borde plomo */
    }

    .logo img {
      height: 40px;
      /* altura invariable */
      width: auto;
      /* ancho proporcional según la imagen */
      display: block;
    }


    .org {
      display: flex;
      flex-direction: column;
    }

    .org .name {
      font-family: 'Merriweather', serif;
      font-size: 22px;
      font-weight: 700;
      color: var(--title);
      letter-spacing: 0.4px;
    }

    .org .tag {
      color: var(--muted);
      font-size: 13px;
      margin-top: 6px;
    }

    .title {
      text-align: center;
      margin: 28px 0 6px 0;
    }

    .title h1 {
      margin-top: 80px;
      margin-bottom: 40px;
      font-family: 'Merriweather', serif;
      font-size: 32px;
      color: var(--accent);
      letter-spacing: 1px;
    }

    .title p {
      margin: 6px 0 0 0;
      color: var(--muted);
    }

    .recipient {
      margin-top: 28px;
      text-align: center;
    }

    .recipient .label {
      color: var(--muted);
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 2px;
    }

    .recipient .name {
      margin-top: 10px;
      font-family: 'Merriweather', serif;
      font-size: 28px;
      color: var(--title);
      font-weight: 700;
    }

    .body-text {
      margin: 28px auto 0 auto;
      max-width: 600px;
      text-align: center;
      color: var(--muted);
      line-height: 1.5;
      font-size: 15px;
    }

    .meta {
      margin-top: 48px;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      gap: 20px;
    }

    .meta .left,
    .meta .right {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .meta .sig {
      margin-top: 30px;
      height: 64px;
      display: block;
      width: 220px;
      border-bottom: 1px solid #ddd;
    }

    .meta .small {
      color: var(--muted);
      font-size: 13px;
    }

    .seal {
      width: 100px;
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 999px;
      border: 6px solid rgba(176, 139, 79, 0.15);
      box-shadow: 0 6px 18px rgba(176, 139, 79, 0.06);
      flex: 0 0 100px;
      background: linear-gradient(180deg, #fff, #fff);
    }

    .seal .inner {
      font-family: 'Merriweather', serif;
      font-weight: 700;
      color: var(--accent);
      font-size: 12px;
      text-align: center;
    }

    /* Responsive */
    @media (max-width:800px) {
      .certificate {
        padding: 24px
      }

      .header {
        gap: 12px
      }

      .logo {
        width: 72px;
        height: 72px;
        flex: 0 0 72px
      }

      .title h1 {
        font-size: 24px
      }

      .recipient .name {
        font-size: 20px
      }
    }

    /* Print rules for A4 vertical */
    @media print {
      body {
        background: transparent
      }

      .certificate {
        box-shadow: none;
        margin: 0;
        border-radius: 0;
        border: 0;
        width: 100%;
        height: 100%;
        padding: 24mm
      }
    }
  </style>
</head>

<body>
  <div class="certificate">
    <div class="header">
      <div class="logo">
        <!-- Logo: reemplaza la src por el logo de tu empresa -->
        <img src="build/img/logo.png" alt="logo" style="height:40px; width:auto; display:block;">
      </div>
      <div class="org">
        <div class="name">NiBel Gestión y Consultoría E.I.R.L.</div>
      </div>
    </div>

    <div class="title">
      <h1>Certificado de Aprobación</h1>
      <p style="text-align:center; color:var(--muted); font-family:'Montserrat', sans-serif; font-size:15px; margin:6px 0 0 0; line-height:1.5;">
        Tras haber participado en el curso ofrecido por nuestra organización:
      </p>
      <p style="font-family:'Merriweather', serif; font-weight:700; font-size:20px; color:#b08b4f; margin-top:12px; text-align:center; letter-spacing:0.5px; font-style:italic;">
        Cálculo de Punto de Equilibrio
      </p>
    </div>

    <div class="recipient">
      <div class="label">Otorgado a</div>
      <div class="name">Weimar Muro Almeida</div>
    </div>

    <div class="body-text">
      <p style="text-align:center; font-family:'Montserrat', sans-serif; font-size:15px; color:var(--muted); line-height:1.6; max-width:600px; margin:28px auto 0 auto;">
        Por haber concluido satisfactoriamente el curso con compromiso, dedicación y desempeño. En mérito a ello, se expide el presente certificado como reconocimiento oficial de su esfuerzo, logro y excelencia académica.
      </p>
    </div>

    <div class="meta">
      <div class="left">
        <div class="small">Fecha de emisión</div>
        <div>25 de agosto de 2025</div>
        <div style="height:12px"></div>
        <div class="small">Firma autorizada</div>
        <div class="sig" style="position:relative; display:flex; align-items:center; justify-content:center;">
          <!-- Imagen de la firma encima de la raya -->
          <img src="build/img/firma.png" alt="firma" style="position:absolute; top:-28px; width:160px; height:auto;">
        </div>
        <!-- Nombre y cargo de la persona que certifica -->
        <div style="margin-top:40px; text-align:center; font-family:'Montserrat', sans-serif; font-size:14px; font-weight:600; color:var(--title);">
          Dr. Juan Pérez
        </div>
        <div style="text-align:center; font-family:'Montserrat', sans-serif; font-size:13px; color:var(--muted);">
          Director Académico
        </div>
      </div>


      <div>
        <div class="inner"><img src="build/img/medalla.png" width="110px" height="130px" alt=""></div>
      </div>

      <div class="right">
        <div class="small">Código de certificado</div>
        <div>NB-2025-0001</div>
        <div id="qrcode" style="margin-top:12px; display:flex; justify-content:center;"></div>
        <div style="height:12px"></div>
        <div class="small">Validez</div>
        <div>Un(01) año</div>
      </div>
    </div>


  </div>
  <!-- Incluye la librería QRCode.js antes del cierre del body -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    // Genera el QR con el contenido que quieras (ej. código de certificado)
    new QRCode(document.getElementById("qrcode"), {
      text: "https://www.tusitio.com", // aquí va la URL
      width: 100,
      height: 100,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H
    });
  </script>
</body>

</html>
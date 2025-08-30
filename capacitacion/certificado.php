<?php
require '../includes/funciones.php';
require '../includes/config/database.php';
require '../clases/cls.php';

$auth = estaAutenticado();
$db = conectarDB();

if (!$auth) {
  header('location: centro_capacitacion.php');
}
$id_particip = $_SESSION['particip'];
$id_curso = $_POST['id_curso'];
$indice = $_POST['indice'];

PARTICIPANTES::setDB($db);
EMPRESA::setDB($db);
CURSOS::setDB($db);
ASIGNACIONES::setDB($db);
CERTIFICADOS::setDB($db);
$participante = PARTICIPANTES::listarParticipanteId($id_particip);
$curso = CURSOS::listarCursoId($id_curso);
$asignacion = ASIGNACIONES::listarAsignacion($id_particip, $id_curso);
$empresa = EMPRESA::listarEmpresa();
$ultimoCert = CERTIFICADOS::listarUltimoCertificado() ?? NULL;
$estado_aprob = $asignacion->estado_aprob;
$nombre_particip = $participante->nombre_particip;
$apellidos_particip = $participante->apellidos_particip;
$titulo_curso = $curso->titulo_curso;
$validez = $curso->validez_cert;
$urlQr = 'localhost/trainlab/vercert.php?particip=' . $id_particip . '&id_curso=' . $id_curso;
$nombre_empresa = $empresa->nombre_empresa;
$certificador = $empresa->certificador;
$cargo_certificador = $empresa->cargo_certificador;
$fechaBD = $asignacion->fecha_fin;
$prefijo = $empresa->prefijo;
$anioActual = date("Y");
if ($ultimoCert) {
  $ultimoIdCert = $ultimoCert->id_cert;
} else {
  $ultimoIdCert = '0';
}
$id_cert = $ultimoIdCert + 1;
$cod_cert = $prefijo . '-' . $anioActual . '-' . $id_cert;

// Establecemos locale en español (importante para nombres de meses)
setlocale(LC_TIME, "es_ES.UTF-8", "es_ES", "spanish");
// Creamos objeto DateTime
$fechaObj = new DateTime($fechaBD);
// Capturamos en la variable $fecha con el formato deseado
$fecha = strftime("%e de %B de %Y", $fechaObj->getTimestamp());
// Trim para limpiar espacios iniciales (en %e agrega espacio antes del día)
$fecha_emision = trim($fecha);


//Verificar si el Certificado existe
$certExiste = CERTIFICADOS::listarCertificadoPartCurso($id_particip, $id_curso) ?? null;

if (!$certExiste) {

  //Graba el certificado en BD
  $nuevoCert = new CERTIFICADOS();
  $nuevoCert->crear($id_particip, $id_curso, $cod_cert, $fechaBD);
}

?>



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
      padding: 32px;
    }

    .certificate {
      margin: auto auto auto 0;
      width: 794px;
      /* tamaño aproximado A4 vertical 210x297mm scaled */
      background: var(--paper);
      padding: 30px 64px;
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
      align-items: flex-start;
      margin-bottom: 18px;
    }

    .flex-column {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
    }

    .flex {
      display: flex;
      align-items: center;
      justify-content: flex-start;
    }

    .mensaje-error {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .mensaje-error p {
      color: red;
      font-weight: 700;
    }

    .logo {
      height: 40px;
      /* altura fija */
      margin-right: 0.5rem;
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
      font-size: 15px;
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
      margin: 18px 0 6px 0;
    }

    .title h1 {
      margin-top: 40px;
      margin-bottom: 40px;
      font-family: 'Merriweather', serif;
      font-size: 40px;
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

    .boton {
      margin: 0 34.4rem 1rem 0;
      background-color: #f4f4f6;
      color: #c13936;
      border: 1px solid #f4f4f6;
      padding: 0.7rem 1.4rem;
      /* Se ajusta al contenido */
      font-size: 0.9rem;
      font-weight: 600;
      border-radius: 0.6rem;
      font-family: 'Merriweather', serif;
      cursor: pointer;
      transition: all 0.25s ease;
      box-shadow: 0 3px 8px rgba(26, 115, 232, 0.08);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      /* Espacio entre "+" y el texto */
    }

    .boton:hover {
      background-color: #eae8ef;
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }

    .boton:active {
      transform: scale(0.96);
      box-shadow: 0 2px 6px rgba(26, 115, 232, 0.2);
    }

    .boton:focus {
      outline: none;
      border-color: #bbaadd;
    }

    .btn-salir {
      margin-bottom: 1rem;
      background-color: #d6f0ff;
      /* celeste pastel claro */
      border: 1px solid #9ed9ff;
      border-radius: 0.5rem;
      padding: 0.7rem 1.4rem;
      font-family: 'Merriweather', serif;
      font-size: 0.9rem;
      font-weight: 700;
      color: #006699;
      /* celeste oscuro para contraste */
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
    }

    .btn-salir svg {
      width: 16px;
      height: 16px;
      fill: #006699;
    }

    .btn-salir:hover {
      background-color: #bde7ff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .btn-salir:active {
      transform: scale(0.97);
    }

    .btn-salir:focus {
      outline: none;
      border-color: #7ecbff;
    }
  </style>
</head>

<body>
  <div class="flex-column">
    <div class="flex">
      <button class="boton" id="view-pdf">Descargar PDF</button>
      <button class="btn-salir" onclick="window.close()">Cerrar</button>
    </div>
    <?php if ($estado_aprob == 'A') { ?>
      <div class="certificate">
        <div class="header">
          <div class="flex">
            <div class="logo">
              <!-- Logo: reemplaza la src por el logo de tu empresa -->
              <img src="../build/img/logo.png" alt="logo" style="height:40px; width:auto; display:block;">
            </div>
            <div class="org">
              <div class="name"><?php echo $nombre_empresa; ?></div>
            </div>
          </div>
        </div>

        <div class="title">
          <h1>Certificado de Participación</h1>
          <p style="text-align:center; color:var(--muted); font-family:'Montserrat', sans-serif; font-size:15px; margin:6px 0 0 0; line-height:1.5;">
            Tras haber participado en el curso ofrecido por nuestra organización:
          </p>
          <p style="font-family:'Merriweather', serif; font-weight:700; font-size:20px; color:#b08b4f; margin-top:12px; text-align:center; letter-spacing:0.5px; font-style:italic;">
            <?php echo $titulo_curso; ?>
          </p>
        </div>

        <div class="recipient">
          <div class="label">Otorgado a</div>
          <div class="name"><?php echo $nombre_particip . ' ' . $apellidos_particip; ?></div>
        </div>

        <div class="body-text">
          <p style="text-align:center; font-family:'Montserrat', sans-serif; font-size:15px; color:var(--muted); line-height:1.6; max-width:600px; margin:28px auto 0 auto;">
            Por haber concluido satisfactoriamente el curso con compromiso, dedicación y desempeño. En mérito a ello, se expide el presente certificado como reconocimiento oficial de su esfuerzo, logro y excelencia académica.
          </p>
        </div>

        <div class="meta">
          <div class="left">
            <div class="small">Fecha de emisión</div>
            <div><?php echo $fecha_emision; ?></div>
            <div style="height:12px"></div>
            <div class="small">Firma autorizada</div>
            <div class="sig" style="position:relative; display:flex; align-items:center; justify-content:center;">
              <!-- Imagen de la firma encima de la raya -->
              <img src="../build/img/firma.png" alt="firma" style="position:absolute; top:-28px; width:160px; height:auto;">
            </div>
            <!-- Nombre y cargo de la persona que certifica -->
            <div style="margin-top:40px; text-align:center; font-family:'Montserrat', sans-serif; font-size:14px; font-weight:600; color:var(--title);">
              <?php echo $certificador; ?>
            </div>
            <div style="text-align:center; font-family:'Montserrat', sans-serif; font-size:13px; color:var(--muted);">
              <?php echo $cargo_certificador; ?>
            </div>
          </div>


          <div>
            <div class="inner"><img src="../build/img/medalla.png" width="110px" height="130px" alt=""></div>
          </div>

          <div class="right">
            <div class="small">Código de certificado</div>
            <div><?php echo $cod_cert; ?></div>
            <div id="qrcode" style="margin-top:12px; display:flex; justify-content:center;"></div>
            <div style="height:12px"></div>
            <div class="small">Validez</div>
            <div><?php echo $validez; ?></div>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <div class="mensaje-error">
        <p>EL PARTICIPANTE AÚN NO HA APROBADO EL CURSO</p>
      </div>
    <?php } ?>
  </div>
  <!-- Incluye la librería QRCode.js antes del cierre del body -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
    // Genera el QR con el contenido que quieras (ej. código de certificado)
    new QRCode(document.getElementById("qrcode"), {
      text: "<?php echo $urlQr; ?>", // aquí va la URL
      width: 100,
      height: 100,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H
    });
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <script>
    const btn = document.getElementById("view-pdf");

    btn.addEventListener("click", () => {
      const element = document.querySelector(".certificate");

      const opt = {
        margin: 0,
        filename: 'certificado.pdf',
        image: {
          type: 'jpeg',
          quality: 1
        },
        html2canvas: {
          scale: 2,
          logging: true,
          dpi: 300,
          letterRendering: true
        },
        jsPDF: {
          unit: 'mm', // aquí usamos pixeles
          format: [214, 219], // ancho x alto en px
          orientation: 'portrait'
        }
      };

      html2pdf().set(opt).from(element).outputPdf('bloburl').then(function(pdfUrl) {
        window.open(pdfUrl, '_blank');
      });
    });
  </script>


</body>

</html>
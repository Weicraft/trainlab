// Genera el QR con el contenido deseado
new QRCode(document.getElementById("qrcode"), {
  text: "https://www.tusitio.com", // reemplaza con tu URL
  width: 100,
  height: 100,
  colorDark: "#000000",
  colorLight: "#ffffff",
  correctLevel: QRCode.CorrectLevel.H
});

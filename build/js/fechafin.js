const fecha = new Date();
const fechaLocal =
  fecha.getFullYear() +
  "-" +
  String(fecha.getMonth() + 1).padStart(2, "0") +
  "-" +
  String(fecha.getDate()).padStart(2, "0") +
  " " +
  String(fecha.getHours()).padStart(2, "0") +
  ":" +
  String(fecha.getMinutes()).padStart(2, "0") +
  ":" +
  String(fecha.getSeconds()).padStart(2, "0");

// Colocamos la fecha en el input oculto
document.getElementById("fecha_equipo").value = fechaLocal;
document.getElementById("autoForm").submit();

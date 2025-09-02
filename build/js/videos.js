const video = document.getElementById("video");
const playBtn = document.getElementById("playBtn");
const progreso = document.getElementById("progreso");
const completado = document.getElementById("completado");
const fullscreenBtn = document.getElementById("fullscreenBtn");
const fecha = new Date();
const fechaLocal = fecha.getFullYear() + '-' +
                   String(fecha.getMonth()+1).padStart(2,'0') + '-' +
                   String(fecha.getDate()).padStart(2,'0') + ' ' +
                   String(fecha.getHours()).padStart(2,'0') + ':' +
                   String(fecha.getMinutes()).padStart(2,'0') + ':' +
                   String(fecha.getSeconds()).padStart(2,'0');

let yaRegistradoInicio = false;
let maxTime = 0; // posición máxima alcanzada del video

// 🚨 BLOQUEA reproducción automática
video.addEventListener("play", (e) => {
  if (!yaRegistradoInicio) {
    video.pause();
    e.preventDefault();
  }
});

// 🎬 Botón Play
playBtn.addEventListener("click", () => {
  if (video.paused) {
    video.play();
    playBtn.textContent = "⏸️";

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

  } else {
    video.pause();
    playBtn.textContent = "▶️";
  }
});

// ⏱️ Actualización de barra y maxTime
video.addEventListener("timeupdate", () => {
  if (video.currentTime > maxTime) {
    maxTime = video.currentTime;
  }

  const porcentaje = (video.currentTime / video.duration) * 100;
  progreso.value = porcentaje;

  const tiempoTexto = `${formatearTiempo(video.currentTime)} / ${formatearTiempo(video.duration)}`;
  document.getElementById("tiempo").textContent = tiempoTexto;
});

// 🔄 Barra personalizada: permite retroceder pero no avanzar
progreso.addEventListener("input", () => {
  const newTime = (progreso.value / 100) * video.duration;

  if (newTime <= maxTime) {
    video.currentTime = newTime; // rebobinar permitido
  } else {
    video.currentTime = maxTime; // bloquear avance
  }
});

// 🖥️ Fullscreen
fullscreenBtn.addEventListener("click", () => {
  if (video.requestFullscreen) {
    video.requestFullscreen();
  } else if (video.webkitRequestFullscreen) {
    video.webkitRequestFullscreen();
  } else if (video.msRequestFullscreen) {
    video.msRequestFullscreen();
  }
});

// ✅ Finalización
video.addEventListener("ended", () => {
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

// 🕒 Función para formatear tiempo
function formatearTiempo(segundos) {
  const min = Math.floor(segundos / 60).toString().padStart(2, "0");
  const sec = Math.floor(segundos % 60).toString().padStart(2, "0");
  return `${min}:${sec}`;
}

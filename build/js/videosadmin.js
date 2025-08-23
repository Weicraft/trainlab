const video = document.getElementById("video");
const playBtn = document.getElementById("playBtn");
const progreso = document.getElementById("progreso");
const completado = document.getElementById("completado");
const fullscreenBtn = document.getElementById("fullscreenBtn");

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

// 🕒 Función para formatear tiempo
function formatearTiempo(segundos) {
  const min = Math.floor(segundos / 60).toString().padStart(2, "0");
  const sec = Math.floor(segundos % 60).toString().padStart(2, "0");
  return `${min}:${sec}`;
}

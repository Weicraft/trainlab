//Script para ver y no ver contraseña
function togglePasswordVisibility() {
  const passwordInput = document.getElementById("password");
  const toggleIcon = document.querySelector(".toggle-password");

  const isPassword = passwordInput.type === "password";
  passwordInput.type = isPassword ? "text" : "password";

  // Cambiar ícono (opcional)
  toggleIcon.textContent = isPassword ? "🔒" : "🔓";
}

//Script para ver y no ver repetir contraseña
function togglePassword2Visibility() {
  const passwordInput = document.getElementById("password2");
  const toggleIcon = document.querySelector(".toggle-password");

  const isPassword = passwordInput.type === "password";
  passwordInput.type = isPassword ? "text" : "password";

  // Cambiar ícono (opcional)
  toggleIcon.textContent = isPassword ? "🔒" : "🔓";
}

function mostrarNombre(input) {
    const label = document.getElementById('file-label');
    if (input.files.length > 0) {
      label.setAttribute('data-label', input.files[0].name);
    } else {
      label.setAttribute('data-label', 'Ningún archivo seleccionado');
    }
  }

  let contador = 1; // ya tenemos la primera pregunta (índice 0)

document.getElementById("agregar-pregunta").addEventListener("click", function() {
  const container = document.getElementById("preguntas-container");

  const nuevaPregunta = document.createElement("div");
  nuevaPregunta.classList.add("pregunta");
  nuevaPregunta.innerHTML = `
    <div class="margin-top"><label><strong>Pregunta ${contador + 1}:</strong></label></div><br>
    <input type="text" class="field_2" name="pregunta[]" placeholder="Escribe la pregunta"><br><br>

    <label>Respuestas:</label><br>
    <input type="text" class="field_2 margin-bottom" name="respuesta[${contador}][]" placeholder="Respuesta 1"><br>
    <input type="text" class="field_2 margin-bottom" name="respuesta[${contador}][]" placeholder="Respuesta 2"><br>
    <input type="text" class="field_2 margin-bottom" name="respuesta[${contador}][]" placeholder="Respuesta 3"><br>
    <input type="text" class="field_2 margin-bottom" name="respuesta[${contador}][]" placeholder="Respuesta 4"><br><br>

    <label>Respuesta correcta:</label>
    <select class="field margin-bottom" name="correcta[]">
      <option value="0">Respuesta 1</option>
      <option value="1">Respuesta 2</option>
      <option value="2">Respuesta 3</option>
      <option value="3">Respuesta 4</option>
    </select>
    <hr>
  `;

  container.appendChild(nuevaPregunta);
  contador++;
});





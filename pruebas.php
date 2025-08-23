<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Examen</title>
</head>
<body>

  <h2>Crear Examen</h2>
  
  <form id="form-examen">
    <div id="preguntas-container">
      <!-- Primera pregunta -->
      <div class="pregunta">
        <label>Pregunta:</label><br>
        <input type="text" name="pregunta[]" placeholder="Escribe la pregunta"><br><br>

        <label>Respuestas:</label><br>
        <input type="text" name="respuesta[0][]" placeholder="Respuesta 1"><br>
        <input type="text" name="respuesta[0][]" placeholder="Respuesta 2"><br>
        <input type="text" name="respuesta[0][]" placeholder="Respuesta 3"><br>
        <input type="text" name="respuesta[0][]" placeholder="Respuesta 4"><br><br>

        <label>Respuesta correcta:</label>
        <select name="correcta[]">
          <option value="0">Respuesta 1</option>
          <option value="1">Respuesta 2</option>
          <option value="2">Respuesta 3</option>
          <option value="3">Respuesta 4</option>
        </select>
        <hr>
      </div>
    </div>

    <button type="button" id="agregar-pregunta">Agregar Pregunta</button><br><br>
    <button type="submit">Guardar Examen</button>
  </form>

  <script>
    let contador = 1; // ya tenemos la primera pregunta (índice 0)

    document.getElementById("agregar-pregunta").addEventListener("click", function() {
      const container = document.getElementById("preguntas-container");

      const nuevaPregunta = document.createElement("div");
      nuevaPregunta.classList.add("pregunta");
      nuevaPregunta.innerHTML = `
        <label>Pregunta:</label><br>
        <input type="text" name="pregunta[]" placeholder="Escribe la pregunta"><br><br>

        <label>Respuestas:</label><br>
        <input type="text" name="respuesta[${contador}][]" placeholder="Respuesta 1"><br>
        <input type="text" name="respuesta[${contador}][]" placeholder="Respuesta 2"><br>
        <input type="text" name="respuesta[${contador}][]" placeholder="Respuesta 3"><br>
        <input type="text" name="respuesta[${contador}][]" placeholder="Respuesta 4"><br><br>

        <label>Respuesta correcta:</label>
        <select name="correcta[]">
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
  </script>

</body>
</html>

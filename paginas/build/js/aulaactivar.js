document.addEventListener("DOMContentLoaded", function() {
    // Selecciona todos los checkboxes
    const toggles = document.querySelectorAll(".toggle-aula");

    toggles.forEach(toggle => {
        toggle.addEventListener("change", function() {
            const id = this.dataset.id;          // id del participante
            const estado = this.checked ? "A" : "D";  // 'A' = activo, 'D' = desactivado

            // AJAX con fetch
            fetch("paginas/backend/update_aula.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id_particip=" + id + "&activo_aula=" + estado
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert("Error al actualizar el estado");
                    // revertir el switch si falla
                    this.checked = !this.checked;
                }
            })
            .catch(err => {
                console.error(err);
                alert("Error de conexión");
                this.checked = !this.checked;
            });
        });
    });
});


<script>
const fecha = new Date();

const fechaLocal = fecha.getFullYear() + '-' +
                   String(fecha.getMonth()+1).padStart(2,'0') + '-' +
                   String(fecha.getDate()).padStart(2,'0') + ' ' +
                   String(fecha.getHours()).padStart(2,'0') + ':' +
                   String(fecha.getMinutes()).padStart(2,'0') + ':' +
                   String(fecha.getSeconds()).padStart(2,'0');

console.log(fechaLocal); // Esto será igual a tu reloj local
</script>

<?php

?>
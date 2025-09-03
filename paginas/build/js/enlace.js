const copyBtn = document.getElementById('copyLinkBtn');
const btnText = document.getElementById('btnText');
const urlToCopy = 'localhost/trainlab/aulavirtual';

copyBtn.addEventListener('click', async () => {
  console.log('hola mundo');
  try {
    await navigator.clipboard.writeText(urlToCopy);
    
    // Feedback temporal
    btnText.textContent = '¡Copiado!';
    setTimeout(() => {
      btnText.textContent = 'Copiar enlace';
    }, 2000); // 2 segundos
  } catch (err) {
    console.error('Error al copiar:', err);
    btnText.textContent = 'Error';
    setTimeout(() => {
      btnText.textContent = 'Copiar enlace';
    }, 2000);
  }
});
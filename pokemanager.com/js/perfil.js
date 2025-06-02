
function eliminarCuenta() {
  if (confirm("¿Estás segura de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.")) {
    fetch('eliminar_usuario.php', {
      method: 'POST'
    })
    .then(r => r.text())
    .then(txt => {
      alert(txt);
      window.location.href = 'index.php'; // Redirige al inicio
    });
  }
}

const formularios_ajax = document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e) {
  e.preventDefault();
}

formularios_ajax.forEach((formularios) => {
  formularios.addEventListener("submit", enviar_formulario_ajax);
});

/**
 * Se encarga de generar un tipo de alerta con acciones personalizadas
 * según unos parámetros enviados en forma de JSON directamente desde
 * los formularios que generan dichas alertas.
 * 
 * @param {*} alerta JSON con los datos de las alertas personalizadas.
 */
function alertas_ajax(alerta) {
  if (alerta.Alerta === "simple") {
    Swal.fire({
      title: alerta.Titulo,
      text: alerta.Texto,
      type: alerta.Tipo,
      confirmButtonText: "Aceptar"
    });
  } else if (alerta.Alerta === "recargar") {
    Swal.fire({
      title: alerta.Titulo,
      text: alerta.Texto,
      type: alerta.Tipo,
      confirmButtonText: "Aceptar"
    }).then((result) => {
      if (result.value) {
        location.reload();
      }
    });
  } else if (alerta.Alerta === "limpiar") {
    Swal.fire({
      title: alerta.Titulo,
      text: alerta.Texto,
      type: alerta.Tipo,
      confirmButtonText: "Aceptar"
    }).then((result) => {
      if (result.value) {
        document.querySelector(".FormularioAjax").reset();
      }
    });
  } else if (alerta.Alerta === "redireccionar") {
    window.location.href = alerta.URL;
  }
}

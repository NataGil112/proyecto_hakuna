// Mostrar el contenido de los servicios
let boton1 = document.getElementById('guarderia');
let boton2 = document.getElementById('peluqueria');
let boton3 = document.getElementById('paseos');

let guarderia = document.getElementById('servicioGuarderia');
let peluqueria = document.getElementById('servicioPeluqueria');
let paseos = document.getElementById('servicioPaseos');

boton1.addEventListener('click', function() {
    let visible = window.getComputedStyle(guarderia).display !== 'none';

    peluqueria.style.display = 'none';
    paseos.style.display = 'none';

    guarderia.style.display = visible ? 'none' : 'block';
});

boton2.addEventListener('click', function() {
    let visible = window.getComputedStyle(peluqueria).display !== 'none';

    guarderia.style.display = 'none';
    paseos.style.display = 'none';

    peluqueria.style.display = visible ? 'none' : 'block';
});

boton3.addEventListener('click', function() {
    let visible = window.getComputedStyle(paseos).display !== 'none';

    peluqueria.style.display = 'none';
    guarderia.style.display = 'none';

    paseos.style.display = visible ? 'none' : 'block';
});

// Comprobar si esta autenticado

let autenticado = false;

function comprobarAut() {
  let xhr = new XMLHttpRequest();
  xhr.open('GET', 'PHP/autenticacion.php', true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      let response = JSON.parse(xhr.responseText);
      autenticado = response.autenticado;
    }
  };
  xhr.send();
}

window.onload = comprobarAut();

// Función para mostrar la notificación

function mostrarAviso() {
  let notificacion = document.getElementById('notificacion');
  notificacion.style.display = 'block';
  setTimeout(function() {
    notificacion.style.display = 'none';
  }, 3000);
}

// Mostrar el formulario

let form = document.getElementById('modal');
let btns = document.querySelectorAll('.agendar');
let cerrar = document.getElementsByClassName("cerrar")[0];

document.querySelectorAll('.agendar').forEach(button => {
    button.addEventListener('click', function(event) {
      if (!autenticado) {
        event.preventDefault();
        mostrarAviso();
      } else {
        form.style.display = "block";
      }
    });
  });
  
cerrar.onclick = function() { 
    form.style.display = "none";
}
  
window.onclick = function(event) {
    if (event.target == form) {
        form.style.display = "none";
    }
}
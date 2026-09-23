// JS del modulo Cursos
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Detalle de curso cargado');
});

// Cambio de pestañas
document.addEventListener('click', function(e) {
    if (e.target.closest('.pestañas button')) {
        const boton = e.target.closest('button');
        const idPestaña = boton.dataset.pestaña;
        
        document.querySelectorAll('.pestañas button').forEach(b => b.classList.remove('pestaña-activa'));
        boton.classList.add('pestaña-activa');
        
        document.querySelectorAll('.contenido-pestaña').forEach(c => c.classList.add('oculto'));
        const seccion = document.getElementById(idPestaña);
        if (seccion) seccion.classList.remove('oculto');
    }
});

// Desplegar/ocultar módulos del temario
function toggleModulo(boton) {
    const contenido = boton.nextElementSibling;
    const indicador = boton.querySelector('.indicador');
    
    if (!contenido || !indicador) return;
    
    contenido.classList.toggle('oculto');
    indicador.textContent = contenido.classList.contains('oculto') ? '▶' : '▼';
}

// Ventana modal
function mostrarVentana(titulo, mensaje) {
    const ventana = document.getElementById('ventana');
    const elTitulo = document.getElementById('ventana-titulo');
    const elMensaje = document.getElementById('ventana-mensaje');
    
    if (elTitulo) elTitulo.textContent = titulo;
    if (elMensaje) elMensaje.textContent = mensaje;
    if (ventana) ventana.classList.remove('oculto');
}

function cerrarVentana() {
    const ventana = document.getElementById('ventana');
    if (ventana) ventana.classList.add('oculto');
}

// Acciones de botones
document.addEventListener('click', function(e) {
    const id = e.target.id;
    
    if (id === 'btn-inscribir' || id === 'btn-acceder' || id === 'btn-acceder-final') {
        mostrarVentana('Solicitud recibida', 'El sistema de inscripción se integrará en la siguiente etapa del desarrollo.');
    }
    if (id === 'btn-guardar') {
        mostrarVentana('Operación completada', 'El curso ha sido agregado a su lista de referencias.');
    }
    if (id === 'btn-compartir') {
        navigator.clipboard?.writeText(window.location.href).then(() => {
            mostrarVentana('Enlace copiado', 'La dirección del curso ha sido copiada al portapapeles.');
        }).catch(() => {
            mostrarVentana('Enlace generado', 'Copie la dirección desde la barra del navegador.');
        });
    }
    if (id === 'btn-ver-ruta') {
        mostrarVentana('Contenido disponible', 'La ruta de aprendizaje se muestra a continuación.');
        document.getElementById('ruta-aprendizaje')?.scrollIntoView({ behavior: 'smooth' });
    }
    if (id === 'btn-contactar') {
        mostrarVentana('Comunicación', 'El sistema de mensajería estará disponible en la próxima versión.');
    }
    if (id === 'btn-valorar') {
        mostrarVentana('Valoración', 'Podrá emitir su opinión una vez finalizado el curso.');
    }
    if (id === 'cerrar-ventana' || id === 'ventana-aceptar') {
        cerrarVentana();
    }
});

// Buscador del temario
document.addEventListener('input', function(e) {
    if (e.target.id === 'buscar-temas') {
        const texto = e.target.value.toLowerCase().trim();
        const temas = document.querySelectorAll('.tema-item');
        
        temas.forEach(function(item) {
            const coincide = texto === '' || item.textContent.toLowerCase().indexOf(texto) !== -1;
            item.style.display = coincide ? 'block' : 'none';
        });
    }
});
// === RUTA DE APRENDIZAJE ===
document.addEventListener('DOMContentLoaded', function() {
    // Desplazamiento suave al hacer clic en "Ver ruta completa"
    const btnRuta = document.getElementById('btn-ver-ruta');
    const seccionRuta = document.getElementById('ruta-aprendizaje');

    if (btnRuta && seccionRuta) {
        btnRuta.addEventListener('click', function(e) {
            e.preventDefault();
            seccionRuta.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    // Resaltar pasos y efectos
    const pasos = document.querySelectorAll('.paso-item');
    
    pasos.forEach((paso, indice) => {
        const numero = paso.querySelector('.paso-numero');
        const tarjeta = paso.querySelector('.paso-tarjeta');
        
        // El primer paso siempre activo al cargar
        if (indice === 0) {
            numero.classList.add('activo');
            tarjeta.classList.add('activa');
        }

        // Efecto al pasar el mouse
        tarjeta.addEventListener('mouseenter', () => {
            numero.style.transform = 'scale(1.1)';
        });
        tarjeta.addEventListener('mouseleave', () => {
            numero.style.transform = 'scale(1)';
        });

        // Marcar/desmarcar al hacer clic (solo los disponibles)
        tarjeta.addEventListener('click', () => {
            if (tarjeta.querySelector('.etiqueta-disponible')) {
                numero.classList.toggle('activo');
                tarjeta.classList.toggle('activa');
            }
        });
    });
});
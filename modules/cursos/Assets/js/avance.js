console.log('✅ Página de Avance cargada — Sistema Completo');

// ==================================================
// UTILIDADES GENERALES
// ==================================================
const BASE_URL = (window.location.pathname.includes('/AiaAcademy') ? '/AiaAcademy' : '');
const USUARIO_ID = 1; // Reemplaza con $_SESSION['user_id'] en PHP

function formatearTiempo(segundos) {
    const h = Math.floor(segundos / 3600);
    const m = Math.floor((segundos % 3600) / 60);
    const s = Math.floor(segundos % 60);
    if (h > 0) {
        return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }
    return `${m}:${s.toString().padStart(2, '0')}`;
}

function mostrarNotificacion(mensaje, tipo = 'exito') {
    const colores = {
        exito: '#10b981',
        error: '#ef4444',
        aviso: '#f59e0b',
        info: '#3b82f6'
    };
    const caja = document.createElement('div');
    caja.style.cssText = `
        position: fixed; top: 20px; right: 20px; z-index: 9999;
        background: ${colores[tipo]}; color: white; padding: 14px 24px;
        border-radius: 10px; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(120%); transition: transform 0.3s ease;
    `;
    caja.textContent = mensaje;
    document.body.appendChild(caja);
    setTimeout(() => caja.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
        caja.style.transform = 'translateX(120%)';
        setTimeout(() => caja.remove(), 300);
    }, 3500);
}

// ==================================================
// 1. RESALTAR LECCIÓN ACTIVA EN EL TEMARIO
// ==================================================
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const leccionId = urlParams.get('leccion');

    if (leccionId) {
        const enlaceActivo = document.querySelector(`.enlace-tema[href*="leccion=${leccionId}"]`);
        if (enlaceActivo) {
            enlaceActivo.style.background = '#eff6ff';
            enlaceActivo.style.borderLeft = '4px solid #3b82f6';
            enlaceActivo.style.fontWeight = '600';
            enlaceActivo.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});

// ==================================================
// 2. BARRA DE PROGRESO — ANIMACIÓN AL CARGAR
// ==================================================
document.addEventListener('DOMContentLoaded', function () {
    const barra = document.querySelector('.barra-llenada');
    const porcentajeTexto = document.querySelector('.texto-progreso');
    
    if (!barra) return;

    const anchoFinal = barra.style.width || '0%';
    barra.style.width = '0%';
    
    let porcentajeNum = parseInt(anchoFinal) || 0;
    let actual = 0;
    const paso = Math.ceil(porcentajeNum / 25);
    const animar = () => {
        actual += paso;
        if (actual > porcentajeNum) actual = porcentajeNum;
        barra.style.width = `${actual}%`;
        if (porcentajeTexto) {
            porcentajeTexto.textContent = `${actual}% completado`;
        }
        if (actual < porcentajeNum) {
            requestAnimationFrame(animar);
        }
    };
    setTimeout(animar, 300);
});

// ==================================================
// 3. VIDEO — REPRODUCCIÓN, PROGRESO Y MARCAR VISTO
// ==================================================
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video-principal');
    if (!video) return;

    const leccionId = video.dataset.leccionId;
    const cursoId = video.dataset.cursoId;
    let marcadoComoVisto = false;
    let tiempoTotalReproducido = 0;
    let ultimaPausa = 0;

    // Cargar posición guardada del localStorage
    const clavePosicion = `video_pos_${cursoId}_${leccionId}`;
    const posicionGuardada = localStorage.getItem(clavePosicion);
    if (posicionGuardada && parseFloat(posicionGuardada) > 0) {
        video.currentTime = parseFloat(posicionGuardada);
        console.log('⏱️ Posición restaurada:', formatearTiempo(video.currentTime));
    }

    // Guardar posición cada 5 segundos
    setInterval(() => {
        if (!video.paused && video.currentTime > 0) {
            localStorage.setItem(clavePosicion, video.currentTime);
            tiempoTotalReproducido += video.currentTime - ultimaPausa;
            ultimaPausa = video.currentTime;
        }
    }, 5000);

    // Actualizar tiempo al pausar/reproducir
    video.addEventListener('play', () => {
        ultimaPausa = video.currentTime;
        console.log('▶️ Reproducción iniciada');
    });

    video.addEventListener('pause', () => {
        console.log('⏸️ Pausado en:', formatearTiempo(video.currentTime));
    });

    // Marcar como visto al llegar al 90% de duración
    video.addEventListener('timeupdate', function () {
        if (marcadoComoVisto || video.duration <= 0) return;
        const porcentajeVisto = (video.currentTime / video.duration) * 100;
        if (porcentajeVisto >= 90) {
            marcadoComoVisto = true;
            marcarVideoVistoEnServidor();
        }
    });

    // Marcar al terminar completamente
    video.addEventListener('ended', marcarVideoVistoEnServidor);

    function marcarVideoVistoEnServidor() {
        if (marcadoComoVisto) return;
        marcadoComoVisto = true;

        console.log('✅ Video marcado como visto — Lección:', leccionId);
        mostrarNotificacion('✅ Video completado', 'exito');

        fetch(`${BASE_URL}/cursos/marcar-video-visto`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `leccion_id=${encodeURIComponent(leccionId)}&curso_id=${encodeURIComponent(cursoId)}`
        })
        .then(res => res.json())
        .then(datos => {
            console.log('📡 Respuesta del servidor:', datos);
            setTimeout(() => location.reload(), 1200);
        })
        .catch(err => {
            console.error('❌ Error al guardar:', err);
            mostrarNotificacion('Guardado localmente', 'aviso');
        });
    }

    // Controles extra del reproductor
    video.addEventListener('loadedmetadata', () => {
        console.log('📹 Duración del video:', formatearTiempo(video.duration));
    });
});

// ==================================================
// 4. GESTIÓN DE NOTAS PERSONALES
// ==================================================
document.addEventListener('DOMContentLoaded', function () {
    const botonGuardar = document.getElementById('boton-guardar-notas');
    const cuadroNotas = document.getElementById('cuadro-notas');
    
    if (!botonGuardar || !cuadroNotas) return;

    const urlParams = new URLSearchParams(window.location.search);
    const leccionId = urlParams.get('leccion') || 'general';
    const claveNotas = `notas_leccion_${leccionId}`;

    // Cargar notas guardadas
    const notasGuardadas = localStorage.getItem(claveNotas);
    if (notasGuardadas) {
        cuadroNotas.value = notasGuardadas;
        console.log('📝 Notas cargadas para lección', leccionId);
    }

    // Guardar al hacer clic
    botonGuardar.addEventListener('click', function () {
        const texto = cuadroNotas.value.trim();
        
        localStorage.setItem(claveNotas, texto);
        
        // Efecto visual
        botonGuardar.textContent = '✅ Guardado';
        botonGuardar.style.background = '#10b981';
        botonGuardar.style.transform = 'scale(0.97)';
        setTimeout(() => botonGuardar.style.transform = '', 150);
        
        mostrarNotificacion('📝 Notas guardadas', 'exito');
        
        setTimeout(() => {
            botonGuardar.textContent = 'Guardar Notas';
            botonGuardar.style.background = '';
        }, 2500);

        console.log('📝 Notas guardadas:', texto.substring(0, 60) + (texto.length > 60 ? '...' : ''));
    });

    // Guardar automáticamente cada 30s mientras escribe
    let temporizadorAutoGuardado;
    cuadroNotas.addEventListener('input', function () {
        clearTimeout(temporizadorAutoGuardado);
        temporizadorAutoGuardado = setTimeout(() => {
            localStorage.setItem(claveNotas, cuadroNotas.value.trim());
            console.log('💾 Autoguardado');
        }, 30000);
    });
});

// ==================================================
// 5. CUESTIONARIO Y EVALUACIÓN
// ==================================================
document.addEventListener('DOMContentLoaded', function () {
    const formExamen = document.getElementById('form-examen');
    if (!formExamen) return;

    const leccionId = formExamen.dataset.leccionId;
    const cursoId = formExamen.dataset.cursoId;
    const resultadoDiv = document.getElementById('resultado-examen');

    // Cargar respuestas previas
    const claveRespuestas = `respuestas_${cursoId}_${leccionId}`;
    const guardadas = JSON.parse(localStorage.getItem(claveRespuestas) || '{}');
    Object.keys(guardadas).forEach(nombrePregunta => {
        const input = document.querySelector(`input[name="${nombrePregunta}"][value="${guardadas[nombrePregunta]}"]`);
        if (input) input.checked = true;
    });

    formExamen.addEventListener('change', function (e) {
        if (e.target.type === 'radio') {
            guardadas[e.target.name] = e.target.value;
            localStorage.setItem(claveRespuestas, JSON.stringify(guardadas));
        }
    });

    formExamen.addEventListener('submit', function (e) {
        e.preventDefault();

        const respuestas = {};
        let totalPreguntas = 0;
        let respondidas = 0;
        let correctas = 0;

        const todosNombres = new Set();
        formExamen.querySelectorAll('input[type="radio"]').forEach(r => {
            todosNombres.add(r.name);
        });
        totalPreguntas = todosNombres.size;

        todosNombres.forEach(nombre => {
            const seleccionada = formExamen.querySelector(`input[name="${nombre}"]:checked`);
            if (seleccionada) {
                respondidas++;
                const valor = seleccionada.value;
                const correcta = seleccionada.dataset.correcta;
                respuestas[nombre] = valor;
                if (parseInt(valor) === parseInt(correcta)) correctas++;
            }
        });

        if (respondidas < totalPreguntas) {
            mostrarNotificacion(`Faltan ${totalPreguntas - respondidas} preguntas por responder`, 'aviso');
            return;
        }

        const porcentaje = (correctas / totalPreguntas) * 100;
        const aprobado = porcentaje >= 60;

        resultadoDiv.style.display = 'block';
        resultadoDiv.style.borderRadius = '8px';
        resultadoDiv.style.padding = '16px';
        resultadoDiv.style.marginTop = '20px';

        if (aprobado) {
            resultadoDiv.style.background = '#065f47';
            resultadoDiv.style.color = '#d1fae3';
            resultadoDiv.innerHTML = `
                <strong>✅ ¡Aprobado!</strong><br>
                ${correctas} de ${totalPreguntas} correctas (${porcentaje.toFixed(0)}%)<br>
                <small>Actualizando progreso...</small>
            `;
            mostrarNotificacion('🎉 ¡Aprobado!', 'exito');

            fetch(`${BASE_URL}/cursos/marcar-cuestionario-aprobado`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `leccion_id=${encodeURIComponent(leccionId)}&curso_id=${encodeURIComponent(cursoId)}`
            })
            .then(res => res.json())
            .then(() => {
                localStorage.removeItem(claveRespuestas);
                setTimeout(() => location.reload(), 1800);
            })
            .catch(err => {
                console.error(err);
                setTimeout(() => location.reload(), 1800);
            });
        } else {
            resultadoDiv.style.background = '#7f1d1d';
            resultadoDiv.style.color = '#fecaca';
            resultadoDiv.innerHTML = `
                <strong>❌ No aprobado</strong><br>
                ${correctas} de ${totalPreguntas} correctas (${porcentaje.toFixed(0)}%)<br>
                <small>Necesitas 60% para aprobar. Repasa y vuelve a intentar.</small>
            `;
            mostrarNotificacion('Inténtalo de nuevo', 'error');
        }

        console.log(`📝 Evaluación: ${correctas}/${totalPreguntas} = ${porcentaje.toFixed(1)}%`);
    });
});

// ==================================================
// 6. SCROLL SUAVE Y NAVEGACIÓN
// ==================================================
document.querySelectorAll('a[href^="#"]').forEach(enlace => {
    enlace.addEventListener('click', function (e) {
        const destino = document.querySelector(this.getAttribute('href'));
        if (destino) {
            e.preventDefault();
            destino.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Navegación entre lecciones
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const cursoId = urlParams.get('curso_id') || document.querySelector('[data-curso-id]')?.dataset.cursoId;
    
    // Teclas de flecha para cambiar de lección
    document.addEventListener('keydown', function (e) {
        if (e.target.tagName === 'TEXTAREA' || e.target.tagName === 'INPUT') return;
        
        const enlaces = Array.from(document.querySelectorAll('.enlace-tema'));
        const actual = document.querySelector('.enlace-tema.activa');
        const indiceActual = enlaces.findIndex(a => a === actual);

        if (e.key === 'ArrowRight' && indiceActual < enlaces.length - 1) {
            location.href = enlaces[indiceActual + 1].href;
        }
        if (e.key === 'ArrowLeft' && indiceActual > 0) {
            location.href = enlaces[indiceActual - 1].href;
        }
    });
});

// ==================================================
// 7. DETECCIÓN DE PANTALLA PEQUEÑA — AJUSTES MÓVIL
// ==================================================
function ajustarParaMovil() {
    const esMovil = window.innerWidth < 768;
    const panelTemario = document.querySelector('.panel-lista-lecciones');
    if (panelTemario) {
        panelTemario.style.position = esMovil ? 'static' : 'sticky';
        panelTemario.style.width = esMovil ? '100%' : '320px';
    }
}
window.addEventListener('resize', ajustarParaMovil);
ajustarParaMovil();

// ==================================================
// 8. VERIFICAR CERTIFICADO
// ==================================================
document.addEventListener('DOMContentLoaded', function () {
    const botonCertificado = document.querySelector('.btn-certificado');
    if (botonCertificado) {
        botonCertificado.addEventListener('click', function () {
            mostrarNotificacion('🎓 Generando certificado...', 'info');
        });
    }
});

console.log('✅ Sistema de avance completamente cargado');
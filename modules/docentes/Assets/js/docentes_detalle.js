/**
 * docentes_detalle.js — Perfil Completo
 * AiaAcademy | Animaciones, accesibilidad y efectos
 */

document.addEventListener('DOMContentLoaded', () => {
    // =============================================
    // 1️⃣ ELEMENTOS Y CONFIGURACIÓN
    // =============================================
    const tarjetaPerfil = document.querySelector('.tarjeta-perfil');
    const tarjetasCurso = document.querySelectorAll('.tarjeta-curso-mini');
    const botonVolver = document.querySelector('.boton-volver');
    const fotoPerfil = document.querySelector('.foto-perfil, .foto-placeholder-grande');
    
    const CONFIG = {
        duracionEntradaPerfil: 700,
        retrasoEntreCursos: 100,
        duracionHover: 300,
        umbralVisibilidad: 0.15,
        animacionesActivas: true
    };

    // =============================================
    // 2️⃣ ANIMACIÓN DE ENTRADA PRINCIPAL
    // =============================================
    function animarEntradaPerfil() {
        if (!tarjetaPerfil) return;

        tarjetaPerfil.style.opacity = '0';
        tarjetaPerfil.style.transform = 'translateY(30px) scale(0.97)';
        tarjetaPerfil.style.transition = 
            `all ${CONFIG.duracionEntradaPerfil}ms cubic-bezier(0.17, 0.55, 0.55, 1)`;

        setTimeout(() => {
            tarjetaPerfil.style.opacity = '1';
            tarjetaPerfil.style.transform = 'translateY(0) scale(1)';
        }, 80);
    }

    // =============================================
    // 3️⃣ ENTRADA ESCALONADA DE CURSOS
    // =============================================
    function animarEntradaCursos() {
        tarjetasCurso.forEach((tarjeta, indice) => {
            tarjeta.style.opacity = '0';
            tarjeta.style.transform = 'translateY(20px)';
            tarjeta.style.transition = 
                `all 0.5s ease-out ${0.25 + indice * CONFIG.retrasoEntreCursos / 1000}s`;

            setTimeout(() => {
                tarjeta.style.opacity = '1';
                tarjeta.style.transform = 'translateY(0)';
            }, 200);
        });
    }

    // =============================================
    // 4️⃣ EFECTO PARALLAX EN FOTO
    // =============================================
    function configurarParallaxFoto() {
        if (!fotoPerfil || window.innerWidth < 768) return;

        tarjetaPerfil.addEventListener('mousemove', (e) => {
            const rect = tarjetaPerfil.getBoundingClientRect();
            const x = (e.clientX - rect.left - rect.width / 2) / rect.width;
            const y = (e.clientY - rect.top - rect.height / 2) / rect.height;

            fotoPerfil.style.transform = 
                `scale(1.03) translate(${x * 5}px, ${y * 5}px)`;
        });

        tarjetaPerfil.addEventListener('mouseleave', () => {
            fotoPerfil.style.transform = '';
        });
    }

    // =============================================
    // 5️⃣ INTERACCIÓN TARJETAS DE CURSO
    // =============================================
    function configurarTarjetasCurso() {
        tarjetasCurso.forEach(tarjeta => {
            // Efecto al presionar
            tarjeta.addEventListener('mousedown', () => {
                tarjeta.style.transform = 'scale(0.98) translateY(-2px)';
            });

            tarjeta.addEventListener('mouseup mouseleave', () => {
                tarjeta.style.transform = '';
            });

            // Soporte táctil
            tarjeta.addEventListener('touchstart', () => {
                tarjeta.style.transform = 'scale(0.98)';
            }, { passive: true });

            tarjeta.addEventListener('touchend', () => {
                tarjeta.style.transform = '';
            }, { passive: true });

            // Accesibilidad: teclado
            tarjeta.setAttribute('tabindex', '0');
            tarjeta.setAttribute('role', 'link');
            const titulo = tarjeta.querySelector('.titulo-curso-mini')?.textContent || 'Curso';
            tarjeta.setAttribute('aria-label', `Ir al curso: ${titulo}`);

            tarjeta.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const enlace = tarjeta.getAttribute('href');
                    if (enlace) window.location.href = enlace;
                }
            });
        });
    }

    // =============================================
    // 6️⃣ BOTÓN VOLVER — ANIMACIÓN DE SALIDA
    // =============================================
    function configurarBotonVolver() {
        if (!botonVolver) return;

        botonVolver.addEventListener('click', (e) => {
            e.preventDefault();
            const destino = botonVolver.getAttribute('href');

            // Animar salida
            tarjetaPerfil.style.opacity = '0';
            tarjetaPerfil.style.transform = 'translateY(-20px)';
            tarjetaPerfil.style.transition = 'all 0.3s ease-in';

            tarjetasCurso.forEach((tarjeta, i) => {
                setTimeout(() => {
                    tarjeta.style.opacity = '0';
                    tarjeta.style.transform = 'translateY(20px)';
                }, i * 50);
            });

            // Navegar después de la animación
            setTimeout(() => {
                window.location.href = destino;
            }, 400);
        });
    }

    // =============================================
    // 7️⃣ OBSERVADOR DE VISIBILIDAD EN SCROLL
    // =============================================
    function configurarObservadorScroll() {
        if (!('IntersectionObserver' in window)) return;

        const observador = new IntersectionObserver((entradas) => {
            entradas.forEach(entrada => {
                if (entrada.isIntersecting) {
                    entrada.target.classList.add('visible-en-pantalla');
                    observador.unobserve(entrada.target);
                }
            });
        }, {
            threshold: CONFIG.umbralVisibilidad,
            rootMargin: '0px 0px -30px 0px'
        });

        tarjetasCurso.forEach(tarjeta => observador.observe(tarjeta));
    }

    // =============================================
    // 8️⃣ CARGA INTELIGENTE DE IMÁGENES
    // =============================================
    function configurarCargaDiferida() {
        const imagenes = document.querySelectorAll('.img-miniatura, .foto-perfil');
        
        if (!('IntersectionObserver' in window)) {
            imagenes.forEach(img => {
                if (img.dataset.src) img.src = img.dataset.src;
            });
            return;
        }

        const observadorImg = new IntersectionObserver((entradas) => {
            entradas.forEach(entrada => {
                if (entrada.isIntersecting) {
                    const img = entrada.target;
                    if (img.dataset.src) {
                        img.style.opacity = '0';
                        img.src = img.dataset.src;
                        img.onload = () => {
                            img.style.transition = 'opacity 0.5s ease';
                            img.style.opacity = '1';
                        };
                        observadorImg.unobserve(img);
                    }
                }
            });
        }, { rootMargin: '150px' });

        imagenes.forEach(img => observadorImg.observe(img));
    }

    // =============================================
    // 9️⃣ EFECTO DE RESALTADO AL SCROLLEAR
    // =============================================
    function configurarResaltadoScroll() {
        if (tarjetasCurso.length < 3) return;

        window.addEventListener('scroll', () => {
            const vista = window.innerHeight;
            tarjetasCurso.forEach(tarjeta => {
                const rect = tarjeta.getBoundingClientRect();
                const centro = rect.top + rect.height / 2;
                const distanciaCentro = Math.abs(vista / 2 - centro);
                const factor = Math.max(0.85, 1 - distanciaCentro / vista);
                
                tarjeta.style.opacity = factor;
            });
        }, { passive: true });
    }

    // =============================================
    // 🔟 RESPONSIVO — DESACTIVAR EFECTOS EN MÓVIL
    // =============================================
    function configurarAdaptacionMovil() {
        let esMovil = window.innerWidth < 768;

        const ajustar = () => {
            esMovil = window.innerWidth < 768;
            if (esMovil) {
                // Quitar parallax y efectos pesados
                if (fotoPerfil) fotoPerfil.style.transform = '';
            }
        };

        window.addEventListener('resize', ajustar);
        window.addEventListener('orientationchange', ajustar);
    }

    // =============================================
    // 1️⃣1️⃣ INICIALIZAR TODO
    // =============================================
    function iniciar() {
        if (!CONFIG.animacionesActivas) return;

        animarEntradaPerfil();
        animarEntradaCursos();
        configurarParallaxFoto();
        configurarTarjetasCurso();
        configurarBotonVolver();
        configurarObservadorScroll();
        configurarCargaDiferida();
        configurarResaltadoScroll();
        configurarAdaptacionMovil();

        console.log('%c👤 Perfil de Docente cargado — AiaAcademy', 
            'color: #60a5fa; font-weight: bold; font-size: 12px;');
    }

    // ¡Todo listo!
    iniciar();
});
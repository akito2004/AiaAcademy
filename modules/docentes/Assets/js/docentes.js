// JS del modulo Docentes

/**
 * docentes.js — Comportamiento página de Docentes
 * AiaAcademy | Diseño con animaciones, interacción y accesibilidad
 */

document.addEventListener('DOMContentLoaded', () => {
    // =============================================
    // 1️⃣ CONFIGURACIÓN Y ELEMENTOS PRINCIPALES
    // =============================================
    const contenedor = document.querySelector('.grid-docentes');
    const tarjetas = document.querySelectorAll('.tarjeta-docente-enlace');
    const encabezado = document.querySelector('.seccion-encabezado');
    
    const config = {
        duracionEntrada: 600,
        retrasoEntreTarjetas: 120,
        duracionHover: 300,
        umbralVisibilidad: 0.15
    };

    // =============================================
    // 2️⃣ ANIMACIÓN DE ENTRADA ESCALONADA
    // =============================================
    function animarEntrada() {
        // Encabezado primero
        if (encabezado) {
            encabezado.style.opacity = '0';
            encabezado.style.transform = 'translateY(-15px)';
            encabezado.style.transition = `all ${config.duracionEntrada}ms ease-out`;
            
            setTimeout(() => {
                encabezado.style.opacity = '1';
                encabezado.style.transform = 'translateY(0)';
            }, 100);
        }

        // Tarjetas una por una
        tarjetas.forEach((tarjeta, indice) => {
            tarjeta.style.opacity = '0';
            tarjeta.style.transform = 'translateY(30px) scale(0.95)';
            tarjeta.style.transition = `all ${config.duracionEntrada}ms cubic-bezier(0.17, 0.55, 0.55, 1) ${indice * config.retrasoEntreTarjetas}ms`;

            setTimeout(() => {
                tarjeta.style.opacity = '1';
                tarjeta.style.transform = 'translateY(0) scale(1)';
            }, 200 + indice * config.retrasoEntreTarjetas);
        });
    }

    // =============================================
    // 3️⃣ EFECTOS AL PASAR EL CURSOR
    // =============================================
    function configurarHover() {
        tarjetas.forEach(tarjeta => {
            const tarjetaInterior = tarjeta.querySelector('.tarjeta-docente');
            const foto = tarjeta.querySelector('.foto-docente, .foto-placeholder');
            const boton = tarjeta.querySelector('.boton-ver-perfil');

            tarjeta.addEventListener('mouseenter', () => {
                tarjetaInterior.style.transition = `all ${config.duracionHover}ms ease`;
                if (foto) foto.style.transition = `transform 0.5s ease`;
                if (boton) {
                    boton.style.transform = 'translateX(6px)';
                    boton.style.color = '#ffffff';
                }
            });

            tarjeta.addEventListener('mouseleave', () => {
                if (boton) {
                    boton.style.transform = 'translateX(0)';
                    boton.style.color = '';
                }
            });
        });
    }

    // =============================================
    // 4️⃣ EFECTO AL HACER CLIC — RETROALIMENTACIÓN
    // =============================================
    function configurarClic() {
        tarjetas.forEach(tarjeta => {
            const tarjetaInterior = tarjeta.querySelector('.tarjeta-docente');
            
            tarjeta.addEventListener('mousedown', () => {
                tarjetaInterior.style.transform = 'scale(0.97) translateY(-4px)';
                tarjetaInterior.style.transition = 'transform 0.12s ease';
            });

            tarjeta.addEventListener('mouseup', () => {
                tarjetaInterior.style.transform = '';
            });

            tarjeta.addEventListener('mouseout', () => {
                tarjetaInterior.style.transform = '';
            });

            // Soporte para dispositivos táctiles
            tarjeta.addEventListener('touchstart', () => {
                tarjetaInterior.style.transform = 'scale(0.97)';
            }, { passive: true });

            tarjeta.addEventListener('touchend', () => {
                tarjetaInterior.style.transform = '';
            }, { passive: true });
        });
    }

    // =============================================
    // 5️⃣ DETECCIÓN DE VISIBILIDAD (SCROLL ANIMADO)
    // =============================================
    function configurarObservadorVisibilidad() {
        if (!('IntersectionObserver' in window)) return;

        const observador = new IntersectionObserver((entradas) => {
            entradas.forEach(entrada => {
                if (entrada.isIntersecting) {
                    entrada.target.classList.add('visible');
                    observador.unobserve(entrada.target);
                }
            });
        }, {
            threshold: config.umbralVisibilidad,
            rootMargin: '0px 0px -50px 0px'
        });

        tarjetas.forEach(tarjeta => {
            observador.observe(tarjeta);
        });
    }

    // =============================================
    // 6️⃣ ACCESIBILIDAD — NAVEGACIÓN POR TECLADO
    // =============================================
    function configurarAccesibilidad() {
        tarjetas.forEach((tarjeta, indice) => {
            tarjeta.setAttribute('tabindex', '0');
            tarjeta.setAttribute('role', 'link');
            tarjeta.setAttribute('aria-label', `Ver perfil del docente: ${tarjeta.querySelector('.nombre-docente')?.textContent || 'Docente'}`);

            tarjeta.addEventListener('keydown', (evento) => {
                if (evento.key === 'Enter' || evento.key === ' ') {
                    evento.preventDefault();
                    const enlace = tarjeta.getAttribute('href');
                    if (enlace) window.location.href = enlace;
                }
            });
        });
    }

    // =============================================
    // 7️⃣ CONTADOR Y MENSAJE ESTADO
    // =============================================
    function mostrarContador() {
        const total = tarjetas.length;
        if (total === 0) return;

        const subtitulo = document.querySelector('.subtitulo');
        if (subtitulo && !subtitulo.dataset.original) {
            subtitulo.dataset.original = subtitulo.textContent;
            subtitulo.textContent = `${subtitulo.dataset.original} — ${total} docentes disponibles`;
        }
    }

    // =============================================
    // 8️⃣ EFECTO PARALLAX SUAVE EN FONDO
    // =============================================
    function configurarParallax() {
        const pagina = document.querySelector('.pagina-docentes');
        if (!pagina || window.innerWidth < 768) return;

        let ultimoScroll = 0;
        let solicitudAnimacion = null;

        window.addEventListener('scroll', () => {
            ultimoScroll = window.scrollY;
            if (!solicitudAnimacion) {
                solicitudAnimacion = requestAnimationFrame(aplicarParallax);
            }
        }, { passive: true });

        function aplicarParallax() {
            if (pagina) {
                pagina.style.backgroundPositionY = `${ultimoScroll * 0.02}px`;
            }
            solicitudAnimacion = null;
        }
    }

    // =============================================
    // 9️⃣ CARGA INTELIGENTE DE IMÁGENES
    // =============================================
    function configurarCargaDiferida() {
        const imagenes = document.querySelectorAll('.foto-docente');
        
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
                            img.style.transition = 'opacity 0.4s ease';
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
    // 🔟 RESPONSIVO — AJUSTES DINÁMICOS
    // =============================================
    function configurarResponsivo() {
        let anchoActual = window.innerWidth;

        function actualizarDispositivo() {
            const esMovil = window.innerWidth < 768;
            if (esMovil) {
                tarjetas.forEach(tarjeta => {
                    tarjeta.style.transform = '';
                    tarjeta.style.transition = '';
                });
            }
            anchoActual = window.innerWidth;
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth !== anchoActual) {
                actualizarDispositivo();
            }
        });
    }

    // =============================================
    // 1️⃣1️⃣ INICIALIZAR TODO
    // =============================================
    function iniciar() {
        animarEntrada();
        configurarHover();
        configurarClic();
        configurarObservadorVisibilidad();
        configurarAccesibilidad();
        mostrarContador();
        configurarParallax();
        configurarCargaDiferida();
        configurarResponsivo();
        
        console.log('%c📚 AiaAcademy — Módulo Docentes cargado', 'color: #60a5fa; font-weight: bold;');
    }

    // ¡Arrancamos!
    iniciar();
});
document.addEventListener('DOMContentLoaded', function () {
    const botones = document.querySelectorAll('.grupo-filtros button');
    const seccionProximos = document.getElementById('proximos');
    const seccionDisponibles = document.getElementById('disponibles');
    const seccionTodos = document.getElementById('todos');

    function ocultarTodasSecciones() {
        if (seccionProximos) seccionProximos.style.display = 'none';
        if (seccionDisponibles) seccionDisponibles.style.display = 'none';
        if (seccionTodos) seccionTodos.style.display = 'block'; // Siempre mostrar "Todos" por defecto
    }

    function quitarActivo() {
        botones.forEach(b => b.classList.remove('filtro-activo'));
    }

    function mostrarTodasTarjetas() {
        document.querySelectorAll('.tarjeta-curso').forEach(t => t.style.display = 'block');
    }

    // Mostrar TODOs al cargar
    ocultarTodasSecciones();
    mostrarTodasTarjetas();
    document.querySelector('[data-filtro="todos"]').classList.add('filtro-activo');

    botones.forEach(boton => {
        boton.addEventListener('click', function () {
            const filtro = this.dataset.filtro;
            
            quitarActivo();
            mostrarTodasTarjetas();

            if (filtro === 'todos') {
                ocultarTodasSecciones();
                if (seccionTodos) seccionTodos.style.display = 'block';
                this.classList.add('filtro-activo');
            } else if (filtro === 'disponibles') {
                ocultarTodasSecciones();
                if (seccionDisponibles) seccionDisponibles.style.display = 'block';
                this.classList.add('filtro-activo');
            } else if (filtro === 'proximos') {
                ocultarTodasSecciones();
                if (seccionProximos) seccionProximos.style.display = 'block';
                this.classList.add('filtro-activo');
            } else if (filtro === 'gratuitos') {
                ocultarTodasSecciones();
                if (seccionTodos) seccionTodos.style.display = 'block';
                this.classList.add('filtro-activo');
                // Ocultar los que NO son gratis
                document.querySelectorAll('.tarjeta-curso').forEach(tarjeta => {
                    const esGratis = tarjeta.textContent.includes('Gratis');
                    tarjeta.style.display = esGratis ? 'block' : 'none';
                });
            }
        });
    });

    // Buscador
    document.getElementById('buscar-cursos')?.addEventListener('input', function () {
        const texto = this.value.toLowerCase();
        document.querySelectorAll('.tarjeta-curso').forEach(tarjeta => {
            const coincide = tarjeta.textContent.toLowerCase().includes(texto);
            tarjeta.style.display = coincide ? 'block' : 'none';
        });
    });
});
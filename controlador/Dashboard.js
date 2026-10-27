document.addEventListener('DOMContentLoaded', () => {
    const Logueado =localStorage.getItem('logueado');
    if (Logueado !== 'true') {
        window.location.href = 'login.html';
        return;
    }

    const nombre = localStorage.getItem('nombre');
    const rol = localStorage.getItem('rol');
    document.getElementById('nombre').textContent = nombre;


    if (rol === 'admin') {
        document.getElementById('administrador').classList.remove('d-none');
        document.getElementById('asociado').classList.add('d-none');
    }
    else {
        document.getElementById('administrador').classList.add('d-none');
        document.getElementById('asociado').classList.remove('d-none');

    }
});
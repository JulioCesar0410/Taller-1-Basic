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

    document.addEventListener('DOMContentLoaded', () => {
    const Logueado =localStorage.getItem('logueado');

    if (Logueado !== 'true') {
        window.location.href = '../vista/login.html';
        return;
    }

    const nombre = localStorage.getItem('nombre');
    const rol = localStorage.getItem('rol');

    document.getElementById('NombreUsuario').textContent = nombre;

    if (rol === 'Administrador') {
        document.getElementById('TarjetasAdministrador').classList.remove('d-none');
        document.getElementById('TarjetaAsociados').classList.add('d-none');
    }else{
        document.getElementById('TarjetasAdministrador').classList.add('d-none');
        document.getElementById('TarjetaAsociados').classList.remove('d-none');  
    }
});

async function cerrarSesion() {
    try{
        const respuesta = await fetch("../modelo/cerrarSesion.php", 
            {method: "POST"
            });

            if (!respuesta.ok) {
                throw new Error("Error al cerrar sesion");
            }
      localStorage.clear();
      window.location.href = "../vista/login.html";
    }catch(error){
        console.error("Error al cerrar sesion:", error);
    }
}
});
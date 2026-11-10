const formulario = getElementById("Login");
formulario.addEventListener("submit", function (e){
    e.preventDefault();

    const boton = formulario.querySelector("button[type='submit']");
    const mensaje = formulario.getElementById("mensajelogin");


    boton.disabled = true;
    
    try{
        const respuesta = await fetch(formulario.action, {
            method: formulario.method,
            headers: {
                "Content-Type": "application/json"
            },
            //stringify convierte un objeto en una cadena de texto
            //Object.fromEntries convierte un objeto en un array de pares clave-valor
            //entries() devuelve un array de pares clave-valor de un objeto
            body: JSON.stringify(Object.fromEntries(new FormData(formulario).entries())),
        });


        const resultado = await respuesta.json();

        if (!resultado.success) {
            mensaje.textContent = resultado.message;
            boton.disabled = false;
            return;
        }

        localStorage.setItem('logueado', 'true');
        localStorage.setItem('rol', usuarioEncontrado.rol);
        localStorage.setItem('nombre', usuarioEncontrado.nombre);
        window.location.href = 'Dashboard.html';
    }catch {
    console.error('Error al enviar el formulario'); 
    mensaje.textContent = 'Error al enviar el formulario';
    }finally {
        boton.disabled = false;
    }


});
const formulario = document.getElementById("Registro");

formulario.addEventListener("submit", async function (event) {
    event.preventDefault();
    
    const botonGuardar = formulario.querySelector("button[type='submit']");

    const Asociado = Object.fromEntries(new FormData(formulario).entries());

    botonGuardar.disabled = true;
    
    try{
        const respuesta = await fetch(formulario.action,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(Asociado)});
        const resultado = await respuesta.json();

        document.getElementById("mensajeRegistro").textContent = "Guardando...";
        document.getElementById("mensajeRegistro").dataset.tipo = "";
        document.getElementById("mensajeRegistro").textContent = resultado.mensaje;

        if(respuesta.ok && resultado.sucess){
            formulario.reset(); 
        }

    }catch (error) {
        console.error("Error al enviar el forulario :",error);
        document.getElementById("mensajeRegistro").textContent = "Error al enviar el formulario. Por favor, inténtelo de nuevo.";
        
    }finally{
        botonGuardar.disabled = false; //Habilitar el botón después de que se complete la solicitud
    }
});     
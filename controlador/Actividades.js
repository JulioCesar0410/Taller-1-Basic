const listaActividades= document.getElementById("listaActividades");
async function solicitarApi(metodo, datos) {
    const opciones = {
        method: metodo,
        headers: {'Content-Type': 'application/json'},
    }
    if(datos){
        opciones.body = JSON.stringify(datos);
    }
     const respuesta = await fetch("../modelo/gestionarActividades.php", opciones);
     const resultado = await respuesta.json();
     if(respuesta.status === 401){
        window.location.href = "login.html";
     }
    
     if(!respuesta.ok  || !resultado.success){
        throw new Error(resultado.mensaje || "Error en la solicitud");
     }
     return resultado;


    async function cargarActividades() {
    try {
        const resultado = await solicitarApi("GET");
        // Procesar el resultado, por ejemplo, mostrar las actividades en la interfaz

        actividades = resultado.actividades; 
    
    } catch (error) {
        console.error("Error al cargar las actividades:", error);
    }

    function mostrarActividades() {
        
        
        if (actividades.length === 0) {
            const filas = document.createElement("tr");
            const celda = document.createElement("td");
            celda.colSpan = esAdministrador ? 5 : 4; // Ajusta el número de columnas según tu tabla
            celda.className = "text-center py-4 text-muted"; // Centrar el texto
            celda.textContent = "No hay actividades disponibles.";
            filas.appendChild(celda);
            listaActividades.appendChild(filas);
            return; // Salir de la función si no hay actividades
        }
    }

    actividades.forEach(actividad) => {
        const fila = document.createElement("tr");
        fila.append(
            crearCelda(actividad.idActividad),
            crearCelda(actividad.nombreActividad),
            crearCelda(actividad.fechaEvento),
            crearCelda(actividad.totalRecaudado===null ? "0" : actividad.totalRecaudado).toString()
        );

        if (esAdministrador) {
            const acciones = document.createElement("td");
            const botonEditar = document.createElement("button");
            botonEditar.textContent = "Editar";
            botonEditar.className = "btn btn-primary btn-sm me-2";
            botonEditar.innerHTML = '<i class="bi bi-pencil"></i> Editar';
            
            botonEliminiar = document.createElement("button");
            botonEliminiar.type = "button";
            botonEliminiar.className = "btn btn-danger btn-sm";
            botonEliminiar.innerHTML = '<i class="bi bi-trash"></i> Eliminar';
            botonEliminiar.addEventListener("click", () => eliminarActividad(actividad.idActividad));

            acciones.append(botonEditar, botonEliminiar);
            fila.append(acciones);
        }

        listaActividades.appendChild(fila);
    }
  }
}

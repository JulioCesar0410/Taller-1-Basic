const formulario = document.getElementById("login");

formulario.addEventListener("submit", async function (e) {
    e.preventDefault();

    const boton = formulario.querySelector("button[type='submit']");
    const mensaje = document.getElementById("mensajelogin");
    const credenciales = {
        email: document.getElementById("loginEmail").value.trim(),
        contrasena: document.getElementById("loginPassword").value
    };

    if (window.location.protocol === "file:") {
        mensaje.textContent = "Abre el formulario desde Laragon/Apache, no directamente como archivo.";
        return;
    }

    boton.disabled = true;
    mensaje.textContent = "Ingresando...";

    try {
        const respuesta = await fetch(formulario.action, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8"
            },
            body: new URLSearchParams(credenciales).toString(),
        });

        const contenido = await respuesta.text();
        let resultado;
        try {
            resultado = JSON.parse(contenido.replace(/^\uFEFF/, "").trim());
        } catch (error) {
            const tipoContenido = respuesta.headers.get("content-type") || "desconocido";
            console.error("Respuesta no JSON del servidor:", respuesta.status, tipoContenido, contenido);
            mensaje.textContent = contenido.includes("<?php")
                ? "El proyecto se está sirviendo sin ejecutar PHP. Ábrelo desde Apache/Laragon: http://localhost/Taller-1-Basic/Vista/login.html"
                : `El servidor devolvió una respuesta no JSON (HTTP ${respuesta.status}, ${tipoContenido}). Abre el proyecto desde Apache/Laragon y revisa la consola para más detalles.`;
            return;
        }

        if (!respuesta.ok || !resultado.success) {
            mensaje.textContent = resultado.message || "No fue posible iniciar sesión.";
            return;
        }

        try {
            localStorage.setItem("logueado", "true");
            localStorage.setItem("rol", resultado.rol);
            localStorage.setItem("nombre", resultado.nombre);
        } catch (error) {
            console.error("No se pudo guardar la sesión en el navegador:", error);
            mensaje.textContent = "Inicio de sesión correcto, pero el navegador no permitió guardar la sesión.";
            return;
        }

        window.location.href = 'Dashboard.html';
    } catch (error) {
        console.error("Error al iniciar sesión:", error);
        mensaje.textContent = "No se pudo conectar con el servidor PHP. Verifica que Laragon/Apache esté activo y abre el proyecto desde localhost.";
    } finally {
        boton.disabled = false;
    }
});
const usuarios = [
    {usuario: 'admin@gmail.com', clave:'123', rol:'admin', nombre:'cesar'},
    {usuario: 'juan@gmail.com', clave:'123', rol:'usuario', nombre:'juan'}
];

    document.getElementById("login").addEventListener('submit', function (e) {
    e.preventDefault();
    const usuarioInput = document.getElementById('loginEmail').value;
    const contrasenainput = document.getElementById('loginPassword').value;

    const usuarioencotrado = usuarios.find(
        u => u.usuario === usuarioInput && u.clave === contrasenainput);
    

    if(usuarioencotrado){
    localStorage.setItem('logueado', 'true');
    localStorage.setItem('rol', usuarioencotrado.rol);
    localStorage.setItem('nombre',usuarioencotrado.nombre);
    window.location.href='Dashboard.html';
    }else{
     alert('usuario o contraseña incorrecta')
    }
});
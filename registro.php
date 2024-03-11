<?php

require_once './config/config.php';
require_once './config/Database.php';
require_once './clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $documento = trim($_POST['documento']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $telefono, $documento, $usuario, $password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es válida";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "El correo electrónico $email ya existe";
    }

    if (count($errors) == 0) {

        $id = registrarCliente([$nombres, $apellidos, $email, $telefono, $documento], $con);

        if ($id > 0) {

            require_once 'clases/Mailer.php';

            $token = generarToken();
            $mailer = new Mailer();
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);


            $idUsuario = registrarUsuario([$usuario, $pass_hash, $token, $id], $con);
            if ($idUsuario > 0) {

                $url = SITE_URL . '/activar_cliente.php?id=' . $idUsuario . '&token=' . $token;

                $asunto = "Activar cuenta - Pasadena Online";
                $cuerpo = "Estimado $nombres: <br> Para continuar con el proceso de registro es indispensable de click
                en la siguinte boton. <br> <a href='$url'>Activar cuenta</a>";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado a la 
                    dirección de correo electrónico $email";

                    exit;
                }

            } else {
                $errors[] = "Error al registrar usuario";
            }

        } else {
            $errors[] = "Error al registrar cliente";
        }

    }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasadena</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./css/estilos.css" rel="stylesheet">
</head>

<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container">
        <h2>Datos del Cliente</h2>

        <?php mostrarMensajes($errors); ?>

        <form class="row g-3" action="registro.php" method="post" autocomplete="off">
            <div class="col-md-6">
                <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                <input type="text" name="nombres" id="nombres" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="email"><span class="text-danger">*</span> Correo Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" requireda>
                <span id="validaEmail" class="text-danger"></span>
            </div>
            <div class="col-md-6">
                <label for="telefono"><span class="text-danger">*</span> Telefono</label>
                <input type="tel" name="telefono" id="telefono" class="form-control" requireda>
            </div>

            <div class="col-md-6">
                <label for="documento"><span class="text-danger">*</span> Número de Documento (C.C)</label>
                <input type="text" name="documento" id="documento" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="usuario"><span class="text-danger">*</span> Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" requireda>
                <span id="validaUsuario" class="text-danger"></span>
            </div>

            <div class="col-md-6">
                <label for="password"><span class="text-danger">*</span> Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" requireda>
            </div>
            <div class="col-md-6">
                <label for="repassword"><span class="text-danger">*</span> Repetir Contraseña</label>
                <input type="password" name="repassword" id="repassword" class="form-control" requireda>
            </div>

            <i><b>Nota:</b> Los campos con asterisco son obligatorios.</i>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>

        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

<script>

    let txtUsuario = document.getElementById('usuario')
    txtUsuario.addEventListener("blur", function () {
        existeUsuario(txtUsuario.value)
    }, false)

    let txtEmail = document.getElementById('email')
    txtEmail.addEventListener("blur", function () {
        existeEmail(txtEmail.value)
    }, false)

    function existeUsuario(usuario) {

        let url = "clases/clienteAjax.php"
        let formData = new FormData()

        formData.append("action", "existeUsuario")
        formData.append("usuario", usuario)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
            .then(data => {

                if (data.ok) {
                    document.getElementById('usuario').value = ''
                    document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
                } else {
                    document.getElementById('validaUsuario').innerHTML = ''
                }

            })

    }

    function existeEmail(email) {

        let url = "clases/clienteAjax.php"
        let formData = new FormData()

        formData.append("action", "existeEmail")
        formData.append("email", email)

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
            .then(data => {

                if (data.ok) {
                    document.getElementById('email').value = ''
                    document.getElementById('validaEmail').innerHTML = 'Email no disponible'
                } else {
                    document.getElementById('validaEmail').innerHTML = ''
                }

            })

    }
</script>

</body>

</html>
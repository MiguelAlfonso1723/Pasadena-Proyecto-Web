<?php

require './config/config.php';
require './config/database.php';
require './clases/clienteFunciones.php';

$db = new database();
$con = $db->conectar();

$errors = [];

if (!empty($_POST)) {

    $email = trim($_POST['email']);

    if (esNulo([$email])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!esEmail($email)) {
        $errors[] = "La dirección de correo no es válida";
    }

    if (count($errors) == 0) {
        if (emailExiste($email, $con)) {
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes ON usuarios.id_cliente=clientes.id WHERE clientes.email LIKE ? LIMIT 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $idUsuario = $row['id'];
            $nombres = $row['nombres'];

            $token = solicitaPasssword($idUsuario, $con);

            if ($token !== null) {
                require 'clases/Mailer.php';
                $mailer = new Mailer();

                $url = SITE_URL . '/reset_password.php?id=' . $idUsuario . '&token=' . $token;

                $asunto = "Recuperar contraseña - Pasadena Online";
                $cuerpo = "Estimado $nombres: <br> Si has solicitado el cambio de tu contraseña da clic en el siguiente link <br> <a href='$url'>$url</a>";
                $cuerpo .= "<br>Si no solicitaste recuperar contraseña puedes ignorar este correo.";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "<p><b>Correo enviado</b></p>";
                    echo "<p><b>Hemos enviado un correo electrónico a la direccion $email para restablecer la contraseña</b></p>";

                    exit;
                }
            }
        } else {
            $errors[] = "No existe una cuenta asociada a esta dirección de correo";
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

<main class="form-login m-auto pt-4">
    <h3>Recuperar contraseña</h3>

    <?php mostrarMensajes($errors); ?>

    <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

        <div class="form-floating">
            <input class="form-control" type="email" name="email" id="email" placeholder="Correo Electrónico" required>
            <label for="email">Correo Electrónico</label>
        </div>

        <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-success">Continuar</button>
        </div>

        <div class="col-12" style="text-align:center">
            ¿No tiene cuenta? <a href="registro.php">Registrate aquí</a>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>
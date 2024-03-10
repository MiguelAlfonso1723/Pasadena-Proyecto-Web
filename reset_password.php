<?php

require './config/config.php';
require './config/database.php';
require './clases/clienteFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if ($user_id == '' || $token == '') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if (!verificarTokenRequest($user_id, $token, $con)){
echo  "No se pudo verificar la informacion";
exit;
}

if (!empty($_POST)) {

    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$user_id, $token ,$password, $repassword])) {
        $errors[] = "Debe llenar todos los campos";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (count($errors) == 0){
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if (actualizaPassword($user_id, $pass_hash, $con)){
            echo "Contraseña modificada.<br><a href='login.php'>Iniciar sesión</a>";
            exit;
        } else{
            $errors[] = "Error al modificar la contraseña. Intentalo nuevamente.";
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

<header class="py-3 mb-4 border-bottom">
    <div class="navbar navbar-expand-lg container d-flex flex-wrap justify-content-center">
        <a href="index.php"
           class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto text-dark text-decoration-none">
            <span class="fs-4"><img src="./images/logoPasadena.png" width="180"></span>

        </a>
        &emsp;
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarHeader">
            <ul class="navbar-nav me-auto mb-2 mg-lg-0">
                <li class="nav-item">
                    <a href="index.php" class="nav-link active">Catalogo</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link ">Contacto</a>
                </li>
            </ul>

            <a href="checkout.php"><img src="./images/3144456.png" width="40"><br><span id="num_cart"
                                                                                        class="badge rounded-pill bg-danger"
                                                                                        style="col">
                        <?php echo $num_cart; ?>
                    </span></a>
        </div>
        <nav class="py-2 bg-light border-bottom">
    </div>


    </nav>
</header>

<main class="form-login m-auto pt-4">
    <h3>Cambiar contraseña</h3>

    <?php mostrarMensajes($errors); ?>

    <form action="reset_password.php" method="post" class="row g-3" autocomplete="off">

        <input type="hidden" name="user_id" id="user_id" value="<?= $user_id; ?>">
        <input type="hidden" name="token" id="token" value="<?= $token; ?>">

        <div class="form-floating">
            <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" required>
            <label for="password">Nueva contraseña</label>
        </div>

        <div class="form-floating">
            <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Confirmar contraseña" required>
            <label for="repassword">Confirmar contraseña</label>
        </div>

        <div class="d-grid gap-3 col-12">
            <button type="submit" class="btn btn-success">Continuar</button>
        </div>

        <div class="col-12" style="text-align:center">
            <a href="login.php">Iniciar sesión</a>
        </div>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>


</body>

</html>
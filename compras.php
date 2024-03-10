<?php

require './config/config.php';
require './config/database.php';
require './clases/clienteFunciones.php';

$db = new database();
$con = $db->conectar();

$token =generarToken();
$_SESSION['token'] = $token;

$idCliente = $_SESSION['user_cliente'];

$sql = $con->prepare("SELECT id_transaccion, fecha, status, total FROM compra WHERE id_cliente = ? ORDER BY DATE(fecha) DESC ");
$sql->execute([$idCliente]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasadena Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./css/estilos.css" rel="stylesheet">
</head>

<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container">
        <h4>Mis compras</h4>

        <hr>

        <?php while ($row = $sql->fetch(PDO::FETCH_ASSOC)) { ?>

            <div class="card border-dark mb-3">
                <div class="card-header">
                    <?php echo $row['fecha']; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Numero de compra: <?php echo $row['id_transaccion']; ?></h5>
                    <p class="card-text">Total: $<?php echo $row['total']; ?></p>
                    <a href="compra_detalle.php?orden=<?php echo $row['id_transaccion']; ?>&token=<?php echo $token; ?> " class="btn btn-primary">Ver compra</a>
                </div>
            </div>

        <?php } ?>

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</body>

</html>
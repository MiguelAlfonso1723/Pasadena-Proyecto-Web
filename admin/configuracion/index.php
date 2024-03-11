<?php

require_once '../config/Database.php';
require_once '../config/config.php';
require_once '../header.php';

if (!isset($_SESSION['user_type'])){
    header('Location: ../index.php');
    exit;
}
if ($_SESSION['user_type'] != 'admin'){
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datos as $dato) {
    $config[$dato['nombre']] = $dato['valor'];
}

?>

    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Configuración</h1>

            <form action="guarda.php" method="post">

                <div class="row">

                    <h2>Correo electrónico</h2>

                    <div class="col-6">
                        <label for="smtp">SMTP</label>
                        <input class="form-control" type="text" name="smtp" id="smtp"
                               value="<?php echo $config ['correo_smtp'] ?>">
                    </div>

                    <div class="col-6">
                        <label for="puerto">Puerto</label>
                        <input class="form-control" type="text" name="puerto" id="puerto"
                               value="<?php echo $config ['correo_puerto'] ?>">
                    </div>

                    <div class="col-6">
                        <label for="email">Correo electrónico</label>
                        <input class="form-control" type="email" name="email" id="email"
                               value="<?php echo $config ['correo_email'] ?>">
                    </div>

                    <div class="col-6">
                        <label for="password">Contraseña</label>
                        <input class="form-control" type="password" name="password" id="password"
                               value="<?php echo $config ['correo_password'] ?>">
                    </div>

                    <hr>
                    <h2>Paypal</h2>

                    <div class="col-6">
                        <label for="paypal_cliente">Cliente ID</label>
                        <input class="form-control" type="text" name="paypal_cliente" id="paypal_cliente"
                               value="<?php echo $config ['paypal_cliente'] ?>">
                    </div>
                    <div class="col-6">
                        <label for="paypal_moneda">Moneda</label>
                        <input class="form-control" type="text" name="paypal_moneda" id="paypal_moneda"
                               value="<?php echo $config ['paypal_moneda'] ?>">
                    </div>

                    <hr>
                    <h2>Mercado Pago</h2>

                    <div class="col-6">
                        <label for="mp_token">Token</label>
                        <input class="form-control" type="text" name="mp_token" id="mp_token"
                               value="<?php echo $config ['mp_token'] ?>">
                    </div>
                    <div class="col-6">
                        <label for="mp_clave">Clave pública</label>
                        <input class="form-control" type="text" name="mp_clave" id="mp_clave"
                               value="<?php echo $config ['mp_clave'] ?>">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>

            </form>

        </div>
    </main>

<?php require_once '../footer.php'; ?>
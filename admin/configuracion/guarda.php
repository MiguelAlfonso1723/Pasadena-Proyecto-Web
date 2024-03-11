<?php

require_once '../config/Database.php';
require_once '../config/config.php';
require_once '../header.php';
require_once '../clases/cifrado.php';

$db = new Database();
$con = $db->conectar();

$smtp = $_POST['smtp'];
$puerto = $_POST['puerto'];
$email = $_POST['email'];
$password = cifrar($_POST['password']);

$paypal_cliente = $_POST['paypal_cliente'];
$paypal_moneda = $_POST['paypal_moneda'];

$mp_token = $_POST['mp_token'];
$mp_clave = $_POST['mp_clave'];

$sql = $con->prepare("UPDATE configuracion SET valor= ? WHERE nombre = ?");
$sql->execute([$smtp, 'correo_smtp']);
$sql->execute([$puerto, 'correo_puerto']);
$sql->execute([$email, 'correo_email']);
$sql->execute([$password, 'correo_password']);
$sql->execute([$paypal_cliente, 'paypal_cliente']);
$sql->execute([$paypal_moneda, 'paypal_moneda']);
$sql->execute([$mp_token, 'mp_token']);
$sql->execute([$mp_clave, 'mp_clave']);

?>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Configuración actualizada</h2>
            <a href="index.php" class="btn btn-secondary">Regresar</a>
        </div>
    </main>

<?php include '../footer.php'; ?>
<?php

$path = dirname(__FILE__);

require_once $path.'/database.php';
require_once $path.'/../admin/clases/cifrado.php';

$db = new Database();
$con = $db->conectar();

$sql = "SELECT nombre, valor FROM configuracion";
$resultado = $con->query($sql);
$datos = $resultado->fetchAll(PDO::FETCH_ASSOC);

$config = [];

foreach ($datos as $dato) {
    $config[$dato['nombre']] =$dato['valor'];
}

//Configuración del Sistema
define("SITE_URL", "http://localhost:8080/Proyecto-Git");
define("KEY_TOKEN", "AAMSAA.cpk-7536@");
define("MONEDA", "$");

//Configuración para PayPal
define("CLIENT_ID", "AWMr8RrqdCZ_fWqVwS3GmTk-CGBnICWhcGEwAgsQqEF59birbkzmQdLewe8104cKsG9tINQo6nNZfB0w");
define("CURRENCY", "USD");

//Configuración para Mercado Pago
define("TOKEN_MP", "TEST-6196276162443239-021316-08f05187ce86f0fd849522d5df55fed8-1679968963");
define("PUBLIC_KEY_MP", "TEST-264d235b-f848-4e7f-95eb-2fabf2ff31ef");
define("LOCALE_MP", "es-CO");



//Datos para envio de correo Electronico
define("MAIL_HOST", $config['correo_smtp']);
define("MAIL_USER", $config['correo_email']);
define("MAIL_PASS", descifrar($config['correo_password']));
define("MAIL_PORT", $config['correo_puerto']);

session_start();

$num_cart = 0;

if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

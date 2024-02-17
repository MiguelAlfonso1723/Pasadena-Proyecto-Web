<?php

define("CLIENT_ID", "AWMr8RrqdCZ_fWqVwS3GmTk-CGBnICWhcGEwAgsQqEF59birbkzmQdLewe8104cKsG9tINQo6nNZfB0w");
define("TOKEN_MP", "TEST-6196276162443239-021316-08f05187ce86f0fd849522d5df55fed8-1679968963");
define("CURRENCY", "USD");
define("KEY_TOKEN", "AAMSAA.cpk-7536@");
define("MONEDA", "$");

session_start();

$num_cart = 0;

if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}

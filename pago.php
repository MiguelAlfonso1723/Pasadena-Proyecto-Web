<?php

require_once 'config/config.php';
require_once 'config/Database.php';
require_once 'vendor/autoload.php';

MercadoPago\SDK::setAccessToken(TOKEN_MP);

$preference = new MercadoPago\Preference();
$productos_mp = array();

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo = 1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: index.php");
    exit;
}


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

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>">
    </script>
</head>

<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container">

        <div class="row">
            <div class="col-6">
                <h4>Detalles de pago</h4>
                <div class="row">
                    <div class="col-10">
                        <div id="paypal-button-container"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-10 text-center">
                        <div class="checkout-btn"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody class="table-group-divider">
                        <?php if ($lista_carrito == null) {
                            echo '<tr><td colspan="6" class="text-center"><b>Lista vacia</b></td></tr>';
                        } else {
                        $total = 0;
                        foreach ($lista_carrito as $produto) {
                            $_id = $produto['id'];
                            $nombre = $produto['nombre'];
                            $precio = $produto['precio'];
                            $descuento = $produto['descuento'];
                            $cantidad = $produto['cantidad'];
                            $precio_desc = $precio - (($precio * $descuento) / 100);
                            $subtotal = $cantidad * $precio_desc;
                            $total += $subtotal;

                            $item = new MercadoPago\Item();
                            $item->id = $_id;
                            $item->title = $nombre;
                            $item->quantity = $cantidad;
                            $item->unit_price = $precio_desc;
                            $item->currency_id = "COP";

                            array_push($productos_mp, $item);
                            unset($item);
                            ?>

                            <tr>
                                <td>
                                    <?php echo $nombre; ?>
                                </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                        <?php echo $cantidad . ' x ' . MONEDA . "<b>" . number_format($subtotal, 2, '.', ',') . "</b>"; ?>
                                    </div>
                                </td>

                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="2">
                                <p class="h3 text-end" id="total">
                                    <?php echo MONEDA . number_format($total, 2, ".", ","); ?>
                                </p>
                            </td>
                        </tr>
                        </tbody>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php

$preference->items = $productos_mp;

$preference->back_urls = array(

    "success" => "http://localhost:8080/Proyecto-Git/captura.php",

);

$preference->auto_return = "approved";
$preference->binary_mode = true;

$preference->save();

?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>


<script>
    paypal.Buttons({
        style: {
            color: 'blue',
            shape: 'pill',
            label: 'pay'
        },
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: <?php echo number_format($total * 0.00025, 2, ".", ","); ?>

                    }
                }]
            });
        },
        onApprove: function (data, actions) {
            actions.order.capture().then(function (detalles) {

                console.log(detalles)

                let url = 'clases/captura.php'

                return fetch(url, {
                    method: 'post',
                    headers: {
                        'content-type': 'application/json'
                    },
                    body: JSON.stringify({
                        detalles: detalles
                    })
                }).then(function (response) {
                    window.location.href = "complete.php?key=" + detalles['id'];
                })

            });

        },

        onCancel: function (data) {
            alert("Pago Cancelado")
            console.log(data)
        }
    }).render('#paypal-button-container');

    const mp = new MercadoPago('<?php echo PUBLIC_KEY_MP; ?>', {
        locale: '<?php echo LOCALE_MP; ?>'
    });

    mp.checkout({
        preference: {
            id: '<?php echo $preference->id; ?>'
        },
        render: {
            container: '.checkout-btn',
            type: 'wallet',
            label: 'Pagar con Mercado Pago'

        }
    })
</script>


</body>

</html>
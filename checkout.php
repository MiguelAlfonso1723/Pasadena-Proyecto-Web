<?php

require './config/config.php';
require './config/database.php';
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
}


//session_destroy();

//print_r($_SESSION);


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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                <?php if ($lista_carrito == null) {
                    echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
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
                    ?>

                    <tr>
                        <td>
                            <?php echo $nombre; ?>
                        </td>
                        <td>
                            <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                        </td>
                        <td>
                            <input type="number" min="1" max="12" step="1" value="<?php echo $cantidad ?>" size="5"
                                   , id="cantidad_<?php echo $_id; ?>"
                                   onchange="actualizarContenido(this.value, <?php echo $_id ?>)">
                        </td>
                        <td>
                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                            </div>
                        </td>
                        <td>
                            <a href="#" id="eliminar" class="btn btn-warning btn-sm"
                               data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal"
                               data-bs-target="#eliminarModal"><i class="bi bi-trash-fill"></i>
                            </a>
                        </td>

                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="3"></td>
                    <td colspan="2">
                        <p class="h3" id="total">
                            <?php echo MONEDA . number_format($total, 2, ".", ","); ?>
                        </p>
                    </td>
                </tr>
                </tbody>
                <?php } ?>
            </table>

        </div>

        <?php if ($lista_carrito != null) { ?>

            <div class="row">
                <div class="col-md-5 offset-md-7 d-grid gap-2">

                    <a href="pago.php" button class="btn btn-success bt-lg">Realizar pago

                </div>
            </div>

        <?php } ?>

    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="eliminarModalLabel">Notifiación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Esta seguro de eliminar el producto del carrito?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id='btn-eliminar' type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
<script>

    let eliminaModal = document.getElementById('eliminarModal')
    eliminaModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')
        let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-eliminar')
        buttonElimina.value = id
    })

    function actualizarContenido(contenido, id) {
        let url = './clases/actualizar_carro.php'
        let formData = new FormData()
        formData.append('action', 'agregar')
        formData.append('id', id)
        formData.append('contenido', contenido)

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json()
            .then(data => {
                if (data.ok) {

                    let divisubtotal = document.getElementById('subtotal_' + id)
                    divisubtotal.innerHTML = data.sub

                    let total = 0.00
                    let list = document.getElementsByName('subtotal[]')

                    for (let i = 0; i < list.length; i++) {
                        total += parseFloat(list[i].innerHTML.replace(/[$,]/g, ''))
                    }

                    total = new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2
                    }).format(total)
                    document.getElementById('total').innerHTML = '<?php echo MONEDA; ?>' + total
                }
            }))
    }

    function eliminar() {

        let botonEliminar = document.getElementById('btn-eliminar')
        let id = botonEliminar.value

        let url = './clases/actualizar_carro.php'
        let formData = new FormData()
        formData.append('action', 'eliminar')
        formData.append('id', id)

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json()
            .then(data => {
                if (data.ok) {
                    location.reload()

                }
            }))
    }
</script>
</body>

</html>
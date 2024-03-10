<?php

require './config/config.php';
require './config/database.php';
$db = new database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar la informacion';
    exit;
} else {

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        if ($sql->fetchAll() > 0) {
            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $descripcion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);

            $dir_images = './images/Productos/' . $id . '/';

            $rutaImg = $dir_images . 'Principal.png';

            if (!file_exists($rutaImg)) {
                $rutaImg = './images/no-photo.png';
            }

            $imagenes = array();
            if (file_exists($dir_images)) {
                $dir = dir($dir_images);

                while (($archivo = $dir->read()) != false) {
                    if ($archivo != 'Principal.png' && (strpos($archivo, 'png') || strpos($archivo, 'jpeg'))) {
                        $imagenes[] = $dir_images . $archivo;
                    }
                }
                $dir->close();
            }


        }
    } else {
        echo 'Error al procesar la informacion';
        exit;
    }

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
</head>

<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container">
        <div class="row">
            <dir class="col-md-6 order-md-1">
                <div id="carouselImages" class="carousel carousel-dark slide" data-bs-slide="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="<?php echo $rutaImg; ?>" class="d-block w-100">
                        </div>

                        <?php foreach ($imagenes as $img) { ?>
                            <div class="carousel-item">
                                <img src="<?php echo $img; ?>" class="d-block w-100">
                            </div>
                        <?php } ?>

                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>


            </dir>
            <dir class="col-md-6 order-md-2">
                <h2>
                    <?php echo $nombre; ?>
                </h2>

                <?php if ($descuento > 0) { ?>
                    <p>
                        <del>
                            <?php echo MONEDA . number_format($precio, 2, '.', ','); ?>
                        </del>
                    </p>
                    <h2>
                        <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
                        <small class="text-success">
                            <?php echo $descuento; ?>% de descuento
                        </small>
                    </h2>

                <?php } else { ?>
                    <h2>
                        <?php echo MONEDA . number_format($precio, 2, '.', ','); ?>
                    </h2>
                <?php } ?>

                <br>


                <div class="col-3 my-3-center">
                    Cantidad: <input class="form-control" id="cantidad" name="cantidad" type="number" min="1" max="20"
                                     value="1">
                </div>

                <br>

                <dir class="d-grid gap-3 col-10 mx-auto">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                            data-bs-target="#details"
                            aria-expanded="false" aria-controls="details">
                        Detalles
                    </button>


                    <div class="collapse" id="details">
                        <div class="card card-body">
                            <p class="lead">
                                <?php echo $descripcion; ?>
                            </p>
                        </div>
                    </div>


                    <button class="btn btn-success" type="button">Comprar Ahora</button>
                    <button class="btn btn-outline-warning" id="btnAgregar" type="button">Agregar al carrito</button>
                    <!-- <button class="btn btn-outline-warning" type="button" onclick="addProducto(<?php echo
                    $id; ?>, cantidad.value,'<?php echo $token_tmp; ?>')">Agregar al carrito</button> -->

                </dir>
            </dir>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

<script>

    document.getElementById("btnAgregar").addEventListener('click', () => {
        let inputCantidad = document.getElementById("cantidad").value
        addProducto(<?php echo
        $id; ?>, inputCantidad, '<?php echo $token_tmp; ?>')
    })


    function addProducto(id, cantidad, token) {
        let url = './clases/carrito.php'
        let formData = new FormData()
        formData.append('id', id)
        formData.append('cantidad', cantidad)
        formData.append('token', token)


        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
            .then(data => {
                if (data.ok) {
                    let elemento = document.getElementById("num_cart")
                    elemento.innerHTML = data.numero
                }
            })
    }
</script>
</body>

</html>

<?php

require './config/config.php';
require './config/database.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if($productos != null){
    foreach($productos as $clave => $cantidad){
        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo = 1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}



//session_destroy();

print_r($_SESSION);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasadena Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="./css/estilos.css" rel="stylesheet">
</head>
<body>
   
  <header class="py-3 mb-4 border-bottom">
    <div class="navbar navbar-expand-lg container d-flex flex-wrap justify-content-center">
      <a href="/" class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto text-dark text-decoration-none">
        <span class="fs-4" ><img src="./images/logoPasadena.png"  width="180" ></span>
        
      </a>
      &emsp;
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mg-lg-0">
            <li class="nav-item">
                <a href="#" class="nav-link active">Catalogo</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link ">Contacto</a>
            </li>
        </ul>

        <a href="carrito.php" ><img src="./images/3144456.png" width="40"><br><span id="num_cart" class="badge rounded-pill bg-danger" style="col"><?php echo $num_cart; ?></span></a>
      </div>
    <nav class="py-2 bg-light border-bottom">
    </div>
    
    
  </nav>
  </header>

  <main >
    <div class="container">
        <div class = "table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($lista_carrito == null){
                        echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                    } else{
                        $total = 0;
                        foreach($lista_carrito as $produto){
                            $_id = $produto['id'];
                            $nombre = $produto['nombre']; 
                            $precio = $produto['precio'];
                            $descuento = $produto['descuento'];
                            $cantidad  = $produto['cantidad'];
                            $precio_desc = $precio - (($precio * $descuento)/100);
                            $subtotal = $cantidad * $precio_desc;
                            $total += $subtotal;
                        ?>
                    
                    <tr>
                        <td><?php echo $nombre; ?></td>
                        <td><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?></td>
                        <td>
                            <input type="number" min="1" max="12" step="1" value="<?php echo $cantidad ?>" 
                            size="5", id="cantidad_<?php echo $_id; ?>" onchange="">
                        </td>
                        <td>
                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                            </div>
                        </td>
                        <td>
                            <a href="#" id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php echo $_id; ?>" 
                            data-bs-toggle="modal" data-bs-target="eliminarModal">Eliminar</a>
                        </td>
                        
                    </tr>
                    <?php }?>
                    <tr>
                        <td colspan="3"></td>
                        <td colspan="2">
                            <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, ".", ","); ?></p>
                        </td>
                    </tr>
                </tbody>
            <?php }?>
            </table>

        </div>

        <div class="row">
            <div class="col-md-5 offset-md-7 d-grid gap-2">
                <button class="btn btn-success bt-lg">Realizar pago</button>
            </div>
        </div>


    </div>
  </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
      function addProducto(id, token){
          let url= './clases/carrito.php'
          let formData = new FormData()
          formData.append('id', id)
          formData.append('token', token)

          fetch(url, {
            method:'POST', 
            body:formData,
            mode: 'cors'
          }).then(response => response.json()
          .then(data => {
            if(data.ok){
              let elemento =document.getElementById("num_cart")
              elemento.innerHTML= data.numero
            }
          }))
      }
    </script>
  </body>
</html>
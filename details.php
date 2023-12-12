
<?php

require './config/config.php';
require './config/database.php';
$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if($id == '' || $token == ''){
  echo 'Error al procesar la informacion';
  exit;
}else {

  $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

  if($token == $token_tmp){
    $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1 LIMIT 1");
    $sql->execute([$id]);
    if($sql->fetchAll() > 0){
      $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1");
      $sql->execute([$id]);
      $row = $sql->fetch(PDO::FETCH_ASSOC);
      $nombre = $row['nombre'];
      $descripcion = $row['descripcion'];
      $precio = $row['precio'];
      $descuento = $row['descuento'];
      $precio_desc = $precio-(($precio*$descuento)/100);

      $dir_images = './images/Productos/'. $id .'/';

      $rutaImg = $dir_images . 'Principal.png';

      if(!file_exists($rutaImg)){
          $rutaImg = './images/no-photo.png';
      }

      $imagenes =  array();
      if(file_exists($dir_images)){
        $dir = dir($dir_images);

        while(($archivo = $dir->read()) != false){  
            if($archivo != 'Principal.png' && (strpos($archivo,'png') || strpos($archivo, 'jpeg'))){
              $imagenes[] = $dir_images . $archivo;
            } 
        }
        $dir->close();
      }
      
      
    }
  }else{
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

        <a href="checkout.php" ><img src="./images/3144456.png" width="40"><br><span id="num_cart" class="badge bg-secondary" ><?php echo $num_cart; ?></span></a>
      </div>
    <nav class="py-2 bg-light border-bottom">
    </div>
    
    
  </nav>
  </header>

  <main >
    <div class="container">
      <div class="row">
        <dir class="col-md-6 order-md-1">
          <div id="carouselImages" class="carousel carousel-dark slide" data-bs-slide="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="<?php echo $rutaImg; ?>" class="d-block w-100">
              </div>

              <?php foreach ($imagenes as $img){ ?>
                <div class="carousel-item">
                  <img src="<?php echo $img; ?>" class="d-block w-100">
                </div>
              <?php } ?>

            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>

          
        </dir>
        <dir class="col-md-6 order-md-2">
          <h2><?php echo $nombre;  ?></h2>

          <?php if($descuento > 0){ ?>
            <p><del><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></del></p>
            <h2>
              <?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?>
              <small class="text-success"><?php echo $descuento;  ?>% de descuento</small>
            </h2>
          
          <?php } else { ?>
            <h2><?php echo MONEDA . number_format($precio, 2, '.', ','); ?></h2>
          <?php } ?>
          
          <p class="lead">
            <?php echo $descripcion; ?>
          </p>

          <dir class="d-grid gap-3 col-10 mx-auto">
            <button class="btn btn-success" type="button">Comprar Ahora</button>
            <button class="btn btn-outline-warning" type="button" onclick="addProducto(<?php echo 
            $id; ?>, '<?php echo $token_tmp; ?>')">Agregar al carrito</button>


          </dir>
        </dir>
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
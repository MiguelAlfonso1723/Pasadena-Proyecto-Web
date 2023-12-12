
<?php

require './config/config.php';
require './config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo = 1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
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

        <a href="carrito.php" class=""><img src="./images/3144456.png" width="40"><br><p>Carrito</p></a>
      </div>
    <nav class="py-2 bg-light border-bottom">
    </div>
    
    
  </nav>
  </header>

  <main >
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php foreach($resultado as $row){ ?>
          <div class="col">
            <div class="card shadow-sm">
              <?php 

                $id = $row['id'];
                $imagen = "./images/Productos/". $id . "/Principal.png";

                if(!file_exists($imagen)){
                  $imagen = "./images/no-photo.png";
                }
              ?>
              <span style="text-align: center;" class="d-block w-100"><img src="<?php echo $imagen; ?>" ></span>
            
              <div class="card-body">
                <h5 class="card-title"><?php echo $row['nombre'] ?></h5>
                <p class="card-text">$ <?php echo number_format($row['precio'] , 2, '.', ','); ?></p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group">
                    <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo
                    hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-warning">Detalles</a>
                  </div>
                  <a href="" class="btn btn-danger">Agregar</a>
                </div>
              </div>
            </div>
          </div>
       <?php }?>
      </div>
    </div>
  </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
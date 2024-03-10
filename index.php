<?php

require './config/config.php';
require './config/database.php';

$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo = 1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
  
</head>

<body>

<?php include 'menu.php'; ?>

  <main class="flex-shrink-0">
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php foreach ($resultado as $row) { ?>
          <div class="col mb-2">
            <div class="card shadow-sm h-100">
              <?php

              $id = $row['id'];
              $imagen = "./images/Productos/" . $id . "/Principal.png";

              if (!file_exists($imagen)) {
                $imagen = "./images/no-photo.png";
              }
              ?>
              <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo
                   hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>"><img class="card-img-top"
                  src="<?php echo $imagen; ?>"></a>

              <div class="card-body">
                <p class="card-title">
                  <?php echo $row['nombre'] ?>
                </p>
                <p class="card-text">
                  <strong>$ <?php echo number_format($row['precio'], 2, '.', ','); ?></strong>
                </p>
                <div class="card-footer bg-transparent">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo
                           hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" class="btn btn-warning">Detalles</a>
                    </div>
                    <a class="btn btn-outline-success" onclick="addProducto(<?php echo
                      $row['id']; ?>, '<?php echo
                       hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar</a>
                  </div>
                </div>

              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
  <script>
    
    function addProducto(id, token) {
      let url = './clases/carrito.php'
      let formData = new FormData()
      formData.append('id', id)
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
<?php

require_once '../config/Database.php';
require_once '../config/config.php';
include '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'];

$sql = $con->prepare("SELECT nombre, descripcion, descuento, precio, stock, descuento, id_categoria FROM productos WHERE id= ? AND activo = 1");
$sql->execute([$id]);
$producto = $sql->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

$rutaImagenes = '../../images/Productos/' . $id . '/';
$imagenPrincipalJPEG = $rutaImagenes . 'Principal.jpeg';
$imagenPrincipalPNG = $rutaImagenes . 'Principal.png';

if (file_exists($imagenPrincipalJPEG)) {
    $imagenPrincipal = $imagenPrincipalJPEG;
} elseif (file_exists($imagenPrincipalPNG)) {
    $imagenPrincipal = $imagenPrincipalPNG;
} else {
    $imagenPrincipal = null; // Maneja el caso de que no se encuentre ninguna imagen
}

?>

<style>
    .ck-editor__editable[role='textbox'] {
        min-height: 250px;
    }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>


<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">Modifica producto</h2>

        <form action="guarda.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre"
                       value="<?php echo $producto['nombre'] ?>" required autofocus>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripcion</label>
                <textarea class="form-control" name="descripcion"
                          id="editor"><?php echo $producto['descripcion'] ?></textarea>
            </div>

            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <label for="imagen_principal" class="form-label">Imagen principal</label>
                    <input type="file" class="form-control" name="imagen_principal" id="imagen_principal"
                           accept="image/jpeg, image/png" required>
                </div>
                <div class="col-12 col-md-6">
                    <label for="otras_imagenes" class="form-label">Otras imagenes</label>
                    <input type="file" class="form-control" name="otras_imagenes[]" id="otras_imagenes"
                           accept="image/jpeg, image/png" multiple>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 col-md-6">
                    <?php if (!is_null($imagenPrincipal)) { ?>
                        <img src="<?php echo $imagenPrincipal; ?>" class="img-thumbnail my-3"><br>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" class="form-control" name="precio" id="precio"
                           value="<?php echo $producto['precio'] ?>" required>
                </div>
                <div class="col mb-3">
                    <label for="descuento" class="form-label">Descuento</label>
                    <input type="number" class="form-control" name="descuento" id="descuento"
                           value="<?php echo $producto['descuento'] ?>" required>
                </div>
                <div class="col mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" name="stock" id="stock"
                           value="<?php echo $producto['stock'] ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-4 mb-3">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select class="form-select" name="categoria" id="categoria" required>
                        <option value="">Seleccionar</option>
                        <?php foreach ($categorias as $categoria) { ?>
                            <option value="<?php echo $categoria['id']; ?>"><?php if ($categoria['id'] == $producto['id_categoria']) echo 'selected'; ?><?php echo $categoria['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

    </div>
</main>

<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>


<?php require_once '../footer.php'; ?>

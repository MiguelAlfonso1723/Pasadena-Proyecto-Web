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

$sql = $con->prepare("SELECT id,nombre FROM categorias WHERE id= ? LIMIT 1");
$sql->execute([$id]);
$categoria = $sql->fetch(PDO::FETCH_ASSOC);

?>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Edita categoría</h2>

            <form action="actualiza.php" method="post" autocomplete="off">

                <input type="hidden" name="id" value="<?php echo $categoria['id'] ?>">

                <div class="mb-3">
                    <label for="nombre" class="form- label " </label>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $categoria['nombre'] ?>" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>

        </div>
    </main>

<?php require_once '../footer.php'; ?>
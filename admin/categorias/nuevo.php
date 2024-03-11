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

?>

    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-4">Nueva categoría</h2>

            <form action="guarda.php" method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="nombre" class="form- label " </label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>

        </div>
    </main>

<?php require_once '../footer.php'; ?>
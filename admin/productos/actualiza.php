<?php

require_once '../config/Database.php';
require_once '../config/config.php';

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

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$descuento = $_POST['descuento'];
$stock = $_POST['stock'];
$categoria = $_POST['categoria'];

// Mostrar los valores de las variables $_POST para depuración
echo "ID: '" . $id . "'<br>";
echo "Nombre: " . $nombre . "<br>";
echo "Descripción: " . $descripcion . "<br>";
echo "Precio: " . $precio . "<br>";
echo "Descuento: " . $descuento . "<br>";
echo "Stock: " . $stock . "<br>";
echo "Categoría: " . $categoria . "<br>";

$sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, descuento=?, stock=?, id_categoria=? WHERE id = ?";
$stm = $con->prepare($sql);
if ($stm->execute([$nombre, $descripcion, $precio, $descuento, $stock, $categoria, $id])) {

    //Cargar imagen principal
    if ($_FILES['imagen_principal']['error'] == UPLOAD_ERR_OK) {
        $dir = '../../images/Productos/' . $id . '/';
        $permitidos = ['jpeg', 'jpg', 'png'];

        $arregloImagen = explode('.', $_FILES['imagen_principal']['name']);
        $extension = strtolower(end($arregloImagen));

        if (in_array($extension, $permitidos)) {
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $ruta_img = $dir . 'principal.' . $extension;

            if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_img)) {
                echo "El archivo se cargó correctamente.";
            } else {
                echo "Error al cargar el archivo.";
            }
        } else {
            echo "Archivo no permitido";
        }
    } else {
        echo "No enviaste archivo";
    }


    // Cargar otras imagenes
    if (isset($_FILES['otras_imagenes'])) {
        $dir = '../../images/Productos/' . $id . '/';
        $permitidos = ['jpeg', 'jpg', 'png']; // Añade 'png' como extensión permitida si también quieres permitir archivos PNG

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach ($_FILES['otras_imagenes']['tmp_name'] as $key => $tmp_name) {
            $fileName = $_FILES['otras_imagenes']['name'][$key];

            $arregloImagen = explode('.', $fileName);
            $extension = strtolower(end($arregloImagen));

            $nuevoNombre = uniqid() . '.' . $extension;
            $ruta_img = $dir . $nuevoNombre; // Corrige la ruta de destino del archivo

            if (in_array($extension, $permitidos)) {
                if (move_uploaded_file($tmp_name, $ruta_img)) {
                    echo "El archivo se cargó correctamente.<br>";
                } else {
                    echo "Error al cargar el archivo.";
                }
            } else {
                echo "Archivo no permitido";
            }
        }
    }

}

header('Location: index.php');

?>

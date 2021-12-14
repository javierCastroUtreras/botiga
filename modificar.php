<?php

session_start();

if (!isset($_SESSION['id_usuario'])) { //si hay usuario conectado mostramos pagina si no de vuelta al home
    header('Location: /login');
  }

  require 'bd.php';


if(isset($_POST['modificar'])){ // si existe modificar, modificamos el producto

$sql = "UPDATE productos SET precio =:precio, dimension = :dimension, peso = :peso WHERE nombre_producto = :nombre_producto AND usuario = :usuario ;";
$declaracion = $conn->prepare($sql);
$declaracion->bindParam(':precio', doubleval($_POST['precio']));
$declaracion->bindParam(':dimension', $_POST['dimension']);
$declaracion->bindParam(':peso', doubleval($_POST['peso']));
$declaracion->bindParam(':nombre_producto', $_POST['nombre_producto']);
$declaracion->bindParam(':usuario', intval($_SESSION['id_usuario']));

if ($declaracion->execute()) {
  $mensaje = 'el producto ha sido modificado correctamente';
} else {
  $mensaje = 'Lo sentimos no se ha podido modificar el producto';
}
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Modificar</title>
   
  </head>
  <body>

    <?php require 'header.php' ?>

    <?php if(!empty($mensaje)): ?>
      <p> <?= $mensaje ?></p>
    <?php endif; ?>

    <a href="clientes.php">Atras</a><br><br>

    </body>
</html>
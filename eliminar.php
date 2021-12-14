<?php

session_start();

if (!isset($_SESSION['id_usuario'])) { //si hay usuario conectado mostramos pagina si no de vuelta al home
    header('Location: /login');
  }

  require 'bd.php';


if(isset($_POST['eliminar'])){ // si existe eliminamos

        $sql = "DELETE FROM productos WHERE nombre_producto = :nombre_producto AND usuario= :usuario;";
        $declaracion = $conn->prepare($sql);
        $declaracion->bindParam(':nombre_producto', $_POST['nombre_producto']);
        $declaracion->bindParam(':usuario', $_SESSION['id_usuario']);
        
        
        
        if ($declaracion->execute()) {
          $mensaje = $_POST['nombre_producto'].' ha sido Eliminado correctamente';
        } else {
          $mensaje = 'Lo sentimos no se ha podido eliminar el producto';
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
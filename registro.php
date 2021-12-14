<?php

  require 'bd.php';   //utilizamos bd.php para la conexion a la base de datos

  $mensaje = '';

  if (!empty($_POST['email']) && !empty($_POST['contrasenya'])) {    //si email y contrasenya no estan vacios procede a crear un nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, email, contrasenya) VALUES (:nombre, :email, :contrasenya)";
    $declaracion = $conn->prepare($sql);
    $declaracion->bindParam(':nombre', $_POST['nombre']);
    $declaracion->bindParam(':email', $_POST['email']);
    $contrasenya = password_hash($_POST['contrasenya'], PASSWORD_BCRYPT);
    $declaracion->bindParam(':contrasenya', $contrasenya);

    if ($declaracion->execute()) {
      $mensaje = 'el usuario '. $_POST['nombre'] .' ha sido creado correctamente';
    } else {
      $mensaje = 'Lo sentimos no se ha podido realizar el registro';
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Registro</title>
    <link rel = "stylesheet" href="css.css" />
  </head>
  <body>

    <?php require 'header.php' ?>

    <?php if(!empty($mensaje)): ?>
      <p> <?= $mensaje ?></p>
    <?php endif; ?>

    <h1>Registrate</h1><br>
    <span>o <br><a href="login.php">Inicia sesion</a></span>

    <form action="registro.php" method="POST">
      <input name="nombre" type="text" placeholder="Nombre">
      <input name="email" type="text" placeholder="correo electronico">
      <input name="contrasenya" type="password" placeholder="Contraseña">
      <input type="submit" value="Registrar">
    </form>
   


    
    
  </body>
</html>
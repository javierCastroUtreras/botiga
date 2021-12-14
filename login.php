<?php

  session_start();

  if (isset($_SESSION['id_usuario'])) {
    header('Location: /login');
  }
  require 'bd.php';
// comprobamos los datos recibidos del formulario con los de la base de datos para el login de usuarios
  if (!empty($_POST['email']) && !empty($_POST['contrasenya'])) {
    $consulta = $conn->prepare('SELECT id, nombre, email, contrasenya FROM usuarios WHERE email = :email');
    $consulta->bindParam(':email', $_POST['email']);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    $mensaje = '';

    if (count($resultado) > 0 && password_verify($_POST['contrasenya'], $resultado['contrasenya'])) {
      $_SESSION['id_usuario'] = $resultado['id'];
      header("Location: /login");
    } else {
      $mensaje = 'Lo sentimos las credenciales no son correctas';
    }
  }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Inicio de sesion</title>
    </head>
    <body>
        <?php require 'header.php' ?>

        <?php if(!empty($mensaje)): ?>
        <p>
            <?= $mensaje ?></p>
        <?php endif; ?>

        <h1>Iniciar sesion</h1>
        <span>o
            <a href="registro.php">Registro</a>
        </span>

        <form action="login.php" method="POST">
            <input name="email" type="text" placeholder="Correo electronico">
            <input name="contrasenya" type="password" placeholder="Contraseña">
            <input type="submit" value="Iniciar Sesion">
        </form>
    </body>
</html>
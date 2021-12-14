<?php

session_start();

if (!isset($_SESSION['id_usuario'])) { //si hay usuario conectado mostramos pagina si no de vuelta al home
    header('Location: /login');
  }

  
    require 'bd.php';
    $mensaje = '';
    if (!empty($_POST['nombre_producto'])){

      //insertamos el producto a la base de datos, segun lo que recogemos del formulario
    $sql2 = "INSERT INTO productos (nombre_producto, precio, descripcion, dimension, peso, foto, fecha_publicacion, usuario, categoria) VALUES (:nombre_producto, :precio, :descripcion, :dimension, :peso, :foto, CURRENT_TIMESTAMP(), :usuario, :categoria)";
    $declaracion2 = $conn->prepare($sql2);
    $declaracion2->bindParam(':nombre_producto', $_POST['nombre_producto']);
    $declaracion2->bindParam(':precio', doubleval($_POST['precio']));
    $declaracion2->bindParam(':descripcion', $_POST['descripcion']);
    $declaracion2->bindParam(':dimension', $_POST['dimension']);
    $declaracion2->bindParam(':peso', doubleval($_POST['peso']));
    //DATOS DE LA IMAGEN
    $nombre_imagen = $_FILES['foto']['name'];
    $tipo_imagen = $_FILES['foto']['type'];
    $tamaño_imagen = $_FILES['foto']['size'];

    echo  $tipo_imagen;

    if($tipo_imagen=="image/jpeg"||$tipo_imagen=="image/jpg"||$tipo_imagen=="image/png"||$tipo_imagen=="image/gif"){
    //RUTA DE LA CARPETA DESTINO
    $carpeta_destino = $_SERVER['DOCUMENT_ROOT'].'/login/img/';
    //movemos la imagen del directorio temporal al directorio escogido
    move_uploaded_file($_FILES['foto']['tmp_name'],$carpeta_destino.$nombre_imagen);
    }else{
      echo "solo imagenes jpg/jpeg/png/gif";
    

    }   
    $declaracion2->bindParam(':foto', $nombre_imagen);
    $declaracion2->bindParam(':usuario', $_SESSION['id_usuario']);
    $declaracion2->bindParam(':categoria', $_POST['categoria']);
    if ($declaracion2->execute()) {
        $mensaje = 'el producto '. $_POST['nombre_producto'] .' ha sido creado correctamente';
      } else {
        $mensaje = 'Lo sentimos no se ha podido realizar el registro del nuevo producto';
      }
    }
  
  



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css.css" />
</head>
<body>
    <header>
    <?php require 'header.php' ?>
    <?php if(!empty($mensaje)): ?>
      <p> <?= $mensaje ?></p>
    <?php endif; ?>

    </header>
<!-- formulario para ingresar productos a la base de datos    -->
    <h1>ingresar productos</h1>
    <form action="clientes.php" method="POST" enctype="multipart/form-data">
      <input name="nombre_producto" type="text" placeholder="Nombre del producto">
      <input name="precio" type="text" placeholder="Precio">
      <input name="descripcion" type="text" placeholder="Descripcion del producto">
      <input name="dimension" type="text" placeholder="dimension">
      <input name="peso" type="text" placeholder="peso">
      <input name="foto" type="file" placeholder="imagen">
      <select name="categoria" id="categoria">
        <option value="comida">comida</option>
        <option value="bebidas">bebidas</option>
        <option value="limpieza">limpieza</option>
        <option value="otros">otros</option> 
      </select>
     
      <input type="submit" value="agregar">
    </form>

<br>
<!-- formulario para modificar productos    -->
<h1>Modificar productos</h1>
    <form action="modificar.php" method="POST" >
        <input type="text" name="nombre_producto" placeholder= "nombre del producto que quieres modificar">
        <input type="text" name="precio" placeholder= "precio">
        <input type="text" name="dimension" placeholder="dimension">
        <input type="text" name="peso" placeholder="peso">
         <input type="submit" name="modificar" value= "modificar">

    </form>

    <br>

<!-- formulario para eliminar productos    -->
<h1>Eliminar productos</h1>
    <form action="eliminar.php" method="POST" >
        <input type="text" name="nombre_producto" placeholder= "nombre del producto que quieres modificar">    
         <input type="submit" name="eliminar" value= "Eliminar">

    </form>

    <br>
    
    <?php 


// muestra los productos ingresados por el usuario conectado.
$consulta2 = $conn->prepare('SELECT * FROM productos WHERE usuario = :usuario');
$consulta2->bindParam(':usuario', $_SESSION['id_usuario']);
$consulta2->execute();


while($resultado2 = $consulta2->fetch(PDO::FETCH_ASSOC)){
  echo "<h3>producto ingresados por mi</h3>"."<div>"."Nombre: ".$resultado2['nombre_producto']. ". precio: ".$resultado2['precio']." euros. descripcion: ".$resultado2['descripcion'].
  ". dimensiones: ".$resultado2['dimension'].". peso: ". $resultado2['peso']. ". kilos. fecha de publicacion: ".$resultado2['fecha_publicacion'].
  ". visitas: ".$resultado2['visitas']. ". usuario: ".$resultado2['usuario'].". categoria: ".$resultado2['categoria']. "</div> <br><br>";
}
          
      
    
    ?>


</body>
</html>
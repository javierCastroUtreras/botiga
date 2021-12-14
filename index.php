<?php
  session_start();

  require 'bd.php';

  if (isset($_SESSION['id_usuario'])) {
    $consulta = $conn->prepare('SELECT id, nombre, email, contrasenya FROM usuarios WHERE id = :id');
    $consulta->bindParam(':id', $_SESSION['id_usuario']);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

    $usuario = null;

    if (count($resultado) > 0) {
      $usuario = $resultado;
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    
    <meta charset="utf-8">
    <title>Bienvenido a la tienda de la esquina</title>
    <link rel= "stylesheet" href="css.css" />
  </head>
  <body>

   <header>   
    <?php require 'header.php' 
    ?>

    <?php 
    
    $_SESSION['cliente']=0;
    if(!empty($usuario)): ?>
      <br> Bienvenid@. <?= $usuario['nombre']; 
      session_start();
       
      $_SESSION['cliente'] = 1;
       
       ?>
      <br>Has iniciado sesion correctamente!
      <br>
      <a href="clientes.php">Area CLientes</a><br><br>

      <a href="logout.php">
        Cerrar sesion
      </a>
    <?php else: ?>
      <h1>Porfavor registrate o inicia sesion</h1>

      <a href="login.php">Iniciar Sesion</a> o
      <a href="registro.php">Registro</a>
    <?php endif; ?>
    </header>



    <form action="index.php">

      <select name="opcion" id="opcion" value="0">
        <option value="1">precio menor a mayor</option>
        <option value="2">precio mayor a menor</option>
        <option value="3">fecha menor a mayor</option>
        <option value="4">fecha mayor a menor</option> 
      </select>

      <select name="categoria" id="categoria">
        <option selected="true" disabled ="disabled">categorias</option>
        <option value="comida">comida</option>
        <option value="bebidas">bebidas</option>
        <option value="limpieza">limpieza</option>
        <option value="otros">otros</option> 
      </select>

      <input type="text" name ="nomDesc"placeholder="nombre o descripcion">
      <label for="num"> introduce precio min y max</label><input type="text" name="min" placeholder="precio min"> entre <input type="text" name="max" placeholder="precio max">
     

      <input type="submit" name = "cambiar" value="cambiar">

    </form>
      <br>
      <br>

      <?php
      // ALTER TABLE productos ADD FULLTEXT(nombre_producto, descripcion);

    if(isset($_REQUEST['cambiar'])){

  $termino = ";";
  $palabra = $_REQUEST['nomDesc'];
  $categoria=$_REQUEST['categoria']; 
  $precioMinimo=$_REQUEST['min'];
  $precioMaximo=$_REQUEST['max'];  

  $select = "SELECT * FROM productos WHERE 1=1 "; 

  $precioASC= "ORDER BY precio ASC";
  $precioDESC= "ORDER BY precio DESC";

  $fechaASC="ORDER BY fecha_publicacion ASC";
  $fechaDESC="ORDER BY fecha_publicacion DESC";
  
  $categorias="AND categoria = '$categoria' ";
 
  $precios="AND precio > $precioMinimo AND precio < $precioMaximo ";

 // SELECT nombre_producto, descripcion FROM productos WHERE ;

  $nombre = "AND MATCH(nombre_producto, descripcion) AGAINST('$palabra') ";

 $opcion = $_REQUEST['opcion'];


 if(strlen($categoria)>0){
  
  $select = $select.$categorias;

 }
 if($precioMinimo!=0||$precioMaximo!=0){

  $select=$select.$precios;

 }
 if (strlen($palabra)>0) {
  
  $select=$select.$nombre;

 }
 if (strlen($opcion)>0) {
   
    switch ($opcion) {
      case '1':
        $select=$select.$precioASC;
        break;
        case '2':
          $select=$select.$precioDESC;
          break;
          case '3':
            $select=$select.$fechaASC;
            break;
            case '4':
              $select=$select.$fechaDESC;
              break;
    }
 }

$select = $select.$termino;
               
    }else{
      $select = "SELECT * FROM productos WHERE 1=1 "; 
      
      if($_REQUEST['detalle']){
        ?>
<center>
    <table border= "2">
    <thead>
    <tr>
      <th>Nombre producto</th>
      <th>Precio</th>
      <th>Descripcion</th>
      <th>Dimensiones</th>
      <th>Peso</th>
      <th>Fecha de publicacion</th>
      <th>Visitas</th>
      <th>Categoria</th>
     

    </tr>
    </thead>
    <tbody>
     <?php
      //realizamos consulta 

      $consulta2 = $conn->prepare($select);
     $consulta2->execute();
     while($resultado2 = $consulta2->fetch(PDO::FETCH_ASSOC)){
     
     ?>
    <tr>
      <td ><?php echo $resultado2['nombre_producto'];?></td>
      <td><?php echo $resultado2['precio'];?></td>
      <td ><?php echo $resultado2['descripcion'];?></td>
      <td><?php echo $resultado2['dimension'];?></td>
      <td ><?php echo $resultado2['peso'];?></td>
      <td><?php echo $resultado2['fecha_publicacion'];?></td>
      <td ><?php echo $resultado2['visatas'];?></td>
      <td><?php echo $resultado2['categoria'];?></td>
      
    </tr>

    <?php
    
    }
    ?>
    </tbody>
    </table>
    </center> 



<?php
      }else{

      }
    }
   //tabla para ver los productos    
      ?>
    <center>
    <table border= "2">
    <thead>
    <tr>
      <th>Nombre producto</th>
      <th>Precio</th>
      <th>imagen</th>
      <th>producto</th>
    </tr>
    </thead>
    <tbody>
     <?php

     //creo indice para buscar mejor
      $index = $conn->prepare("ALTER TABLE productos ADD FULLTEXT(nombre_producto, descripcion);");
      $index->execute();
      //realizamos consulta 
      $consulta2 = $conn->prepare($select);
     $consulta2->execute();
     while($resultado3 = $consulta2->fetch(PDO::FETCH_ASSOC)){

     ?>
    <tr>
  
      <td><?php echo $resultado3['nombre_producto'];?></td>
      <td><?php echo $resultado3['precio'].' €';?></td>
      <td><a href="detalle.php"><img src="/login/img/<?php echo $resultado3['foto']?>" /></a></td>
     <td>   <form action="index.php"> <input type="submit" name="detalle" value="ver producto">  </form></td> 
   
    </tr>

    <?php
    }
    ?>
    </tbody>
    </table>
    </center>  
  </body>

  <script>



  </script>
</html>
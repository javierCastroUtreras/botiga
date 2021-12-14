<?php
// variables para conexion a la base de datos
$servidor = 'localhost:3306';
$nombre_usuario = 'root';
$pass = 'javier';
$bd = 'botiga';

try {
  $conn = new PDO("mysql:host=$servidor;dbname=$bd;", $nombre_usuario, $pass); // conexion a la base de datos
} catch (PDOException $e) {
  die('No se ha podido conectar a la base de datos: ' . $e->getMessage());
}

?>
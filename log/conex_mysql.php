<?php
	$conexion = mysqli_connect('localhost', 'root', '1234')or die("Error conexion");
	$data = mysqli_select_db($conexion,'portal')or die("Error base de datos");
?>
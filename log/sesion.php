<?php
	include('conex_mysql.php');

	$user = $_REQUEST['user'];
	$pass = md5($_REQUEST['pass']);

	$con = mysqli_query($conexion,"SELECT * FROM usuarios 
									WHERE username = '$user' 
									AND password = '$pass'")or die("error");


	$nf = mysqli_num_rows($con);
	if($nf > 0){
		session_start();
		$row = mysqli_fetch_array($con);
		$_SESSION['autenticado'] = 'SI';
		$_SESSION['username'] = $row['username'];
		$_SESSION['type'] = $row['type'];
		?>
		<script>window.location="index.php";</script>
		<?php

	}else{
		echo '<br><div class="row">
			<div class="alert alert-danger">Nombre de usuario o contraseña incorrecto</div>
		  </div>';
	}
?>
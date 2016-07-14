<?php
include('conex_mysql.php');

$sql = mysqli_query($conexion, "SELECT DISTINCT FULLNAME_SLSPRSN,SLPRSNID, SPRSNSMN FROM historial_comisiones 
    							WHERE  SPRSNSMN LIKE '%GTE%' ");

$nf = mysqli_num_rows($sql);
if($nf > 0){
	while($row = mysqli_fetch_array($sql)){
		$porcentaje = '0.3';
		$insert = mysqli_query($conexion, "INSERT INTO vendedores VALUES ('','$row[SLPRSNID]','$row[FULLNAME_SLSPRSN]','GTE VENTAS','$porcentaje')")or die("Error ".mysqli_error($conexion));
		if($insert){
			echo 'cargado correctamente';
		}
	}
}else{
	echo 'No hay registro en esa busqueda';
}
?>
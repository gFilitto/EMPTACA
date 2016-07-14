<?php
	
	include('conexion.php');

$result = mysqli_query($conexion,"SELECT id_ciudad,ciudad
						FROM ciudades,estados  
						WHERE estados.id_estado = ciudades.id_estado
						AND estados.id_estado='".$_REQUEST["elegido"]."';")or die(mysqli_error()); 
if ($row = mysqli_fetch_array($result)){
	
	echo "        <select name=\"ciudad\" id=\"ciudad\">";
	echo "			<option value=\"A\">- Seleccione-</option>";
   do {
      
      echo "<option value=\"".$row["id_ciudad"]."\">".$row["ciudad"].' '.$row["apellido"]."</option>\""; 
   	} while ($row = mysqli_fetch_array($result)); 

	echo "        </select>";
}

else{
	echo "ERROR:";
	}

?>
<?php
	
	include('../../log/conex_mysql.php');

$result = mysqli_query($conexion,"SELECT *
						FROM vendedores as ven
						WHERE ven.SPRSNSMN='".$_REQUEST["car"]."';")or die(mysqli_error()); 
if ($row = mysqli_fetch_array($result)){
	
	echo "        <select name=\"vendedores\" id=\"vendor\">";
	echo "			<option value=\"A\">- Seleccione-</option>";
   do {
      
      $nom = utf8_encode($row['FULLNAME_SLSPRSN']);
      echo "<option value=\"".$row["SLPRSNID"]."\">".$nom."</option>\""; 
   	} while ($row = mysqli_fetch_array($result)); 

	echo "</select>";
}

else{
	echo "ERROR:";
}

?>
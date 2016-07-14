<?php

include('../../log/conex.php');

$op = $_REQUEST['op'];
$ve = $_REQUEST['ve'];

$sql = "SELECT SLPRSNFN  FROM SALESPERSON_MASTER  
       	    WHERE SPRSNSMN = ? ";

$par = array($op,$ve);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$consulta= sqlsrv_query($conex,$sql,$par,$options);
$nf = sqlsrv_num_rows($consulta);

while( $row = sqlsrv_fetch_array( $consulta, SQLSRV_FETCH_ASSOC) ) {

	echo '<option value="'.$row['SPRSNSMN'].'">'.$row['SPRSNSMN'].'</option>';

}

?>
<?php 
include('conex.php');
include('conex_mysql.php');
set_time_limit (3000);



$sql = "SELECT cli.CUSTNMBR,cli.CUSTNAME,
       		   ven.SLPRSNFN,ven.SPRSNSLN,ven.SPRSNSMN,ven.SLPRSNID,
	   	       fac.APTODCNM,fac.ActualApplyToAmount,fac.APFRDCNM,fac.ApplyToGLPostDate,fac.APFRDCDT,	
	   	       fac.ActualApplyToAmount/1.12*0.02 as Comision,
	   	       fac.ActualApplyToAmount/1.12 as MontoSinIva	     
		FROM RM00101 AS cli, RM20201 AS fac, RM00301 AS ven
	    WHERE cli.CUSTNMBR = fac.CUSTNMBR
		AND cli.SLPRSNID = ven.SLPRSNID
		ORDER BY ven.SLPRSNFN";
       	    
$par = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$consulta= sqlsrv_query($conex,$sql,$par,$options);
$nf = sqlsrv_num_rows($consulta);


/*echo '<table>
		<tr>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		<td>a</td>
		</tr>';*/
$delete = mysqli_query($conexion,"DELETE FROM historial_comisiones");

while( $row1 = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC)){

	$date = $row1['ApplyToGLPostDate']->format('Y-m-d');
	$date1= $row1['APFRDCDT']->format('Y-m-d');
	$full_name = mysqli_escape_string($conexion,$row1['SLPRSNFN']." ".$row1['SPRSNSLN']);
	$custname = mysqli_escape_string($conexion,$row1['CUSTNAME']);



	
	$insert = mysqli_query($conexion,"INSERT INTO historial_comisiones (CUSTNMBR,CUSTNAME,APTODCNM,ApplyToGLPostDate,APFRDCNM,APFRDCDT,ActualApplyToAmount,MontoSinIva,Comisiones,SLPRSNID,FULLNAME_SLSPRSN,SPRSNSMN) VALUES ('$row1[CUSTNMBR]','$custname','$row1[APTODCNM]','$date','$row1[APFRDCNM]','$date1','$row1[ActualApplyToAmount]','$row1[MontoSinIva]','$row1[Comision]','$row1[SLPRSNID]','$full_name','$row1[SPRSNSMN]')")or die("Error".mysqli_error($conexion));



	
	/*echo 	"<tr><td>".$row1['CUSTNMBR']." </td>
			<td>".$row1['CUSTNAME']."</td>
	        <td>".$row1['SLPRSNFN']." ".$row1['SPRSNSLN']."</td>
	        <td>".$row1['SPRSNSMN']."</td>
	        <td>".$row1['APTODCNM']."</td>
	        <td>".$row1['ActualApplyToAmount']."</td>
	        <td>".$row1['APFRDCNM']."</td>
	        <td>".$date."</td>
	        <td>".$date1."</td>
	        <td>".$row1['Comision']."</td>
	        <td>".$row1['MontoSinIva']."</td>
	        
	      </tr>";*/

}

//echo '</table>';


?>
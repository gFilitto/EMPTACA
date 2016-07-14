<?php
	include('../../log/conex.php');
	include('../../log/money_format.php');
	include('../../log/conex_mysql.php');


	$fecha=$_REQUEST['fecha'];
	$fecha1=$_REQUEST['fecha1'];
	$cargo = $_REQUEST['cargo'];

	//CONSULTA QUE TRAE LAS COMISIONES 
	$sql = "SELECT DISTINCT FULLNAME_SLSPRSN FROM BP_HIST_COMMISSIONS_SALE  
       	    WHERE ApplyFromGLPostDate BETWEEN ? AND ?
       	    AND SPRSNSMN = ?
       	    ";

    $delete = mysqli_query($conexion,"DELETE FROM hist_commissions_sale");
    $delete = mysqli_query($conexion,"DELETE FROM totales");

    $par = array($fecha,$fecha1,$cargo);
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$consulta= sqlsrv_query($conex,$sql,$par,$options);
	$nf = sqlsrv_num_rows($consulta);
		echo '<a  href="pages/resumen/export.php" id="export">Exportar a Excel</a>';
	$html = '<div class="table-responsive"><table class="table table-bordered">
	        <tr>
	            <th>Id</th>
	            <th>Nombre del Cliente</th>
	            <th>N° Fact/Cheq. Dev</th>
	            <th>Fecha Emisión Fact.</th>
	            <th>N° Cobro/Rent</th>
	            <th>Fecha cobro Fact</th>
	            <th>Monto/Cobro</th>
	            <th>Monto sin Iva</th>
	            <th>Comisiones</th>
	            <th>Días de Cobranza</th>
	          
	        </tr>';
	    
	//$nf = sqlsrv_num_rows($consulta);

	if( $nf > 0)
	{
		 $montoT = 0;
	     $sinIvaTotal = 0;
	   	 $comisionCal = 0;

	   	 $InsertMontoT = 0;
	     $InsertsinIva = 0;  
	     $Insertcalc = 0;

	while( $row = sqlsrv_fetch_array( $consulta, SQLSRV_FETCH_ASSOC) ) {
	    
	    $sql1 = "SELECT * FROM BP_HIST_COMMISSIONS_SALE  
	            WHERE ApplyFromGLPostDate BETWEEN ? AND ?
	            AND FULLNAME_SLSPRSN = ?
	            AND SPRSNSMN = ?
	            ORDER BY DOCNUMBR ASC";
	   $par1 = array($fecha,$fecha1,$row['FULLNAME_SLSPRSN'],$cargo);
	   $consulta1= sqlsrv_query($conex,$sql1,$par1);
	 
	    while( $row1 = sqlsrv_fetch_array($consulta1, SQLSRV_FETCH_ASSOC)){
	        	    
	    //$date = strtotime($row['DOCDATE']);	    
	     $date = $row1['DOCDATE']->format('Y-m-d');
	     $date1= $row1['APFRDCDT']->format('Y-m-d');
	     $id = $row1['CUSTNMBR'];  
	     $monto = $row1['ActualApplyToAmount'];
	     $sinIva= $row1['MontoSinIva']; 
	     $calc = $row1['calc_result'];
	     $SinIva = number_format($row1['MontoSinIva'],2);
	     $date_post = $row1['ApplyFromGLPostDate']->format('Y-m-d');

	     $insert = mysqli_query($conexion,"INSERT INTO hist_commissions_sale VALUES ('$row1[CUSTNMBR]', '$row1[CUSTNAME]', '$row1[DOCNUMBR]','$date','$row1[APFRDCNM]', '$date1','$row1[ActualApplyToAmount]','$date_post','$row1[DOCDAYS]','$row1[MontoSinIva]','$row1[calc_result]','$row1[porcentaje]','$row1[FULLNAME_SLSPRSN]','$row1[SPRSNSMN]')")or die("error 1".mysqli_error($conexion)); 
	      $nom = utf8_encode($row['FULLNAME_SLSPRSN']);
	      $html.= '<tr><td>'.$id."</td>
	                   <td>".$row1['CUSTNAME']." </td>
	                   <td>".$row1['DOCNUMBR']."</td>
	                   <td>".$date."</td>
	                   <td>".$row1['APFRDCNM']."</td>
	                   <td>".$date1."</td>
	                   <td>".number_format($monto,2)."</td>
	                   <td>".number_format($sinIva,2)."</td>
	                   <td>".number_format($calc,2)."</td>
	                   <td>".$row1['DOCDAYS']."</td>
	                   
	               </tr>";
	     $montoT += $monto;
	     $sinIvaTotal += $row1['MontoSinIva'];  
	     $comisionCal += $calc;
	    
	     $InsertMontoT += $monto;
	     $InsertsinIva += $row1['MontoSinIva'];  
	     $Insertcalc += $calc;
	       
	    }
	    	
	    	 
	    	 $montoT = toMoney($montoT);
	    	 $sinIvaTotal = toMoney($sinIvaTotal);
	    	 $comisionCal = toMoney($calc);
	         $html .= '<tr>
		                <td align="right"><b>Monto Cobro: '.$montoT.'</b></td>
		                <td align="right"><b>Monto sin Iva: '.$sinIvaTotal.'</b></td>
		                <td ><b>Total Comisión: '.$comisionCal.'</b></td>
		              </tr>'; 
	        /* $insert2 = mysqli_query($conexion,"INSERT INTO totales 
	         									VALUES ('$row[FULLNAME_SLSPRSN]','$InsertMontoT','$InsertsinIva','$Insertcalc')");*/

	         $InsertMontoT = 0;
	     	 $InsertsinIva = 0;  
	     	 $Insertcalc = 0; 
	}
	$html .= '</table></div>';
	echo $html;
}else{
	echo '<div class="row">
			<div class="alert alert-warning">No hay comisiones registradas para ese mes</div>
		  </div>';
}
?>
<style type="text/css">
	.luz tr:hover{
		background: #efefef;
	}
</style>
<?php
	//include('../../log/conex.php');
	include('../../log/money_format.php');
	include('../../log/conex_mysql.php');

	$fecha = $_REQUEST['fecha'];
	$fecha1= $_REQUEST['fecha1'];
	$fecha = new Datetime($fecha);
	$fecha = date_format($fecha,'Y-m-d');
	$fecha1 = new Datetime($fecha1);
	$fecha1 = date_format($fecha1,'Y-m-d');
	$key = uniqid();
	//echo $fecha.' '. $fecha1;
	//$cargo= $_REQUEST['cargo'];
	//CONSULTA QUE TRAE LAS COMISIONES 
	$consulta = mysqli_query($conexion,"SELECT DISTINCT FULLNAME_SLSPRSN,SLPRSNID FROM historial_comisiones 
    									WHERE ApplyFromGLPostDate BETWEEN '$fecha' AND '$fecha1'
    									AND ActualApplyToAmount > 0
    									");
    $nf = mysqli_num_rows($consulta);
		echo '<a  href="pages/resumen/export.php?key='.$key.'">Exportar a Excel</a>';
	$html = '<div class="table-responsive"><table class="table table-bordered" style="font-size:11px;">
	        <tr>
	            <th>Id</th>
	            <th>Vendedor</th>
	            <th>Tipo</th>
	            <th>Monto/Cobro</th>
	            <th>Monto sin Iva</th>
	            <th>Comisión por Venta</th>
	            <th>Comisión por Zona</th>
	            <th>Comisión General</th>      
	            <th>%</th>      
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
	     $CalcZonaComision = 0;
	while( $row = mysqli_fetch_array($consulta) ) {
	    $CalcZonaComision = 0;
	    $CalcZona = 0;
	  
			$consulta1= mysqli_query($conexion,"SELECT *,com.ActualApplyToAmount/1.12*ven.porcentaje as calc_result FROM historial_comisiones as com, vendedores as ven
	   										WHERE com.ApplyFromGLPostDate BETWEEN '$fecha' AND '$fecha1'
	   										AND com.SLPRSNID = '$row[SLPRSNID]'
	   										AND com.SLPRSNID = ven.SLPRSNID 
	            							ORDER BY ven.SPRSNSMN ASC");

			
			//CONSULTA PARA TRAER LOS VENDEDORES DE CADA SUPERVISOR
			$vendedores = mysqli_query($conexion,"SELECT sup.id_vendedor,ven.porcentaje,ven.FULLNAME_SLSPRSN,ven.SLPRSNID,ven.porcentaje_sup
											 FROM supervisores as sup, vendedores as ven
											 WHERE sup.id_supervisor = '$row[SLPRSNID]'
											 AND sup.id_supervisor = ven.SLPRSNID");

			/* ?>
				<script type="text/javascript">
			 		console.log(<?php echo $row['SLPRSNID'] ?>);
			 	</script>
			<?php */
			$nf_ven = mysqli_num_rows($vendedores);
			
			if($nf_ven > 0){
					$CalcZonaComision = 0;
					$CalcZona = 0;
					while($datos_ven = mysqli_fetch_array($vendedores)){
						$porcentaje_sup = $datos_ven['porcentaje_sup'];

					?>
					<script type="text/javascript">
				 		console.log(<?php echo $datos_ven['id_vendedor'] ?>);
				 	</script>
				<?php
					
					$consulta2 = mysqli_query($conexion,"SELECT *
												 FROM supervisores as sup, vendedores as ven, historial_comisiones as hist
												 WHERE sup.id_vendedor= '$datos_ven[id_vendedor]'
												 AND ven.SLPRSNID = sup.id_vendedor
												 AND hist.SLPRSNID = sup.id_vendedor
												 AND hist.ApplyFromGLPostDate BETWEEN '$fecha' AND '$fecha1'
												 ORDER BY hist.APTODCNM ASC");

			         //SETIANDO VARIABLES 
					$zonaMonto = 0;
					$ZmontoT = 0;
					$sinIvaTotalzona = 0;
					$calcZona = 0;
					$CalcZona = 0;
					$CalcZonaComision = 0;
					$C = 0;
					while($row2 = mysqli_fetch_array($consulta2)){

						$por = $row2['porcentaje'];
						$zonaMonto = $row2['ActualApplyToAmount'];
						$ZmontoT += $zonaMonto; 
					    $sinIvaTotalzona += $row2['MontoSinIva'];  
					    $comisionzona = $row2['ActualApplyToAmount']/1.12 * $porcentaje_sup;
					    $C += $comisionzona;
					    //$comisionCal += $calc;
					    //$calcZona = $row2['ActualApplyToAmount']/1.12*$por;
					  	$nom = utf8_encode($row2['FULLNAME_SLSPRSN']);	
					  	//$id = $row2['SLPRSNID'];

					}
					$CalcZona += $C;
				}	//END WHILE VENDEDORES 

				
			} //END IF NF_VENDEDORES
			
	 	 $montoT = 0;
	     $sinIvaTotal = 0;
	     $SinIvaTotal = 0;
	   	 $comisionCal = 0;
	   	 $Calc= 0;

	   	 $A= 1;
 
	  while( $row1 = mysqli_fetch_array( $consulta1)){
 
	    
	     $date = $row1['ApplyToGLPostDate'];
	     //$date1= $row1['APFRDCDT']->format('Y-m-d');
	     $id = $row1['CUSTNMBR'];  
	     $monto = $row1['ActualApplyToAmount'];
	     $sinIva= $row1['MontoSinIva']; 
	     
	     $calc = $row1['calc_result'];
	     $Calc += $calc;
	     $SinIva = number_format($row1['MontoSinIva'],2);
	     //$date_post = $row1['ApplyToGLPostDate']->format('Y-m-d');
	     $id = $row1['SLPRSNID'];
	     $nom = utf8_encode($row1['FULLNAME_SLSPRSN']);
	     $SPRSNSMN = $row1['SPRSNSMN'];
	     
	  /*  $html.= '<tr><td>'.$row1['SLPRSNID']."</td>
	    				<td>".$nom."</td>
	                    <td>".$row1['APTODCNM']."</td>
	                   <td>".$date."</td>
	                   <td>".$row1['APFRDCNM']."</td>
	                   <td></td>
	                   <td>".number_format($monto,2)."</td>
	                   <td>".number_format($sinIva,2)."</td>
	                   <td>".number_format($calc,2)."</td>
	                   <td></td>
	                   <td>".$row1['CUSTNAME']." </td>
	               </tr>";  */
	     $montoT += $monto;
	     $SinIvaTotal += $row1['MontoSinIva'];  
	     //$comisionCal += $calc;
	    
	     $InsertMontoT += $monto;
	     $InsertsinIva += $row1['MontoSinIva'];  
	     $Insertcalc += $calc;
	     $A++;
	     $porcen = $row1['porcentaje'];
	     $com = 'JJJ';
	    }
	    	if($montoT > 0){

	    	 $nom = utf8_encode($row['FULLNAME_SLSPRSN']);
	    	 $montoT = toMoney($montoT);
	    	 $sinIvaTotal = toMoney($SinIvaTotal);
	    	 $comisionCal = toMoney($Calc);
	    	 $CalcZonaComision = toMoney($CalcZona);

	    	 $porcen = $porcen * 100;
	    	 $CalcGeneral = $CalcZona + $Calc;
	         $html .= '<tr>
	         			<td>'.$id.'</td>
	         			<td>'.$nom.'</td>
	         			<td>'.$SPRSNSMN.'</td>
		                <td align="right">'.$montoT.'</td>
		                <td align="right">'.$sinIvaTotal.'</td>
		                <td>'.$comisionCal.'</td>
		                <td align="right">'.$CalcZonaComision.'</td>
		                <td align="right">'.toMoney($CalcGeneral).'</td>
		                <td align="right">'.$porcen.'</td>
		              </tr>'; 
	        // $insert2 = mysqli_query($conexion,"INSERT INTO totales 
	        // 									VALUES ('$row[FULLNAME_SLSPRSN]','$InsertMontoT','$InsertsinIva','$Insertcalc')");

		       $insert_export = mysqli_query($conexion,"INSERT INTO exportador_resumen VALUES ('','$id','$nom','$SPRSNSMN','$monto','$SinIvaTotal','$Calc','$CalcZona','$CalcGeneral','$key')");
		    }
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
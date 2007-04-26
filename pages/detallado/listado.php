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
	//echo $fecha.' '. $fecha1;
	$cargo= $_REQUEST['cargo'];
	$vendedor = $_REQUEST['vendedores'];
	$key = uniqid();

	
	//CONSULTA QUE TRAE LAS COMISIONES 
	$consulta = mysqli_query($conexion,"SELECT *,com.ActualApplyToAmount/1.12*ven.porcentaje as calc_result, com.id
											FROM historial_comisiones as com, vendedores as ven
	   										WHERE com.ApplyFromGLPostDate BETWEEN '$fecha' AND '$fecha1'
	   										AND com.SLPRSNID = '$vendedor'
	            							AND com.SPRSNSMN LIKE '%$cargo%'
	            							AND com.SLPRSNID = ven.SLPRSNID 
	            							ORDER BY com.APTODCNM ASC")or die("error");


	//CONSULTA PARA TRAER LOS VENDEDORES DE CADA SUPERVISOR
	if($cargo == 'SUPERVISOR'){

		$consulta1 = mysqli_query($conexion,"SELECT sup.id_vendedor,ven.porcentaje,ven.FULLNAME_SLSPRSN,ven.SLPRSNID,ven.porcentaje_sup
											 FROM supervisores as sup, vendedores as ven
											 WHERE sup.id_supervisor = '$vendedor'
											 AND sup.id_supervisor = ven.SLPRSNID"); 

	}else if($cargo == 'GTE VENTAS'){

		$consulta1 = mysqli_query($conexion,"SELECT gte.id_vendedor,ven.porcentaje,ven.FULLNAME_SLSPRSN,ven.SLPRSNID,ven.porcentaje_sup
											 FROM gerentes as gte, vendedores as ven
											 WHERE gte.id_gte = '$vendedor'
											 AND gte.id_gte = ven.SLPRSNID"); 	
	}
	
    $nf = mysqli_num_rows($consulta);
    $nf2 = mysqli_num_rows($consulta1);
		echo '<a href="pages/detallado/export.php?key='.$key.'" id="#"><button class="btn btn-info">Exportar a Excel</button></a> - 
			  <a href="pages/detallado/export_short.php?key='.$key.'" ><button class="btn btn-info">Exportar a Excel Short</button></a>';
	
	 $i = 0;
	 $Calc = 0; 
	 $montoT = 0;
	 $sinIvaTotal = 0;
	 $MontoCobroVendedor = 0;
	 $MontoSinIvaSupervisor = 0;
	//$nf = sqlsrv_num_rows($consulta);

	 $montoTven = 0;
			$sinIvaTotalVen = 0;
			//$MontoComisionCal = 0;
			$CalcVen = 0;
	if( $nf > 0 || $nf2 > 0)
	{
		$html = '<div class="table-responsive"><table class="table table-bordered luz" style="font-size:11px;">
	        <tr>
	            <th>Id</th>
	            <th>Vendedor</th>
	            <th>Cliente</th>
	            <th>N° Fact/Cheq. Dev</th>
	            <th>Fecha Emisión Fact.</th>
	            <th>N° Cobro/Rent</th>
	            <th>Fecha cobro Fact</th>
	            <th width="100">Monto/Cobro</th>
	            <th width="100">Monto sin Iva</th>
	            <th>Comisiones</th>
	            <th>%</th>
	            <th>Días de Cobranza</th>
	        </tr>';
		
	while( $row1 = mysqli_fetch_array($consulta)){

		 $date = $row1['ApplyToGLPostDate'];
	     $date1= $row1['APFRDCDT'];
	     $id = $row1['CUSTNMBR'];  
	     $monto = $row1['ActualApplyToAmount'];
	     $sinIva= $row1['MontoSinIva']; 
	     
	     $calc = $row1['calc_result'];
	     $Calc += $calc;
	     $SinIva = number_format($row1['MontoSinIva'],2);
	     //$date_post = $row1['ApplyToGLPostDate']->format('Y-m-d');
	     
	     $nom = utf8_encode($row1['FULLNAME_SLSPRSN']);
	     $porcen = $row1['porcentaje'];
		 $Porce = $porcen * 100;
		 ?><script type="text/javascript">
		 	console.log(<?php echo $row1['id']; ?>);
		 </script>
		 <?php
		 ?>
		
		<?php	

		 $id =  array($row1['id']);
		 $html.= '<tr><td>'.$row1['SLPRSNID'].'</td>
	    			   <td>'.$nom.'</td>
	    			   <td>'.$row1['CUSTNAME'].' </td>
	    			   <td>'.$row1['APTODCNM'].'</td>
	                   <td>'.$date.'</td>
	                   <td>'.$row1['APFRDCNM'].'</td>
	                   <td>'.$date1.'</td>
	                   <td>'.number_format($monto,2).'</td>
	                   <td>'.number_format($sinIva,2).'</td>
	                   <td>'.number_format($calc,2).'</td>
	                   <td>'.$Porce.'</td>
	                   <td></td>
	               </tr>';  

	     
	     
	     ?><script type="text/javascript">
	     	
	     	console.log(<?php echo $montoT; ?>);
	     </script><?php
	     
	     $montoT += $monto;
	     $sinIvaTotal += $row1['MontoSinIva'];  
	     //$comisionCal += $calc;   

	     //Realiza insert para tabla donde esta el export
	     //Este insert solo contiene las ventas de cada RDV,SUP y GTE 
	     $insert = mysqli_query($conexion,"INSERT INTO exportador_excel VALUES ('', '$row1[CUSTNMBR]','$row1[CUSTNAME]','$row1[APTODCNM]','$row1[ApplyToGLPostDate]','$row1[APFRDCNM]','$row1[APFRDCDT]','$row1[ActualApplyToAmount]','$row1[MontoSinIva]','$row1[Comisiones]','$row1[porcentaje]','$row1[SLPRSNID]','$row1[FULLNAME_SLSPRSN]','$row1[SPRSNSMN]','$row1[id]','$key')")or die("Error en el insert".mysqli_error($conexion));
	}
		
		$cobro = $montoT;
		?>
		<script>
			console.log('ants del if');
		</script>
		<?php

	if($cargo == 'SUPERVISOR'){
		include('listado_supervisor.php');

	}else if($cargo == 'GTE VENTAS'){

		include('listado_gte.php');

	}
	 	 	//Total Ventas
	 	 	 $html .= '<tr>
	         			<td colspan="7" align="right">Total de sus Ventas: </td>
		                <td align="right"><b>'.toMoney($cobro).'</b></td>
		                <td align="right"><b>'.toMoney($sinIvaTotal).'</b></td>
		                <td align="right"><b>'.toMoney($Calc).'</b></td>
		                <td colspan="2"><b>'.$Porce.'</b></td>
		              </tr>'; 


		     //Total Comisión por Zona
		     $html .= '<tr>
	         			<td colspan="7" align="right">Total Zona de Ventas: </td>
		                <td align="right"><b>'.toMoney($MontoCobroVendedor).'</b></td>
		                <td align="right"><b>'.toMoney($MontoSinIvaSupervisor).'</b></td>
		                <td align="right"><b>'.toMoney($MontoComisionCal).'</b></td>
		                <td colspan="2"><b>'.$Porce_sup.'</b></td>
		              </tr>';


	    	// $montoT = toMoney($montoT);
	    	 //$sinIvaTotal = $sinIvaTotal;
	    	 $comisionCal = toMoney($Calc);
	    	 $totalMontoT = $montoT + $MontoCobroVendedor;
	    	 $totalCalc = $CalcVen + $Calc;
	    	 $totalsnIva = $MontoSinIvaSupervisor + $sinIvaTotal;
	         $html .= '<tr>
	         			<td colspan="7" align="right">Totales: </td>
		                <td align="right"><b>'.toMoney($totalMontoT).'</b></td>
		                <td align="right"><b>'.toMoney($totalsnIva).'</b></td>
		                <td align="right"><b>'.toMoney($totalCalc).'</b></td>
		              </tr>'; 
	$html .= '</table></div>';
	echo $html;
		
}else{
	echo '<div class="row">
			<div class="alert alert-warning">No hay comisiones registradas para ese mes</div>
		  </div>';
}
?>
<script type="text/javascript">
	 $('#Export').click(function(){
		$('#myModal').modal('show');
		var id = $('#Id').val();
		$.ajax({
			type:'GET', 
            url: 'pages/detallado/prueba_export.php?id='+id,
            async:false,
            success: function(data){                   
              	$('#myModal').modal('hide');
               	$('#inicio').addClass('active');
              	$(".container").html(data);
            },error: function() {
					$("body").removeClass("loading");
					alert('Se ha producido un error');
			}
		});
	});
</script>
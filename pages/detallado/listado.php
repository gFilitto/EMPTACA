<?php
	include('../../log/conex.php');
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
	//CONSULTA QUE TRAE LAS COMISIONES 
	$consulta = mysqli_query($conexion,"SELECT *,com.ActualApplyToAmount/1.12*ven.porcentaje as calc_result, com.id
											FROM historial_comisiones as com, vendedores as ven
	   										WHERE com.ApplyToGLPostDate BETWEEN '$fecha' AND '$fecha1'
	   										AND com.SLPRSNID = '$vendedor'
	            							AND com.SPRSNSMN LIKE '$cargo%'
	            							AND com.SLPRSNID = ven.SLPRSNID 
	            							ORDER BY CUSTNMBR ASC");
	//CONSULTA PARA TRAER LOS VENDEDORES DE CADA SUPERVISOR
	$consulta1 = mysqli_query($conexion,"SELECT sup.id_vendedor,ven.porcentaje,ven.FULLNAME_SLSPRSN,ven.SLPRSNID,ven.porcentaje_sup
											 FROM supervisores as sup, vendedores as ven
											 WHERE sup.id_supervisor = '$vendedor'
											 AND sup.id_supervisor = ven.SLPRSNID"); 
    $nf = mysqli_num_rows($consulta);
    $nf2 = mysqli_num_rows($consulta1);
		echo '<a href="#" id="Export">Exportar a Excel</a>';
	
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
		$html = '<div class="table-responsive"><table class="table table-bordered" style="font-size:11px;">
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
		 $html.= '<tr><td><input name="id" type="text" disabled value="'.$row1['id'].'"></td>
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

	     $montoT += $monto;
	     $sinIvaTotal += $row1['MontoSinIva'];  
	     //$comisionCal += $calc;     
	}

	if($cargo == 'SUPERVISOR'){
		include('listado_supervisor.php');

	}else if($cargo == 'GTE VENTAS'){

		include('listado_gte.php');

	}
	 	 
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
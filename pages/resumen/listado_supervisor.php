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
	//CONSULTA QUE TRAE LAS COMISIONES 

	/*$html = '<div class="table-responsive"><table class="table table-bordered">
	        <tr>
	            <th>Vendedor</th>
	            <th>Total Sus Ventas</th>
	            <th>Total sus Ventas sin iva</th>
	            <th width="120">Comisión Ventas</th>
	            <th>Monto Cobro vendedores</th>
	            <th>Monto sin Iva vendedores</th>
	            <th width="120">Comisión Vendedores</th>
	            <th>Porcentaje</th>	            
	        </tr>'; */

	$html = '<div class="table-responsive"><table class="table table-bordered">
				<tr>
					<td>Id</td>
					<td>Vendedor</td>
					<td>Tipo</td>
					<td>Monto Cobro Ret</td>
					<td>Monto Cobro sin Iva</td>
					<td>Comisión General</td>
				</tr>';

	$consulta = mysqli_query($conexion,"SELECT DISTINCT id_supervisor,ven.* 
										FROM supervisores as sup, vendedores as ven
										WHERE ven.SLPRSNID = sup.id_supervisor");



	while($row = mysqli_fetch_array($consulta)){

		//CONSULTA PARA TRAER LOS VENDEDORES DE CADA SUPERVISOR
		$consulta1 = mysqli_query($conexion,"SELECT sup.id_vendedor,ven.porcentaje,ven.FULLNAME_SLSPRSN,ven.SLPRSNID
											 FROM supervisores as sup, vendedores as ven
											 WHERE sup.id_supervisor = '$row[id_supervisor]'
											 AND sup.id_supervisor = ven.SLPRSNID");

		$MontoCobroSupervisor = 0;
		$MontoIvaSupervisor = 0;
		$ComisionSupervisor = 0;

		//CONSULTA PARA TRAER LAS VENTAS DEL SUPERVISOR
		$vent = mysqli_query($conexion, "SELECT *
											 FROM  historial_comisiones as hist
											 WHERE hist.SLPRSNID= '$row[id_supervisor]'
											 AND hist.ApplyToGLPostDate BETWEEN '$fecha' AND '$fecha1' ");

		$montosup = 0;
		$montoivasup = 0;
		$comisionsup = 0;

		while($ventas = mysqli_fetch_array($vent)){
			$montosup += $ventas['ActualApplyToAmount'];
			$montoivasup += $ventas['MontoSinIva'];
			$comisionsup += $ventas['ActualApplyToAmount']/1.12*0.018;
		}
		@$MontoCobroSupervisor += $montosup;
		@$MontoIvaSupervisor += $montoivasup;
		@$ComisionSupervisor += $comisionsup;
		
		$MontoCobroVendedor = 0;
		$MontoSinIvaSupervisor = 0;
		
		$nom_sup = "";
		$a = 0;
		$MontoComisionCal = 0;
		while($row1 = mysqli_fetch_array($consulta1)){
			$consulta2 = mysqli_query($conexion,"SELECT *
											 FROM supervisores as sup, vendedores as ven, historial_comisiones as hist
											 WHERE sup.id_vendedor= '$row1[id_vendedor]'
											 AND ven.SLPRSNID = sup.id_vendedor
											 AND hist.SLPRSNID = sup.id_vendedor
											 AND hist.ApplyToGLPostDate BETWEEN '$fecha' AND '$fecha1'");

			//SETIANDO VARIABLES
			$montoT = 0;
			$sinIvaTotal = 0;
			//$MontoComisionCal = 0;
			$Calc = 0;
			$nom = "";

			//DATOS DEL SUPERVISOR
			$por = $row1['porcentaje'];
			$nom_sup = $row1['FULLNAME_SLSPRSN'];
			$id = $row1['SLPRSNID'];


			while($row2 = mysqli_fetch_array($consulta2)){
				$por = $row1['porcentaje'];
				$monto = $row2['ActualApplyToAmount'];
				$montoT += $monto;
			   $sinIvaTotal += $row2['MontoSinIva'];  
			   //$comisionCal += $calc;
			   $calc = $row2['ActualApplyToAmount']/1.12*$por;
			  	$nom = utf8_encode($row2['FULLNAME_SLSPRSN']);	
			  	$Calc += $calc;
				//$id = $row2['SLPRSNID'];

			}
			$MontoCobroVendedor += $montoT;
			$MontoSinIvaSupervisor += $sinIvaTotal;
			$MontoComisionCal += $Calc;						
		}
	
		//CAMBIANDO A FORMATO MONEDA LOS MONTOS DE LOS VENDEDORES 
		$MontoMoneyCobroVendedor = ToMoney($MontoCobroVendedor);
		$MontoSinIvaSupervisor = toMoney($MontoSinIvaSupervisor);
	   $MontoComisionCalu = toMoney($MontoComisionCal);

	   //CAMBIANDO A FORMATO MONEDA LOS MONTOS DE LOS SUPERVISORES 
	   $MontoMoneyCobroSupervisor = toMoney($MontoCobroSupervisor);
		$MontoIvaSupervisor = toMoney($montoivasup);
		$ComisionSupervisor = toMoney($comisionsup);
	   
	   $consultaMount = mysqli_query($conexion, "SELECT * 
	   										  FROM historial_comisiones as hist
	   										  WHERE hist.SLPRSNID = '$id' 
	   										  AND hist.ApplyToGLPostDate BETWEEN '$fecha' AND '$fecha1'")or die("Error".mysqli_error($conexion));
	  $montoVentaSup = 0;
	   while($col = mysqli_fetch_array($consultaMount)){
	   	$montoVentaSup += $col['ActualApplyToAmount'];

	   }
	   $montoVentaSup = toMoney($montoVentaSup);

	   $SumaComision = $comisionsup + $MontoComisionCal;
		$SumaMoney = toMoney($SumaComision);
	   
	   $html .= '<tr>
	   	    		<td rowspan="2" align="bottom">'.$nom_sup.' '.$id.'</td>
	   	    		<td>'.$MontoMoneyCobroSupervisor.'</td>
	   	    		<td>'.$MontoIvaSupervisor.'</td>
	   	    		<td>'.$ComisionSupervisor.'</td>
	   	    		<td align="right">'.$MontoMoneyCobroVendedor.'</td>
		            <td align="right">'.$MontoSinIvaSupervisor.'</td>
		            <td align="right">'.$MontoComisionCalu.'</td>
		            <td align="right">'.$por.'</td>
		          </tr>'; 
		
		$html .= '<tr>
						<td colspan="7"><b>Comisión General: '.$SumaMoney.'</b></td>
					</tr>';

	}
	$html .= '</table>';
	echo $html;
?>
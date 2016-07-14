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

	$html = '<div class="table-responsive"><table class="table table-bordered">
	        <tr>
	            <th>Vendedor</th>
	            <th>Total Sus Ventas</th>
	            <th>Total sus Ventas sin iva</th>
	            <th width="120">Comisión Ventas</th>
	            <th>Monto Cobro vendedores</th>
	            <th>Monto sin Iva vendedores</th>
	            <th width="120">Comisión Vendedores</th>
	            <th>Porcentaje</th>	            
	        </tr>';

	$consulta = mysqli_query($conexion,"SELECT DISTINCT id_gte
										FROM gerentes as gte");



	while($row = mysqli_fetch_array($consulta)){

		$consulta1 = mysqli_query($conexion,"SELECT gte.id_vendedor,ven.porcentaje,ven.FULLNAME_SLSPRSN,ven.SLPRSNID
											 FROM gerentes as gte, vendedores as ven
											 WHERE gte.id_gte = '$row[id_gte]'
											 AND gte.id_gte = ven.SLPRSNID");

		$MontoCobroSupervisor = 0;
		$MontoIvaSupervisor = 0;
		$ComisionSupervisor = 0;

		//CONSULTA PARA TRAER LAS VENTAS DEL SUPERVISOR
		$vent = mysqli_query($conexion, "SELECT *
											FROM  historial_comisiones as hist
											 WHERE hist.SLPRSNID= '$row[id_gte]'
											 AND hist.ApplyToGLPostDate BETWEEN '$fecha' AND '$fecha1' ");

		$montosup = 0;
		$montoivasup = 0;
		$comisionsup = 0;

		while($ventas = mysqli_fetch_array($vent)){
			$montosup += $ventas['ActualApplyToAmount'];
			$montoivasup += $ventas['MontoSinIva'];
			$comisionsup += $ventas['ActualApplyToAmount']/1.12*0.02;
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
											 FROM gerentes as gte, vendedores as ven, historial_comisiones as hist
											 WHERE gte.id_vendedor= '$row1[id_vendedor]'
											 AND ven.SLPRSNID = gte.id_vendedor
											 AND hist.SLPRSNID = gte.id_vendedor
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
			   $calc = $row2['ActualApplyToAmount']/1.12*0.0012;
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
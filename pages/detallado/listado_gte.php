<script type="text/javascript">
	console.log('paso por aqui');
</script>
<?php

while($ven = mysqli_fetch_array($consulta1)){
			//CONSULTA PARA TRAER LAS FACTURAS DE LOS VENDEDORES DE CADA GERENTE
			$consulta2 = mysqli_query($conexion,"SELECT *
											 FROM gerentes as gte, vendedores as ven, historial_comisiones as hist
											 WHERE gte.id_vendedor= '$ven[id_vendedor]'
											 AND ven.SLPRSNID = gte.id_vendedor
											 AND hist.SLPRSNID = gte.id_vendedor
											 AND hist.ApplyFromGLPostDate BETWEEN '$fecha' AND '$fecha1'
											 ORDER BY hist.APTODCNM ASC");
			//SETIANDO VARIABLES
			$montoTven = 0;
			$sinIvaTotalVen = 0;
			//$MontoComisionCal = 0;
			
			$nom = "";

			//DATOS DEL SUPERVISOR
			$por = $ven['porcentaje'];
			$nom_sup = $ven['FULLNAME_SLSPRSN'];
			$id = $ven['SLPRSNID'];
			
			
			$MontoComisionCal =0;	

			while($row2 = mysqli_fetch_array($consulta2)){
				$por = $ven['porcentaje_sup'];
				$montoven = $row2['ActualApplyToAmount'];
				$montoTven += $montoven;
			    $sinIvaTotalVen += $row2['MontoSinIva'];  
			   //$comisionCal += $calc;
			   $calcven = $row2['ActualApplyToAmount']/1.12*$por;
			   $Porce_sup = $por * 100;
			  	$nom = utf8_encode($row2['FULLNAME_SLSPRSN']);	
			  	$CalcVen += $calcven;
				//$id = $row2['SLPRSNID'];
				
				
				

			$date = $row2['ApplyToGLPostDate'];
	    	$date1= $row2['APFRDCDT'];
	    	$id = array($row2['id']);

			$html.= '<tr><td>'.$id[$i]."</td>
	    			   <td>".$nom."</td>
	    			   <td>".$row2['CUSTNAME']." </td>
	    			   <td>".$row2['APTODCNM']."</td>
	                   <td>".$date."</td>
	                   <td>".$row2['APFRDCNM']."</td>
	                   <td>".$date1."</td>
	                   <td>".number_format($montoven,2)."</td>
	                   <td>".number_format($sinIvaTotalVen,2)."</td>
	                   <td>".number_format($calcven,2)."</td>
	                   <td>".$Porce_sup."</td>
	                   <td></td>
	               </tr>";  

	        $insert = mysqli_query($conexion,"INSERT INTO exportador_excel VALUES ('', '$row2[CUSTNMBR]','$row2[CUSTNAME]','$row2[APTODCNM]','$row2[ApplyToGLPostDate]','$row2[APFRDCNM]','$row2[APFRDCDT]','$row2[ActualApplyToAmount]','$row2[MontoSinIva]','$row2[Comisiones]','$por','$row2[SLPRSNID]','$row2[FULLNAME_SLSPRSN]','$row2[SPRSNSMN]','$row2[id]','$key')")or die("Error en el insert".mysqli_error($conexion));

			}
			$MontoCobroVendedor += $montoTven;
			$MontoSinIvaSupervisor += $sinIvaTotalVen;
			$MontoComisionCal += $CalcVen;		
			
		}

	

?>

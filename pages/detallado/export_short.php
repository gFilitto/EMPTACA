<?php
 
include('../../log/conex_mysql.php');

    $key = $_REQUEST['key'];
    $consulta = mysqli_query($conexion,"SELECT * FROM exportador_excel WHERE llave = '$key'");

     require_once('../../lib/Classes/PHPExcel.php');
     $objPHPExcel = new PHPExcel();
     

      $objPHPExcel->
      getProperties()
        ->setCreator("")
        ->setLastModifiedBy("")
        ->setTitle("Resumen Comisiones")
        ->setSubject("")
        ->setDescription("")
        ->setKeywords("")
        ->setCategory("");   

        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
        

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1','Id');

        
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B1','Vendedor')
        ->setCellValue('C1','Cliente')
        ->setCellValue('D1','Monto/Cobro')
        ->setCellValue('E1','Monto sin Iva')
        ->setCellValue('F1','Comisiones')
        ->setCellValue('G1','%');

        //ESTILO DE LA HOJA DE CALCULO
        
        $objPHPExcel->getActiveSheet()->getStyle('D2:D200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('E2:E200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('F2:F200')->getNumberFormat()->setFormatCode("#,##0.00");
        //$objPHPExcel->getActiveSheet()->getStyle('F2:F200')->getNumberFormat()->setFormatCode("#,##0.00");

    $i = 2;  
    while($row1 = mysqli_fetch_array($consulta)){
     
            $nom = utf8_encode($row1['FULLNAME_SLSPRSN']);
            $porcentaje = $row1['porcentaje'] * 100;

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row1['SLPRSNID'])
                ->setCellValue('B'.$i, $nom)
                ->setCellValue('C'.$i, $row1['CUSTNAME'])
                ->setCellValue('D'.$i, $row1['ActualApplyToAmount'])
                ->setCellValue('E'.$i, $row1['MontoSinIva'])
                ->setCellValue('F'.$i, $row1['Comisiones'])
                ->setCellValue('G'.$i, $porcentaje);

        $i++;
        /*$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, 'Vendedor: '.$row1['CUSTNMBR'])
        ->setCellValue('C'.$i, 'Total Cobro:')
        ->setCellValue('D'.$i, $row1['total_cobro'])
        ->setCellValue('E'.$i, 'Total sin Iva:')
        ->setCellValue('F'.$i, $row['total_sin_iva']);*/
       
       
    }
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Resumen_Comisiones.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;

?>


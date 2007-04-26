<?php
 
include('../../log/conex_mysql.php');

    $key = $_REQUEST['key'];
    $consulta = mysqli_query($conexion,"SELECT * FROM exportador_resumen WHERE llave = '$key'")or die("error");



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

        

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1','Id')
        ->setCellValue('B1','Vendedor')
        ->setCellValue('C1','Tipo')
        ->setCellValue('D1','Monto/Cobro')
        ->setCellValue('E1','Monto sin Iva')
        ->setCellValue('F1','Comisión por Ventas')
        ->setCellValue('G1','Comisión por Zona')
        ->setCellValue('H1','Comision General');
        

        //ESTILO DE LA HOJA DE CALCULO
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);
       
        $objPHPExcel->getActiveSheet()->getStyle('D2:D200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('E2:E200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('F2:F200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('G2:G200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('H2:H200')->getNumberFormat()->setFormatCode("#,##0.00");

    $i = 2;  
    
    while($row1 = mysqli_fetch_array($consulta)){


         $nom = utf8_encode($row1['FULLNAME_SLSPRSN']);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row1['SLPRSNID'])
                ->setCellValue('B'.$i, $nom)
                ->setCellValue('C'.$i, $row1['SPRSNSMN'])
                ->setCellValue('D'.$i, $row1['ActualApplyToAmount'])
                ->setCellValue('E'.$i, $row1['MontoSinIva'])
                ->setCellValue('F'.$i, $row1['ComisionVenta'])
                ->setCellValue('G'.$i, $row1['ComisionZona'])
                ->setCellValue('H'.$i, $row1['ComisionGeneral']);
           $i++;     
                
       

       
       
    }
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Comisiones_Resumen.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;

?>


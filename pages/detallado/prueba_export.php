<?php
 
include('../../log/conex_mysql.php');

$ids = $_REQUEST['id'];
foreach ($ids as $id) {
    $consulta = mysqli_query($conexion,"SELECT * FROM exportador_excel");
}

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
        ->setCellValue('C1','Cliente')
        ->setCellValue('D1','N° Fact/Cheq. Dev')
        ->setCellValue('E1','Fecha Emisión Fact.')
        ->setCellValue('F1','N° Cobro/Rent')
        ->setCellValue('G1','Fecha cobro Fact')
        ->setCellValue('H1','Monto/Cobro')
        ->setCellValue('I1','Monto sin Iva')
        ->setCellValue('J1','Comisiones')
        ->setCellValue('K1','%');

        //ESTILO DE LA HOJA DE CALCULO
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);
        $objPHPExcel->getActiveSheet()->getStyle('H2:G200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('I2:J200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('J2:D200')->getNumberFormat()->setFormatCode("#,##0.00");
        //$objPHPExcel->getActiveSheet()->getStyle('F2:F200')->getNumberFormat()->setFormatCode("#,##0.00");

    $i = 2;  
    while($row = mysqli_fetch_array($consulta)){
     
    		$nom = utf8_encode($row1['CUSTNAME']);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row1['CUSTNMBR'])
                ->setCellValue('B'.$i, $nom)
                ->setCellValue('C'.$i, $row1['DOCNUMBR'])
                ->setCellValue('D'.$i, $row1['DOCDATE'])
                ->setCellValue('E'.$i, $row1['APFRDCNM'])
                ->setCellValue('F'.$i, $row1['APFRDCDT'])
                ->setCellValue('G'.$i, $row1['ActualApplyToAmount'])
                ->setCellValue('H'.$i, $row1['ApplyFromGLPostDate'])
                ->setCellValue('I'.$i, $row1['DOCDAYS'])
                ->setCellValue('J'.$i, $row1['MontoSinIva'])
                ->setCellValue('K'.$i, $row1['calc_result']);
                
        

      

        $i++;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, 'Vendedor: '.$row['CUSTNMBR'])
        ->setCellValue('C'.$i, 'Total Cobro:')
        ->setCellValue('D'.$i, $row['total_cobro'])
        ->setCellValue('E'.$i, 'Total sin Iva:')
        ->setCellValue('F'.$i, $row['total_sin_iva']);
        $i++;
        $i++;
       
    }
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Resumen_Comisiones.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;

?>


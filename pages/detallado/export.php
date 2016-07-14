<?php
 
include('../../log/conex_mysql.php');

$consulta = mysqli_query($conexion,"SELECT * FROM totales");



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
        ->setCellValue('A1','Rif Cliente')
        ->setCellValue('B1','Razon Social')
        ->setCellValue('C1','N° Fact/Cheq. Dev')
        ->setCellValue('D1','Fecha Emisión Fact.')
        ->setCellValue('E1','N° Cobro/Rent')
        ->setCellValue('F1','Fecha cobro Fact')
        ->setCellValue('G1','Monto/Cobro')
        ->setCellValue('H1','Monto sin Iva')
        ->setCellValue('I1','Días de Cobranza')
        ->setCellValue('J1','Monto sin Iva')
        ->setCellValue('K1','Calculo');



        //ESTILO DE LA HOJA DE CALCULO
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);
       
        $objPHPExcel->getActiveSheet()->getStyle('G2:G200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('J2:J200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('D2:D200')->getNumberFormat()->setFormatCode("#,##0.00");
        $objPHPExcel->getActiveSheet()->getStyle('F2:F200')->getNumberFormat()->setFormatCode("#,##0.00");

    $i = 2;  
    
    while($row = mysqli_fetch_array($consulta)){

      $sql = mysqli_query($conexion,"SELECT * FROM hist_commissions_sale 
                                     WHERE FULLNAME_SLSPRSN LIKE '%$row[CUSTNMBR]'"); 

      while($row1 = mysqli_fetch_array($sql)){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $row1['CUSTNMBR'])
                ->setCellValue('B'.$i, $row1['CUSTNAME'])
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

      }

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


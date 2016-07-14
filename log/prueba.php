
<?php
$serverName = "servidorgp"; //serverName\instanceName
$connectionInfo = array( "Database"=>"GEMPTA", "UID"=>"sa", "PWD"=>"Pl4nd3Acc10n");
$conn = sqlsrv_connect( $serverName, $connectionInfo );

$fecha = '20141101';
$fecha1='20141129';

$sql = "SELECT * FROM BP_HIST_COMMISSIONS_SALE 
		WHERE ApplyFromGLPostDate BETWEEN ? AND ? ";
$params = array($fecha,$fecha1);
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query( $conn, $sql, $params, $options );


$row_count = sqlsrv_num_rows( $stmt );
   
if ($row_count === false)
   echo "Error in retrieveing row count.";
else
   echo $row_count;
?>

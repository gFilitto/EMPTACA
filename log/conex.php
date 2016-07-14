<?php
/*$serverName = "WHLCCS0803\LOCALGP"; //serverName\instanceName
$connectionInfo = array( "Database"=>"GEMPTA", "UID"=>"sa", "PWD"=>"Jose21025");*/
$serverName = "servidorgp"; //serverName\instanceName
$connectionInfo = array( "Database"=>"COPAC", "UID"=>"sa", "PWD"=>"Pl4nd3Acc10n");
$conex = sqlsrv_connect( $serverName, $connectionInfo);

if( $conex ) {
    // echo "Conexión establecida.<br />";
}else{
     echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}
/*$serverName = "servidorgp"; //serverName\instanceName
$connectionInfo = array( "Database"=>"GEMPTA", "UID"=>"sa", "PWD"=>"Pl4nd3Acc10n");*/
?>
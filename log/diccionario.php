<?php 

function diccionario($pag){
    switch ($pag) {
        case 'listado':
             $pag = 'pages/resumen/listado.php';
            break;
        
        default:
            $pag = 'pages/inicio.php';
            break;
    }
    return $pag;
    
}



?>
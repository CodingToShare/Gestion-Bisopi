<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


    //definimos con header el tipo del documento (JSON)
    header("Content-Type:application/json");
    global $db;
    $sql = "SELECT 
            date_format( STR_TO_DATE( SK_PERIODO , '%Y%m%d')  , '%m/%d/%Y' ) 'MM/DD/YYYY'
            ,Dia 
            ,Mes 
            ,Trimetre 
            ,Semestre 
            ,Anio 
            ,NombreMes 
            ,NombreTrimestre 
            ,NombreSemestre
            ,PeriodoTrimestre  
            ,NombreMesAbr  
            ,AnioFiscal 
            ,MesFiscal 
            ,TrimestreFiscal 
            ,NombreMesFiscal
            ,AnioMesFiscal 
            ,SK_PERIODO 'YYYYMMDD'
            ,SK_PERIODO 
            ,Dialaboral 
            from carga_fecha
    "; //where id_recurso = 1
    
    //echo $sql;
    $objects = $db->selectArraysBySql($sql);
    // $recurso = array_map(utf8_encode ,$recurso );
    $respuesta_json = "";
    foreach( $objects as $object ){
        $object = array_map(utf8_encode , $object );
        $respuesta_json.= json_encode( $object )."\n";
    }
    //print_r( $recurso );
    //codificamos el json
    //$respuesta_json = json_encode($recurso);
    //pintamos el contenido del json
    echo $respuesta_json;
?>
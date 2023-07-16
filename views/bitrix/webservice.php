<?php

// ej: http://localhost/webservice/webservice.php?nombre=Pepe&peso=75&estatura=183


    //definimos con header el tipo del documento (JSON)
    header("Content-Type:application/json");
    global $db;
    $sql = "select
                id
                ,task_id
                ,portal_user_id
                ,responsible_id 
                ,COMMENT
                ,seconds
                ,create_date
                ,start_date
                ,finish_date
                ,create_text
                ,start_text
                ,finish_text
                ,fecha_registro
                FROM bitrix_tasks
    "; //where id_recurso = 1
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
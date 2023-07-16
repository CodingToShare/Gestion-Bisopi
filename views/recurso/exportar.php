<?php
    global $db;

    $sql ="
            SELECT            	
            	codigo_recurso
                , r.abreviatura
            	, recurso
            	,c.cargo
                ,case r.cod_estado_recurso when 'A' then 'Activo' else 'Inactivo' end estado_recurso                
                ,ci.ciudad
                ,r.telefono
                ,a.area
            FROM recurso r
            inner join cargo c
            on c.id_cargo = r.cod_cargo
            left join ciudad ci
            on ci.id_ciudad = r.cod_ciudad
            left join area a
            on a.id_area = r.cod_area
            where c.estado = 1
            order by recurso asc
                       ";
    //echo $sql;
    $datos = $db->selectObjectsBySql($sql);
    
    $delimiter = ";";
    $filename = "Recursos_" . date("Ymd"). ".csv";
    if( count( $datos) > 0){
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        $fields = array('Codigo Recurso', 'Abreviatura', 'Recurso','Cargo', 'Estado', 'Ciudad', 'Teléfono','Área');
        fputcsv($f, $fields, $delimiter);
        
        //output each row of the data, format line as csv and write to file pointer
        foreach( $datos as $dato ){            
            //$lineData = array($dato->fecha, $row['name'], $row['email'], $row['phone'], $row['created'], $status);
            $lineData = (array)$dato;
            fputcsv($f, $lineData, $delimiter);
        }
        
        //move back to beginning of file
        fseek($f, 0);
        
        //set headers to download file rather than displayed
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        
        //output all remaining data on a file pointer
        fpassthru($f);
    }
?>
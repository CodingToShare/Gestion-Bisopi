<?php
    global $db;
    $fechaini = $_REQUEST["fechaini"];
    $fechafin = $_REQUEST["fechafin"];
    
    $sql = "SELECT date_format( p.fecha , '%Y-%m-%d') fecha , r.codigo_recurso, r.recurso,pais, pr.codigo_proyecto, pr.proyecto , SUM( asignacion ) asignacion
            FROM programacion p
            INNER JOIN recurso r
            ON r.id_recurso = p.cod_recurso 
            INNER JOIN proyecto pr
            ON pr.id_proyecto = p.cod_proyecto
            left join pais pa on pa.id_pais = pr.cod_pais 
            WHERE p.estado = 1 and date_format( p.fecha , '%Y-%m-%d') between '".$fechaini."' and '".$fechafin."'
            group by fecha , r.recurso,pais, pr.proyecto
            ORDER BY fecha , recurso, proyecto ";
    //echo $sql;
    $datos = $db->selectObjectsBySql($sql);
    
    $delimiter = ";";
    $filename = "Programacion_" . str_replace( '-' , '' , $fechaini ) ."_a_". str_replace( '-' , '' , $fechafin ) . ".csv";
    if( count( $datos) > 0){
        //create a file pointer
        $f = fopen('php://memory', 'w');
        
        $fields = array('Fecha', 'Codigo Recurso', 'Recurso','País', 'Codigo Proyecto', 'Proyecto', 'Asignación');
        fputcsv($f, $fields, $delimiter);
        
        //output each row of the data, format line as csv and write to file pointer
        foreach( $datos as $dato ){
            //$status = ($row['status'] == '1')?'Active':'Inactive';
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
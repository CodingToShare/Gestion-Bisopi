<?php
    include_once 'views/administrativo/base.php';
    //print_r( $_REQUEST );
    
    require('include/export-xls.class.php');
    // Filter the excel data
    function filterData(&$str){
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    } 
    $datos = $db->selectObjectsBySql("select ".$sql);
    
    $delimiter = ";";
    $filename = "Reporte_Facturacion_" . date("Ymd"). ".xls";    
    #create an instance of the class
    $xls = new ExportXLS($filename);
    
    if( count( $datos ) > 0){        
        // Column names
        $fields = array('Codigo proyecto','Proyecto','Control de Cambio','Nro hito','Fecha hito','Nro dias','Pais','Cliente'
            ,'Estado Proyecto','Estado hito','TRM Proyectada','Valor Hito','Valor Hito Pesos','Vr. Retención en Pesos'
            ,'Vr. IVA','Vr. RETE IVA','Total Factura en Pesos','Moneda'
            ,'Fecha Aprobado Facturar','Fecha Facturado','Fecha Pagado');        
        // Display column names as first row
        $xls->addHeader($fields);
        //output each row of the data, format line as csv and write to file pointer
        foreach( $datos as $dato ){
            //$lineData = array($dato->fecha, $row['name'], $row['email'], $row['phone'], $row['created'], $status);
            $dato = (array)$dato;
            $dato = array_map("utf8_encode" , $dato );
            $row = $dato;
            $xls->addRow($row);
        }        
    }
    // Headers for download
    //header("Content-Type: application/vnd.ms-excel");
    //header("Content-Disposition: attachment; filename=\"$filename\"");
    
    // Render excel data
    $xls->sendFile();
?>
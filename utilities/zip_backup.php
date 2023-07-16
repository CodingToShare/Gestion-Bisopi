<?php
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', '600');
    try
    {
        $dir = dirname(__FILE__);
        $dir = $dir.'/../../../bkp_gestion/';
        $nombreArchivo = "bisionco_gestion_recurso_v2_".date( "Ym");
        $a = new PharData($dir.$nombreArchivo.'.tar');
        foreach (glob($dir."*.sql") as $filename) {
            $a->addFile( $filename , str_replace( $dir , "" , $filename ) );
        }
        $a->compress(Phar::GZ);
    }
    catch (Exception $e)
    {
        echo "Exception : " . $e;
    }
    
    if (file_exists($dir.$nombreArchivo.'.tar')) {
        unlink( $dir.$nombreArchivo.'.tar' );
        /*foreach (glob($dir."*.sql") as $filename) {
            unlink( $filename );
        }*/
    }
    
?>
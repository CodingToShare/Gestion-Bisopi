<?php
    $dir = dirname(__FILE__);
    $dir = $dir.'/../logs/';
    /*$nombreArchivo = "php-error.log";
    if (file_exists($dir.$nombreArchivo) ){
        unlink( $dir.$nombreArchivo );
    }*/
    
    foreach (glob($dir."php-error.log") as $filename ){
        //unlink( $filename );
        echo $filename."<br/>";
        if( file_exists( $filename ) ){
            unlink( $filename );
        }
    }
?>
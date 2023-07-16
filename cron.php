<?php
    ini_set("display_errors", 0 );
    ini_set("error_log", "logs/php-error.log");
    ini_set("default_charset","iso-8859-1");
    include_once  "conf/configuracion.php" ;
    include_once "include/mysqli.php";
    $filters = array_fill(0, 3, null);
    for($i = 1; $i < $argc; $i++) {
        $filters[$i - 1] = $argv[$i];
    }
    $filename = $filters[0];
    include "cron/".$filename.".php";
?>
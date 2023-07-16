<?php
    ini_set("display_errors", 0 );
    ini_set("error_log", "logs/php-error.log");
    ini_set("default_charset","iso-8859-1");
    include_once  "conf/configuracion.php" ;
    include_once "include/mysqli.php";
    //include 'views/'.$_REQUEST["view"]."/webservice.php";
    include 'services/'.$_REQUEST["view"].".php";
?>
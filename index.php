<?php    
    ini_set("display_errors", 0 );
    ini_set("error_log", "logs/php-error.log");
    ini_set("default_charset","iso-8859-1");
    define( "SES_NAME" , md5( $_SERVER["SERVER_NAME"] ) );
    
    date_default_timezone_set("America/Bogota");
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $start_time = microtime(TRUE);
    $timeout = 28800;
    //Set the maxlifetime of the session    
    ini_set( "session.gc_maxlifetime", $timeout );
    //Set the cookie lifetime of the session
    ini_set( "session.cookie_lifetime", $timeout );
    
    session_start();    
    
    require "include/xajax/xajax_core/xajax.inc.php";
    $xajax = new xajax();
    $xajax->configure( "defaultMode", "sync" );
    $xajax->configure( "characterEncoding", "ISO-8859-1" );
    $xajax->configure( "decodeUTF8Input", true );
    $xajax->configure( "javascript URI", "xajax" );
    
    include_once  "conf/configuracion.php" ;
    
    error_reporting( ERRORS );    
    $xajax->configure("debug",DEBUG_XAJAX);
    
    include_once "include/mysqli.php";
    include_once 'include/funciones.php';
    include_once 'include/funciones_xajax.php';
    include_once "include/usuario.php";
    global $_action;
    global $_view;
    $_action = trim( $_REQUEST["action"] );
    $_view  = trim( $_REQUEST["view"] );
    $usuario = new usuario();    
    $user = new stdClass();
    $user = $usuario->getInfoUsuario();
    $xajax->processRequest();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />  
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
		<?php $xajax->printJavascript("include/xajax"); ?>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link href="css/bootstrap-icons.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet" />        
        <title><?php echo TITLE;?></title>
        <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
		<link rel="icon" href="favicon/favicon.ico" type="image/x-icon"/>    	
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            
        <!-- Calendar  -->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>
        <script src="js/jquery-1.12.4.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/jquery-ui-timepicker-addon.js"></script>
        <script src="js/popper.min.js"></script>    
        <script src="js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script type="text/javascript" src="js/funciones.js"></script>
        
        <!-- Table with fix header -->
        <link href="css/bootstrap-table.min.css" rel="stylesheet"/>        
        <script src="js/bootstrap-table.min.js"></script>
        <link href="css/bootstrap-table-sticky-header.css" rel="stylesheet"/>
        <script src="js/bootstrap-table-sticky-header.min.js"></script>
        <!-- Multiselect -->        
        <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css" />
		<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
		<!--  Documentaci�n https://www.jqueryscript.net/demo/jQuery-Multiple-Select-Plugin-For-Bootstrap-Bootstrap-Multiselect/ -->
    </head>
    <body>
        <div class="container-fluid">
        <?php
            if( SITE_ACTIVE ){ 
                include_once 'views/menu.php';
                if( !$user ){		/*Si el usuario "NO" ha iniciado sesion*/                
                    include_once 'views/login.php';
                }else{
                    $filename = "views/".$_view."/index.php";                    
                    if( file_exists($filename)){
                        include_once $filename;
                    }else{                        
                        include_once "views/bienvenida.php";
                    }
                }
                include_once 'views/alert.php';
            }else{
                include_once 'views/maintenance.php';
            }
        ?>
        </div>
        <?php if( $_SESSION["SES_TAREA"] != "" ){?>
        <iframe src="timer_load.php" style="height:1px; width:1px; display:none;"></iframe>
        <?php }?>       
    </body>
</html>

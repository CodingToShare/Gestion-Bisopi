
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
<?php
    $db->close();    
    $end_time = microtime(TRUE);
    $time_taken =($end_time - $start_time)*1000;
    $time_taken = round($time_taken,5);
    //echo 'Page generated in '.$time_taken.' seconds.';
   
 ?>
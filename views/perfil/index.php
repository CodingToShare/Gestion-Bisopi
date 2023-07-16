 <?php
    global $_view;
    global $_action;
    $id = $_REQUEST["id"];
    include_once "views/".$_view."/model.php";   
?>

<div class="row">
<div class="col-12">
<?php    
    $filename = "views/".$_view."/".$_action.".php";
    if( file_exists($filename))
        include_once $filename;
        else{
            $filename = "views/".$_view."/perfil.php";
            if( file_exists($filename))
                include_once $filename;
        }
?>
</div>
</div>
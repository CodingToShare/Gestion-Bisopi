<?php
    global $_view;
    global $_action;
    $id = $_REQUEST["id"];
    include_once "views/".$_view."/model.php";
    global $db;    
    $objects = new stdClass();
    $sql ="
            SELECT
            	id_area
            	, area
            FROM area c
            where c.estado = 1
            order by area asc        
                       ";
    $objects = $db->selectObjectsBySql($sql) ;
    
?>

<div class="row">
<div class="col-sm-2">
	<nav class="nav flex-column nav-pills">
	<?php 
		echo '<a class="nav-link '.(( $_action == "" )?"active":"").'" href="index.php?view='.$_view.'">Areas <span class="badge badge-dark">'.count( $objects ).'</span></a>';
		echo '<a class="nav-link '.(( $_action != "" )?"active":"").'" href="index.php?view='.$_view.'&action=agregar">Nueva Area</a>';
	?>
	</nav>
</div>
<div class="col-10">
<?php    
    $filename = "views/".$_view."/".$_action.".php";
    if( file_exists($filename))
        include_once $filename;
        else{
            $filename = "views/".$_view."/list.php";
            if( file_exists($filename))
                include_once $filename;
        }
?>
</div>
</div>
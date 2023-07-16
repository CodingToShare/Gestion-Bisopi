<?php
    global $_view;
    global $_action;
    $id = $_REQUEST["id"];
    $_group = (($_REQUEST["group"]=="")?"estado_propuesta":$_REQUEST["group"] );    
    include_once "views/".$_view."/model.php";
    global $db;    
    $objects = new stdClass();
    $sql ="
            SELECT
            	*
            FROM estado_propuesta 
            where estado = 1
            order by orden    
                       ";
    $objects = $db->selectObjectsBySql($sql) ;
?>

<div class="row">
    <div class="col-12">
    	<div class="row m-3">
    	    <div class="btn-group" role="group" aria-label="Acciones">
                <button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "agregar" )?"active":"");?>"  onclick="window.location.href='index.php?view=<?php echo $_view;?>&action=agregar'">
                	<img title="Nuevo"  style="width:24px;cursor:pointer;" src="<?php echo IMG_ADD;?>" />
                	Nuevo
    			</button>
    			<button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == ""   )?"active":"");?>"  onclick="window.location.href='index.php?view=<?php echo $_view;?>'">
                	<img title="Listado"  style="width:24px;cursor:pointer;" src="<?php echo IMG_LIST;?>" />
                	Listado
    			</button>
			</div>
			
		</div>
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
<script>
$( "#exportar" ).click(function() {
	// crearDialogo('confirmacion','¿Está seguro de exportar los datos de facturación?','window.location.href=\'exportar.php?view=<?php echo $_view;?>"\';location.reload();');
  	window.open( "exportar.php?view=<?php echo $_view;?>" );      	
	return false;
});
</script>
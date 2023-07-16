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
            	id_propuesta
                ,propuesta
                ,estado_propuesta cod_estado_propuesta
                ,estado_propuesta
                ,cliente cod_cliente
                ,responsable
                ,valor
                ,m.abreviatura cod_moneda
                ,( valor * trm ) valor_pesos
                ,c.comentario
            FROM propuesta c
            inner join estado_propuesta ec
            on ec.id_estado_propuesta = c.cod_estado_propuesta
            left join moneda m
            on m.id_moneda = c.cod_moneda
            left join cliente cl
            on cl.id_cliente = c.cod_cliente
            left join trm t on t.cod_moneda = c.cod_moneda 
            and date_format( c.fecha_creacion , '%Y' )  = t.anio
            where c.estado = 1
            ".(($_REQUEST["search"]!="")?" and propuesta like '%".$_REQUEST["search"]."%'":"")."
            order by c.fecha_creacion    
                       ";
    //echo $sql;
    $objects = $db->selectObjectsBySql($sql) ;
?>

<div class="row">
    <div class="col-12">
    	<div class="row m-3">
    	    <div class="btn-group" role="group" aria-label="Acciones">
                <button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "agregar"   && $_view == "cotizacion")?"active":"");?>"  onclick="window.location.href='index.php?view=cotizacion&action=agregar'">
                	<img title="Nuevo"  style="width:24px;cursor:pointer;" src="<?php echo IMG_ADD;?>" />
                	Nuevo Lead
    			</button>
                <button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "agregar" && $_view == "propuesta" )?"active":"");?>"  onclick="window.location.href='index.php?view=propuesta&action=agregar'">
                	<img title="Nuevo"  style="width:24px;cursor:pointer;" src="<?php echo IMG_ADD;?>" />
                	Nuevo Deal
    			</button>
    			<button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == ""    && $_view == "cotizacion" )?"active":"");?>"  onclick="window.location.href='index.php?view=cotizacion'">
                	<img title="Listado"  style="width:24px;cursor:pointer;" src="<?php echo IMG_LIST;?>" />
                	Listado Leads
    			</button>
    			<button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "" && $_view == "propuesta"  )?"active":"");?>"  onclick="window.location.href='index.php?view=propuesta'">
                	<img title="Listado"  style="width:24px;cursor:pointer;" src="<?php echo IMG_LIST;?>" />
                	Listado Deals
    			</button>
    			<button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "kanban"  && $_view == "cotizacion"  )?"active":"");?>"  onclick="window.location.href='index.php?view=cotizacion&action=kanban'">
                	<img title="Kanban"  style="width:24px;cursor:pointer;" src="<?php echo IMG_KANBAN;?>" />
                	Leads
    			</button>
    			<button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "kanban"  && $_view == "propuesta" )?"active":"");?>"  onclick="window.location.href='index.php?view=propuesta&action=kanban'">
                	<img title="Kanban"  style="width:24px;cursor:pointer;" src="<?php echo IMG_KANBAN;?>" />
                	Deals
    			</button>
			</div>
		</div>
		
    <?php if( $_action == "" ){?>
    	<div class="col-sm-4"> 
			<input class="form-control" id="search" type="text" placeholder="Buscar..." autocomplete="off"  >
		</div>
		<?php }elseif( $_action == "kanban" ){?>
		<form name="form1" id="form1" method="post"  action="index.php">
			<div class="form-row">
        		<input type="hidden" name="view" id="view" value="propuesta" />
        		<input type="hidden" name="action" id="action" value="kanban" />
        		<div class="form-group col-sm-3 "> 
        			<input class="form-control" id="search" name="search" type="text" placeholder="Buscar..." autocomplete="off" value="<?php echo (($_REQUEST["search"]!="")?$_REQUEST["search"]:"");?>" />
    			</div>	
    			<div class="form-group col-sm-1">
        			<button id="save" name='save' type="submit" class="btn btn-success">Filtrar</button>
    			</div>
    			<div class="form-group col-sm-1">
    				<button id="clear" name='clear' type="button" class="btn btn-danger" >Limpiar</button>        			
        		</div>
			</div>
		</form>
	<?php } ?>
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

    $('#clear').click(function() {
        location.href='index.php?view=propuesta&action=kanban';
    });
</script>
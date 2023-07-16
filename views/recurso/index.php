 <?php
    global $_view;
    global $_action;
    $id = $_REQUEST["id"];
    include_once "views/".$_view."/model.php";
    global $db;    
    $objects = new stdClass();
    $sql ="
            SELECT
            	id_recurso
            	, codigo_recurso
                , r.abreviatura
            	, recurso
            	, correo
            	,c.cargo
                ,r.cod_estado_recurso
                ,r.cod_ciudad 
                ,ci.ciudad
                ,r.telefono
                ,r.cod_area
                ,a.area
            FROM recurso r
            inner join cargo c
            on c.id_cargo = r.cod_cargo
            left join ciudad ci
            on ci.id_ciudad = r.cod_ciudad
            left join area a 
            on a.id_area = r.cod_area 
            where c.estado = 1
            order by recurso asc        
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
    			<button type="button" class="btn p-1 btn-outline-secondary" id="exportar" name="exportar">
                	<img title="Exportar Facturación"  style="width:24px;cursor:pointer;" src="<?php echo IMG_DOWNLOAD;?>" />
                	Exportar
    			</button>
			</div>
			<?php if( $_action == "" ){?>
        	<div class="col-sm-4"> 
    			<input class="form-control" id="search" type="text" placeholder="Buscar..." autocomplete="off" >
    		</div>
    		<?php }?>
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
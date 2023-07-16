<?php
    global $_view;
    global $_action;
    $id = $_REQUEST["id"];
    include_once "views/".$_view."/model.php";
    global $db;    
    $objects = new stdClass();
    $sql ="
            SELECT id_cierre_permiso 
                , cod_responsable codigo
                , recurso cod_responsable
                , c.fecha                 
                , case when c.estado_permiso = 1 then 'Activa' else 'Inactiva' end estado_permiso
                , concat( semana_inicio , ' - ' , semana_fin ) cod_cierre
            FROM cierre_permiso c     
            inner join recurso r on r.id_recurso =  c.cod_responsable
            left join cierre ci on ci.id_cierre = c.cod_cierre
            where c.estado = 1
            order by fecha desc        
                       ";
    $objects = $db->selectObjectsBySql($sql) ;
    
?>

<div class="row">
<div class="col-12">
	<div class="row m-3">
	    <div class="btn-group" role="group" aria-label="Acciones">
            <button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "agregar" )?"active":"");?>"  onclick="window.location.href='index.php?view=<?php echo $_view;?>&action=agregar'">
            	<img title="Nuevo"  style="width:24px;cursor:pointer;" src="<?php echo IMG_ADD;?>" />
            	Nueva Abrir Semana
			</button>
			<button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == ""   )?"active":"");?>"  onclick="window.location.href='index.php?view=<?php echo $_view;?>'">
            	<img title="Listado"  style="width:24px;cursor:pointer;" src="<?php echo IMG_LIST;?>" />
            	Listado Solicitudes
			</button>
			<button type="button" class="btn p-1 btn-outline-secondary <?php echo (( $_action == "calendar"   )?"active":"");?>"  onclick="window.location.href='index.php?view=<?php echo $_view;?>&action=calendar'">
            	<img title="Calendario Cierres"  style="width:24px;cursor:pointer;" src="<?php echo IMG_CALENDAR;?>" />
            	Calendario Cierres
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
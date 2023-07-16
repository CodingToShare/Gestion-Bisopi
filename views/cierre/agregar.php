<?php
    global $db;
    
    $object = $db->selectObject("cierre_permiso", "id_cierre_permiso = '".$id."'" );
      
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> Solicitud Abrir Semana</div>
<div class="border border-secondary rounded px-3 pt-3">
<form name="form1" id="form1" method="post" onsubmit="return false;">
	
  <div class="form-row">
    <div class="form-group col-md-5">
    	<label for="cod_responsable">Responsable</label>
    	<select class="custom-select" id="cod_responsable" name="cod_responsable">
			<option value='-' selected>Seleccione un responsable</option>
            <?php
            $recursos =  $db->selectObjectsBySql("select r.* from recurso r where estado = 1 order by cod_estado_recurso , r.recurso"); //inner join ( select distinct cod_recurso  from programacion pr where  pr.cod_proyecto =  '".$objeto->id_proyecto."' ) pr on pr.cod_recurso = r.id_recurso  where r.estado = 1 and r.cod_estado_recurso ='A' order by r.recurso");
                $txt_estado = "";
                foreach( $recursos as $recurso ){
                    if( $txt_estado == "" || $txt_estado != $recurso->cod_estado_recurso ){
                        echo "<option ".(($object->cod_responsable == $recurso->id_recurso )?"selected":"")." style='font-weight:bold' disabled value='-'>".(( $recurso->cod_estado_recurso =='A')?"- Activos - ":"- Inactivos - ")."</option>";
                    }
                    echo "<option ".(($object->cod_responsable == $recurso->id_recurso )?"selected":"")." ".(($recurso->cod_estado_recurso=='I')?"disabled":"")."  value='".$recurso->id_recurso."'>".$recurso->recurso."</option>";
                    $txt_estado = $recurso->cod_estado_recurso;
                }
            ?>
		</select>
  	</div> 
    <div class="form-group col-md-3">
    	<label for="cod_cierre">Semana</label>
    	<select class="custom-select" id="cod_cierre" name="cod_cierre">
			<option value='' selected>Seleccione un semana</option>
            <?php
                $semanas =  $db->selectObjects("cierre","fecha_cierre < '".date('Y-m-d H:i:s')."'","semana_fin desc");                
                foreach( $semanas as $semana ){                    
                    echo "<option ".(($object->cod_cierre == $semana->id_cierre)?"selected":"")."  value='".$semana->id_cierre."'>".$semana->semana_inicio." - ".$semana->semana_fin."</option>";                    
                }
            ?>
		</select>
  	</div> 
  	<div class="form-group col-md-3">
    	<label for="fecha">Fecha Limite Permiso</label>
    	<input type="text" autocomplete="off"  placeholder="YYYY-MM-DD" class="form-control" id="fecha" name="fecha" value="<?php echo $object->fecha;?>">    	
    </div>
    </div>
	<div class="form-group">
		<input type="hidden" id="id_cierre_permiso" name="id_cierre_permiso" value="<?php echo $object->id_cierre_permiso;?>">
		<input type="hidden" id="estado_permiso" name="estado_permiso" value="<?php echo (( isset( $object ) )? $object->estado_permiso:1);?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
</div>
</div>
<script>

    $( function() {
    	$( "#fecha" ).datetimepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'cierre_permiso' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>
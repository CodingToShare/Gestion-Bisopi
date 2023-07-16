<?php
    global $db;
    
    $object = $db->selectObject("retencion", "id_retencion = '".$id."'" );    
    $paises = $db->selectObjects("pais","estado = 1","pais");
?>
<form name="form1" id="form1" method="post" onsubmit="return false;">
	    
	 <div class="form-row">
		<div class="form-group col-md-6">
    		<label for="cod_pais">País</label>
      		<select class="custom-select" id="cod_pais" name="cod_pais">
    			<option value='-' selected>Seleccione un País</option>
            <?php
                foreach( $paises as $pais ){
                    echo "<option ".(( $pais->id_pais ==  $object->cod_pais)?"selected":"")." value='".$pais->id_pais."'>".$pais->pais." (".$pais->abreviatura.")</option>";
                }
              	?>
    		</select>
        </div>
        <div class="form-group col-md-6">
    		<label for="retencion">% Retención</label>
    		<input type="number" class="form-control" id="retencion" name="retencion" value="<?php echo $object->retencion;?>">
        </div>        
	</div>
	<div class="form-row">
    	<div class="form-group col-md-6">
			<label for="fecha_inicio">Fecha Inicio Vigencia</label>
      		<input type="text" autocomplete="off"  placeholder="YYYY-MM-DD" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $object->fecha_inicio;?>">     		
        </div>
        <div class="form-group col-md-6">
          		<label for="fecha_fin">Fecha Fin Vigencia</label>
          		<input type="text" autocomplete="off"  placeholder="YYYY-MM-DD" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $object->fecha_fin;?>">  
    	</div>
  	</div>
	<div class="form-group">
		<input type="hidden" id="id_retencion" name="id_retencion" value="<?php echo $object->id_retencion;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
<script>
	$( function() {
    	$( "#fecha_inicio" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
    $( function() {
    	$( "#fecha_fin" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'retencion' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>
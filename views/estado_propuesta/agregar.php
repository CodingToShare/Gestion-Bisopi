<?php
    global $db;
    $objeto = $db->selectObject("estado_propuesta", "id_estado_propuesta= '".$id."'" );    
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> Estado Deals</div>
<div class="border border-secondary rounded px-3 pt-3">
<form name="form1" id="form1" method="post" onsubmit="return false;">
  <div class="form-row">
  	<div class="form-group col-md-12">
    	<label for="estado_propuesta">Estado Deals <span class="text-danger" style='font-weight:bold'>*</span></label>
    	<input type="text" class="form-control" id="estado_propuesta" name="estado_propuesta" value="<?php echo $objeto->estado_propuesta;?>">
  	</div>
  </div>
	<div class="form-row">
		<div class="form-group col-md-4">
    		<label for="abreviatura">Abreviatura</label>
      		<input type="text" autocomplete="off" class="form-control" id="abreviatura" name="abreviatura" value="<?php echo $objeto->abreviatura;?>"> 
        </div>
    	 <div class="form-group col-md-2">
    		<label for="orden">Orden </label>
      		<input type="text" autocomplete="off" class="form-control" id="orden" name="orden" value="<?php echo $objeto->orden;?>"> 
        </div>
    	 <div class="form-group col-md-2">
    		<label for="visible">Visible </label>
      		<input type="text" autocomplete="off" class="form-control" id="visible" name="visible" value="<?php echo $objeto->visible;?>"> 
        </div>
     	 <div class="form-group col-md-4">
    		<label for="color">Color</label>
      		<input type="color" autocomplete="off" class="form-control" id="color" name="color" value="#<?php echo $objeto->color;?>"> 
     	 </div>
  </div>   
	<div class="form-group">
		<input type="hidden" id="id_estado_propuesta" name="id_estado_propuesta" value="<?php echo $objeto->id_estado_propuesta;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>
  		<input type="hidden" id="back" name="back" value="<?php echo $_SERVER["HTTP_REFERER"];?>">
  	</div>
</form>
</div>
</div>
<script>
    $( function() {
    	$( "#fecha_cotizacion" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'estado_propuesta' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {    	
      	if( this.form.back.value != ""){
      		window.location.href = this.form.back.value;
      	}else{
      		window.location.href = 'index.php?view=<?php echo $_view;?>';
      	}
    	return false;
    });
</script>
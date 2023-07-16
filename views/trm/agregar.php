<?php
    global $db;
    
    $object = $db->selectObject("trm", "id_trm= '".$id."'" );    
    $monedas = $db->selectObjects("moneda","estado = 1","moneda");
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> TRM</div>
<div class="border border-secondary rounded px-3 pt-3">
<form name="form1" id="form1" method="post" onsubmit="return false;">
	    
	 <div class="form-row">
		<div class="form-group col-md-4">
    		<label for="cod_moneda">Moneda</label>
      		<select class="custom-select" id="cod_moneda" name="cod_moneda">
    			<option value='-' selected>Seleccione</option>
                <?php
                    foreach( $monedas as $moneda ){
                        echo "<option ".(( $moneda->id_moneda==  $object->cod_moneda)?"selected":"")." value='".$moneda->id_moneda."'>".$moneda->abreviatura."</option>";
                    }
                  	?>
    		</select>  
        </div>
        <div class="form-group col-md-4">
    		<label for="trm">TRM</label>
    		<input type="number" class="form-control" id="trm" name="trm" value="<?php echo $object->trm;?>">
        </div>        	
        <div class="form-group col-md-4">
          		<label for="anio">Año</label>
          		<input type="number" autocomplete="off"  class="form-control" id="anio" name="anio" value="<?php echo $object->anio;?>">  
    	</div>
  	</div>
	<div class="form-group">
		<input type="hidden" id="id_trm" name="id_trm" value="<?php echo $object->id_trm;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>  		
  	</div>
</form>
</div>
</div>
<script>
    $( "#save" ).click(function() {
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'trm' , <?php echo $json_fields;?> );
    });
    $( "#cancel" ).click(function() {
      	window.location.href = 'index.php?view=<?php echo $_view;?>';
    	return false;
    });
</script>
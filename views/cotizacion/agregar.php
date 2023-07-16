<?php
    global $db;
    $objeto = $db->selectObject("cotizacion", "id_cotizacion= '".$id."'" );    
    $estados = $db->selectObjects("estado_cotizacion","estado = 1");
    $monedas = $db->selectObjects("moneda","estado = 1");
    $clientes = $db->selectObjects("cliente","estado = 1","cliente");    
?>
<div class="p-3">
<div class="h3" ><?php echo (($id!="")?"Editar":"Crear");?> Leads</div>
<div class="border border-secondary rounded px-3 pt-3">
<form name="form1" id="form1" method="post" onsubmit="return false;">
  <div class="form-row">
  	<div class="form-group col-md-12">
    	<label for="cotizacion">Lead <span class="text-danger" style='font-weight:bold'>*</span></label>
    	<input type="text" class="form-control" id="cotizacion" name="cotizacion" value="<?php echo $objeto->cotizacion;?>">
  	</div>
  </div>
  <div class="form-row">
		<div class="form-group col-md-5">
    		<label for="cod_cliente">Cliente <span class="text-danger" style='font-weight:bold'>*</span></label>
      		<select class="custom-select" id="cod_cliente" name="cod_cliente">
    			<option value='-' selected>Seleccione un Cliente</option>
            <?php
                foreach( $clientes as $cliente ){
                    echo "<option ".(( $cliente->id_cliente ==  $objeto->cod_cliente)?"selected":"")." value='".$cliente->id_cliente."'>".$cliente->cliente." (".$cliente->codigo_cliente.")</option>";
                }
              	?>
    		</select>
        </div>
        <div class="form-group col-md-7">
    		<label for="responsable">Responsable Cliente </label>
    		<input type="text" class="form-control" id="responsable" name="responsable" value="<?php echo $objeto->responsable;?>">
        </div>        
	</div>
	<div class="form-row">
		<div class="form-group col-md-6">
    		<label for="cod_estado_cotizacion">Estado <span class="text-danger" style='font-weight:bold'>*</span></label>
      		<select class="custom-select" id="cod_estado_cotizacion" name="cod_estado_cotizacion">
    			<option value='-' selected>Seleccione un Estado</option>
            <?php
                foreach( $estados as $estado ){
                    echo "<option ".(( $estado->id_estado_cotizacion==  $objeto->cod_estado_cotizacion)?"selected":"")." value='".$estado->id_estado_cotizacion."'>".$estado->estado_cotizacion."</option>";
                }
              	?>
    		</select>
        </div>
    	 <div class="form-group col-md-3">
    		<label for="cod_moneda">Moneda </label>
      		<select class="custom-select" id="cod_moneda" name="cod_moneda">
    			<option value='-' selected>Seleccione una moneda</option>
            <?php
                foreach( $monedas as $moneda ){
                    echo "<option ".(( $moneda->id_moneda==  $objeto->cod_moneda)?"selected":"")." value='".$moneda->id_moneda."'>".$moneda->abreviatura."</option>";
                }
              	?>
    		</select>
        </div>
     	 <div class="form-group col-md-3">
    		<label for="valor_proyecto">Valor</label>
      		<input type="text" autocomplete="off" class="form-control" id="valor" name="valor" value="<?php echo $objeto->valor;?>"> 
     	 </div>
  	</div>
	<div class="form-group">
		<label for="comentario">Comentarios</label>
		<textarea rows="5" id="comentario" name="comentario" class="form-control" ><?php echo $objeto->comentario;?></textarea>
 	</div>
	<div class="form-group">
		<input type="hidden" id="id_cotizacion" name="id_cotizacion" value="<?php echo $objeto->id_cotizacion;?>">
        <button id="save" name='save' type="submit" class="btn btn-success">Guardar</button>
  		<button id="cancel" name='cancel' type="submit" class="btn btn-dark">Cancelar</button>
  		<input type="hidden" id="back" name="back" value="<?php echo $_SERVER["HTTP_REFERER"];?>"/>
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
      	xajax_guardar( xajax.getFormValues( this.form , true ) , 'cotizacion' , <?php echo $json_fields;?> );
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
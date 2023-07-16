<form name="form2" id="form2" method="post" onsubmit="return false;">
<!-- Modal -->
<div class="modal fade" id="ModalRegistro" tabindex="-1"  data-backdrop="static" data-keyboard="true" role="dialog" aria-labelledby="ModalRegistro" aria-hidden="true">
  <div class="modal-dialog modal-lg" style='font-size:14px'>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
            //$proyecto_hito =  $db->selectObjects("proyecto_hito","estado = 1");
        ?>        
        	<div class="form-row">
        		<div class="form-group col-md-3">
            		<label for="fecha_registro">Fecha Registro</label>
            		<input type="text"  autocomplete="off" placeholder="YYYY-MM-DD" class="form-control" id="fecha_registro" name="fecha_registro" value="<?php echo date("Y-m-d");?>">
				</div>
				<div class="form-group col-md-6">        			
            		<label for="hora">Tiempo Ejecutado</label>
            		<div class="form-row">
            			<div class="form-group col-md-4">
            				<input type="number" class="form-control" id="hora" name="hora" min="0"  value="" /> 
            			</div>
            			<div class="form-group col-md-2"><label for="hora">Horas</label></div>            			
            			<div class="form-group col-md-4">
            				<input type="number" class="form-control" id="minuto" name="minuto" min="0" max="59" value="">
            				<input type="hidden" class="form-control" id="segundo" name="segundo" min="0" max="59" value="">
            			</div>
            			<div class="form-group col-md-2"><label for="minuto">Minutos</label></div>
            		</div>            		
				</div>
        		<div class="form-group col-md-12">
            		<label for="comentario">Comentario</label>
        			<input type="text" class="form-control" id="comentario" name="comentario" value="">
				</div>
			</div>
			
      </div>
      <div class="modal-footer">   
      	<input type="hidden" id="id_proyecto_tarea" name="id_proyecto_tarea" value="">
      	<input type="hidden" id="vista" name="vista" value="">
      	<input type="hidden" id="id_proyecto_tarea_registro" name="id_proyecto_tarea_registro" value="">      	      
        <button id="save_tarea" name='save_tarea' type="button" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
</form>
<script>
    $( function() {
    	$( "#fecha_registro" ).datepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});  	
    } );
    $( "#save_tarea" ).click(function() {
      	xajax_guardarRegistroTarea( xajax.getFormValues( this.form , true ) );
    });
    $( "#cancel" ).click(function() {
      	//window.location.href = 'index.php?view=<?php echo $_view;?>';
      	history.back();
    	return false;
    });
</script>
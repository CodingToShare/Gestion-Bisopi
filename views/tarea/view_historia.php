<form name="form2" id="form2" method="post" onsubmit="return false;">
<!-- Modal -->
<div class="modal fade" id="ModalHistorialRegistro" tabindex="-1"  data-backdrop="static" data-keyboard="true" role="dialog" aria-labelledby="ModalHistorialRegistro" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" style='font-size:14px'>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalTitleHistorial">Historial Registro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="div_historial" name='div_historial'>
      </div>
      <div class="modal-footer">   
      	<input type="hidden" id="id_proyecto_tarea" name="id_proyecto_tarea" value="">        
        <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
</form>
<script>
    $( "#cancel" ).click(function() {
      	//window.location.href = 'index.php?view=<?php echo $_view;?>';
      	history.back();
    	return false;
    });
</script>
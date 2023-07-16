<div class="h3" >Reporte Facturación</div>
<?php
    
    //echo $sql;
    include_once 'views/administrativo/base.php';
    $sql = "SELECT
                        	id_proyecto
                        	,id_proyecto_hito
                        	, ".$sql;
    $proyectos = $db->selectObjectsBySql($sql) ;
    //echo $db->error();
?>
<form name="form1" id="form1" method="post"  action="index.php?view=administrativo&action=reporte">
    <input type="hidden" name="view" id="view" value="administrativo" />    
    <input type="hidden" name="action" id="action" value="reporte" />
    <div class="form-row">
        <div class="form-group col-md-2">
          <label for="tfecha">Fecha </label>
          <select class="custom-select" id="tfecha" name="tfecha">        		
            <?php
                foreach( $array_txt_filtros_fecha as $tpos => $tval ){
                    echo "<option ".(( $tpos ==  $filtro_t_fecha)?"selected":"")." value='".$tpos."'>".$tval."</option>";
                }
              	?>
        	</select>
        </div>
        <div class="form-group col-md-2">
          <label for="fecha_inicio">Fecha Inicio</label>
          <input  autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $mesant;?>">
        </div>
        <div class="form-group col-md-2">
          <label for="fecha_fin">Fecha Fin</label>
          <input autocomplete="off" type="text" placeholder="YYYY-MM-DD" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $mespos?>">
        </div>
        <div class="form-group col-md-1">
          <label for="cod_pais">País</label>
          <select class="custom-select" id="cod_pais" name="cod_pais">
        		<option value='-' selected>Todos</option>
            <?php
                $paises = $db->selectObjects("pais","estado = 1","pais");
                foreach( $paises as $pais ){
                    echo "<option ".(( $pais->id_pais ==  $filtro_pais)?"selected":"")." value='".$pais->id_pais."'>".$pais->pais." (".$pais->abreviatura.")</option>";
                }
              	?>
        	</select>
        </div>
        <div class="form-group col-md-1">
          <label for="cod_cliente">Cliente</label>
          <select class="custom-select" id="cod_cliente" name="cod_cliente">
        		<option value='-' selected>Todos</option>
            <?php
                $clientes = $db->selectObjects("cliente","estado = 1","cliente");
                foreach( $clientes as $cliente ){
                    echo "<option ".(( $cliente->id_cliente ==  $filtro_cliente)?"selected":"")." value='".$cliente->id_cliente."'>".$cliente->cliente."</option>";
                }
              	?>
        	</select>
        </div>
        <div class="form-group col-md-1">
        	<label for="cod_estado">Estado Proy</label><br/>
        	<select id="cod_estado" name="cod_estado[]" multiple="multiple">
        	<?php $estados =$db->selectObjects("estado_proyecto", "estado =1");
                foreach( $estados as $estado ){
                    echo '<option value="'.$estado->id_estado_proyecto.'" '.(( array_search( $estado->id_estado_proyecto , $array_estados ) !== false )?"selected":"").'>'.$estado->estado_proyecto.'</option>';
                }
            ?>
            </select>
        </div>       
        <div class="form-group col-md-1">
        	<label for="cod_estado_hito">Estado Hitos</label><br/>
        	<select id="cod_estado_hito" name="cod_estado_hito[]" multiple="multiple">
        	<?php 
        	   foreach( $estados_hito as $pos => $val ){
        	       echo '<option value="'.$pos.'" '.(( array_search( $pos , $array_estados_hito ) !== false )?"selected":"").'>'.$val.'</option>';
                }
            ?>
            </select>
        </div>
        <div class="form-group col-md-1">    	
        	<button id="save" name='save' type="submit" class="btn btn-success mt-4">Filtrar</button>
        </div>
        <div class="form-group col-md-1">
        	<button type="button" class="btn p-1 btn-outline-secondary mt-4" id="exportar" name="exportar">
            	<img title="Exportar Facturación"  style="width:24px;cursor:pointer;" src="<?php echo IMG_DOWNLOAD;?>" />
            	Exportar
    		</button>
		</div>
    </div>
</form>    
<?php if( count( $proyectos ) > 0 ){ ?>
    <table class="table table-striped table-hover" style="font-size:13px">
        <thead class="thead-dark">
        <tr>
            <th class="p-1" scope="col"></th>
            <th class="p-1" scope="col"></th>
            <th class="p-1" scope="col">Código</th>
            <th class="p-1" scope="col">Proyecto</th>
            <th class="p-1" scope="col">País</th>
            <th class="p-1" scope="col">Cliente</th>
            <th class="p-1" scope="col">Estado Proyecto</th>
            <th class="p-1" scope="col">Control Cambio</th>
            <th class="p-1" scope="col">Hito</th>
            <th class="p-1" scope="col">Fecha Hito</th>
            <th class="p-1" scope="col">Nro. Días</th>
            <th class="p-1" scope="col">Estado Hito</th>
            <th class="p-1" scope="col">Moneda</th>
            <th class="p-1" scope="col">Valor Hito</th>
            <th class="p-1" scope="col">TRM Proyectada</th>
            <th class="p-1" scope="col">Valor Hito Pesos</th>
            <th class="p-1" scope="col">Vr. Retención en Pesos</th>
            <th class="p-1" scope="col">Vr. IVA</th>
            <th class="p-1" scope="col">Vr. RETE IVA</th>
            <th class="p-1" scope="col">Total Factura en Pesos</th>
            <th class="p-1" scope="col">Fecha Aprobado Facturar</th>
            <th class="p-1" scope="col">Fecha Facturado</th>
            <th class="p-1" scope="col">Fecha Pagado</th>
        </tr>
        </thead>
        <tbody id="tb_search" class=" text-nowrap">
        <?php
            $contador= 1;
            $total_estado = 0;
            $estado_txt = "";
            foreach( $proyectos as $proyecto ){ 
        ?>
        	<tr>
        		<th class="p-1" scope="row"><?php echo ( $contador++);?></th>
				<td class="p-1"><a href="index.php?view=<?php echo 'proyecto&action=hito&id='.$proyecto->id_proyecto."#hito_".$proyecto->id_proyecto_hito;?>"><img src="<?php echo IMG_HITO;?>" style="width:24px" title="Hitos" /></a></td>
        		<td class="p-1"><?php echo $proyecto->codigo_proyecto;?></td>
        		<td class="p-1"><?php echo $proyecto->proyecto;?></td>
        		<td class="p-1"><?php echo $proyecto->pais;?></td>
        		<td class="p-1"><?php echo $proyecto->cliente;?></td>
        		<td class="p-1"><?php echo $proyecto->estado_proyecto;?></td>
        		<td class="p-1" style='text-align:center;'><?php echo (( $proyecto->control_cambio == 'Si')?"<img title='Control de Cambio'  style='width:16px;' src='".IMG_PATH."change.png'/>":"-");?></td>
        		<td class="p-1"><?php echo "Hito # ".$proyecto->nro_hito;?></td>        		
        		<td class="p-1"><?php echo $proyecto->fecha_hito;?></td>
        		<td class="p-1"><?php echo $proyecto->nro_dias;?></td>
        		<td class="p-1"><?php echo $proyecto->estado_hito;?></td>        		
        		<td class="p-1"><?php echo $proyecto->moneda;?></td>
        		<td class="p-1" style='text-align:right;'><?php echo number_format( $proyecto->valor_hito ,2 ,',','.');;?></td>
        		<td class="p-1" style='text-align:right;'><?php echo number_format( $proyecto->trm ,2 ,',','.');;?></td>
        		<td class="p-1" style='text-align:right;'><?php echo number_format( $proyecto->valor_hito_pesos ,2 ,',','.');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo number_format( $proyecto->valor_retencion_pesos ,2 ,',','.');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo number_format( $proyecto->iva ,2 ,',','.');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo number_format( $proyecto->rete_iva,2 ,',','.');?></td>
        		<td class="p-1" style='text-align:right;'><?php echo number_format( $proyecto->total_pesos ,2 ,',','.');?></td>        		
        		<td class="p-1"><?php echo $proyecto->fecha_aprobado_facturar;?></td>
        		<td class="p-1"><?php echo $proyecto->fecha_facturado;?></td>
        		<td class="p-1"><?php echo $proyecto->fecha_pagado;?></td>
        	</tr>
        <?php
                $total_estado+=$proyecto->valor_hito_pesos;
                $total_estado_retencion+=$proyecto->valor_retencion_pesos;
                $total_pesos+=$proyecto->total_pesos;
            }            
        ?>
        	<tr  class="thead-dark">        		
        		<th colspan='15'>Total</th>
        		<th class="p-1" style='text-align:right;'><?php echo number_format( $total_estado ,2 ,',','.');?></th>
        		<th class="p-1" style='text-align:right;'><?php echo number_format( $total_estado_retencion ,2 ,',','.');?></th>
        		<th colspan='2'></th>
        		<th class="p-1" style='text-align:right;'><?php echo number_format( $total_pesos ,2 ,',','.');?></th>
        		<th colspan='3'></th>
        	</tr>
        </tbody>
    </table>
<?php } ?>
<script>
	$(function(){
		$('#cod_estado').multiselect({
			buttonWidth: '100%',
			includeSelectAllOption: true
		});
	});
	$(function(){
		$('#cod_estado_hito').multiselect({
			buttonWidth: '100%',
			includeSelectAllOption: true
		});
	});

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
    
$( "#exportar" ).click(function() {
	// crearDialogo('confirmacion','¿Está seguro de exportar los datos de facturación?','window.location.href=\'exportar.php?view=<?php echo $_view;?>"\';location.reload();');
  	window.open( "exportar.php?view=<?php echo $_view."&output=xls&fecha_inicio=".$_REQUEST["fecha_inicio"]."&fecha_fin=".$_REQUEST["fecha_fin"].( ($_REQUEST["cod_pais"]!= "")?"&cod_pais=".$_REQUEST["cod_pais"]:"").( ($_REQUEST["cod_estado"]!= "")?"&f_estado=".$filtro_estado:"").( ($_REQUEST["cod_estado_hito"]!= "")?"&f_estado_hito=".$filtro_estado_hito:"");?>" );      	
	return false;
});
</script>

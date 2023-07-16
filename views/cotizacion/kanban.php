<style>

.container22 {  
  margin: auto;
  display: flex;
  flex-direction: column;
}

.kanban-heading {
  display: flex;
  flex-direction: row;
  justify-content: center;
  font-family: sans-serif;
}

.kanban-board {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  font-family: sans-serif;
}


.kanban-block {
  margin-right:5px;
  width: 225px;
  min-height: 350px;
  border-radius: 0.3rem;
}


.kanban-block-title {
  font-weight:bold;  
  padding: 0.6rem;
  font-size:11px;
}
.kanban-block-money {
  font-weight:bold;  
  padding: 0.6rem;
  font-size:16px;
  text-align:center;
}

.kanban-block-body {   
  padding: 0.6rem; 
  min-height: 320px;
}

.task {
  background-color: white;
  margin: 0.2rem 0rem 0.3rem 0rem;
  border: 0.1rem solid black;
  border-radius: 0.2rem;
  padding: 5px;
  cursor:pointer;  
  font-size:11px;
}

</style>
<script>
	function drag(ev) {
		ev.dataTransfer.setData("text", ev.target.id);
    }

    function allowDrop(ev) {
        ev.preventDefault();
        //alert( ev.target.id );
    }

    function drop(ev , me = null ) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.currentTarget.appendChild(document.getElementById(data));
        //alert( data + ' - ' +ev.target.id + ' - '+ document.getElementById( ev.target.id ).parentNode.id ) ;
        if( data != ev.target.id && ev.target.id !== null )
        	if( document.getElementById( ev.target.id ).parentNode.id )
        		xajax_cambiarEstado( 'cotizacion' , data , document.getElementById( ev.target.id ).parentNode.id );
        	else
        		xajax_cambiarEstado( 'cotizacion' , data , ev.target.id );
    }
        
</script>

<?php 
    $sql ="select cod_estado_cotizacion , sum( valor * trm ) valor_pesos
    from cotizacion c
    left join trm t on t.cod_moneda = c.cod_moneda 
    and date_format( c.fecha_creacion , '%Y' )  = t.anio
    group by cod_estado_cotizacion ";
    
    $sql = "select id_estado_cotizacion , estado_cotizacion, valor_pesos , color , conteo_cotizacion
        from estado_cotizacion ec
        left join ( select cod_estado_cotizacion,
            sum( valor * trm ) valor_pesos , count(1) conteo_cotizacion
            from cotizacion c
            left join trm t on t.cod_moneda = c.cod_moneda
            and date_format( c.fecha_creacion , '%Y' )  = t.anio
            where c.estado = 1 and c.cod_propuesta is null
            ".(($_REQUEST["search"]!="")?" and ( cotizacion like '%".$_REQUEST["search"]."%'  or date_format( c.fecha_creacion , '%Y-%m-%d' ) like  '%".$_REQUEST["search"]."%') ":"")."  
            group by cod_estado_cotizacion
            ) c on ec.id_estado_cotizacion= c.cod_estado_cotizacion
        where ec.visible = 1
        order by ec.orden
    ";
    $estados = $db->selectObjectsBySql($sql);
    
?>
<div class="p-3">
<div class="h3" >Leads</div>

    <div class="container22">
        <div class="kanban-board">
            <?php 
                $sql = "";
                foreach( $estados as $estado ){                    
                    echo '<div class="kanban-block" >';
                        echo '<div class="kanban-block-title" style="background-color:#'.$estado->color.'">'.$estado->estado_cotizacion.'</div>';
                        echo '<div class="kanban-block-money" id="total_estado_'.$estado->id_estado_cotizacion.'" name="total_estado_'.$estado->id_estado_cotizacion.'" style="color:#'.$estado->color.'">$ '.number_format( $estado->valor_pesos  , 1 ,',','.') .'</div>';
                        
                        echo '<div class="kanban-block-body" style="border:1px solid #'.$estado->color.';background-color:#'.$estado->color.'15; '.(($estado->conteo_cotizacion>3)?"padding-bottom:25px":"").'" id="estado_'.$estado->id_estado_cotizacion.'"  name="estado_'.$estado->id_estado_cotizacion.'" ondrop="drop(event,this.id)" ondragover="allowDrop(event)">';
                        $cotizaciones  = new stdClass();
                        $db->sql("SET lc_time_names = 'es_ES'");
                        $sql ="
                            SELECT
                            	id_cotizacion
                                ,cotizacion
                                ,estado_cotizacion cod_estado_cotizacion
                                ,estado_cotizacion
                                ,cliente cod_cliente
                                ,responsable
                                ,valor
                                ,m.abreviatura moneda
                                ,( valor * trm ) valor_pesos
                                ,date_format( c.fecha_creacion , '%b %d / %Y' ) fecha_cotizacion
                            FROM cotizacion c
                            inner join estado_cotizacion ec
                            on ec.id_estado_cotizacion = c.cod_estado_cotizacion
                            left join moneda m
                            on m.id_moneda = c.cod_moneda
                            left join cliente cl
                            on cl.id_cliente = c.cod_cliente
                            left join trm t on t.cod_moneda = c.cod_moneda 
                            and date_format( c.fecha_creacion , '%Y' )  = t.anio
                            where c.estado = 1 and cod_estado_cotizacion = ".$estado->id_estado_cotizacion."
                            and c.cod_propuesta is null
                            ".(($_REQUEST["search"]!="")?" and ( cotizacion like '%".$_REQUEST["search"]."%'  or date_format( c.fecha_creacion , '%Y-%m-%d' ) like  '%".$_REQUEST["search"]."%') ":"")."
                            order by c.cotizacion , c.fecha_creacion
                                       ";
                        $cotizaciones = $db->selectObjectsBySql($sql) ;
                        $contador = 0;
                        foreach( $cotizaciones as $cotizacion ){                        
                            echo '<div class="task" id="task_'.$cotizacion->id_cotizacion.'" name="task_'.$cotizacion->id_cotizacion.'" draggable="true" ondragstart="drag(event)">';                            
                            echo '<span style="font-weight:bold;">'.$cotizacion->cotizacion.'</span><br />';
                            echo '<span>Fecha: '.$cotizacion->fecha_cotizacion.'</span><br />';
                            echo '<span>Valor: '.$cotizacion->moneda.' $'.number_format( $cotizacion->valor , 1 ,',','.').'</span><br />';
                            if( $cotizacion->moneda != "COP" ){
                                echo '<span>Valor Pesos: $'.number_format( $cotizacion->valor_pesos , 1 ,',','.').'</span><br />';
                            }
                            echo '<a href="index.php?view='.$_view.'&action=agregar&id='.$cotizacion->id_cotizacion.'" class=""><img src="'.IMG_EDIT.'" style="width:16px" title="Editar" /></a>';
                            echo '<img title="Convertir a Deal"  style="width:24px;cursor:pointer;" src="'.IMG_CONVERT.'" onclick="xajax_copiarLeadToDeal('.$cotizacion->id_cotizacion.')" />';
                            echo "<a href='#' onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminar','\'cotizacion\'','\'".$cotizacion->id_cotizacion."\'');\"><img src='".IMG_DELETE."' style='width:16px'  title='Eliminar' /></a>";
                            echo '</div>';
                        }
                        echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>
    <form name="form2" id="form2" method="post" onsubmit="return false;">
        <!-- Modal -->
        <div class="modal fade" id="ModalConvert" tabindex="-1"  data-backdrop="static" data-keyboard="true" role="dialog" aria-labelledby="ModalConvert" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Lead to Deal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php
                    //$proyecto_hito =  $db->selectObjects("proyecto_hito","estado = 1");
                ?>        
                	<div class="form-row">	
                		<div class="form-group col-md-6">
                    		<label for="ruta_documento_pago">Estado Deal</label>
                    		<select class="custom-select" id="cod_estado_propuesta" name="cod_estado_propuesta">
                			<option value='-' selected>Seleccione un Estado</option>
                        <?php
                            $estados = $db->selectObjects("estado_propuesta","estado = 1");
                            foreach( $estados as $estado ){
                                echo "<option ".(( $estado->id_estado_propuesta==  $objeto->cod_estado_propuesta)?"selected":"")." value='".$estado->id_estado_propuesta."'>".$estado->estado_propuesta."</option>";
                            }
                          	?>
                		</select>
        				</div>
        			</div>
              </div>
              <div class="modal-footer">
              	<input type="hidden" id="id_cotizacion" name="id_cotizacion" value="">      	      
                <button id="convert" name='convert' type="button" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>
	</form>
</div>    

<script>
    $( "#convert" ).click(function() {
      	xajax_copyLeadToDeal( xajax.getFormValues( this.form , true ) );
    });
</script>    
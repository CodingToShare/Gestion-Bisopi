<?php
	# definimos los valores iniciales para nuestro calendario
	$month=(( !isset( $_REQUEST["m"]) || $_REQUEST["m"] == "" )?date("n"):$_REQUEST["m"]);
	$year=(( !isset( $_REQUEST["y"]) || $_REQUEST["y"] == "" )?date("Y"):$_REQUEST["y"]);
	if( ($month-1) > 0 ){
		$prev_month= $month -1;
		$prev_year = $year;
	}else{
		$prev_month= 12;
		$prev_year = $year-1;
	}
	if( ($month+1) < 13 ){
		$post_month=$month+1;
		$post_year = $year;
	}else{
		$post_month=1;
		$post_year = $year+1;
	}
	
	
	$dtoday = date("j");
	$mtoday = date("n");
	$ytoday = date("Y");
	# Obtenemos el dia de la semana del primer dia
	# Devuelve 0 para domingo, 6 para sabado
	$diaSemana=date("w",mktime(0,0,0,$month,1,$year));//+7;
	if( $diaSemana == 0 )
		$diaSemana = 7;
	# Obtenemos el ultimo dia del mes
	$ultimoDiaMes=date("d",(mktime(0,0,0,$month+1,1,$year)-1));
	
	
	
	$sql = "select
				DATE_FORMAT( festivo ,'%d') dia				
			from festivo
			where DATE_FORMAT( festivo ,'%Y' ) = ".$year."
			and DATE_FORMAT( festivo,'%m' ) = ".$month."
			";
	$festivos = $db->selectObjectsBySql($sql);
	$a_festivos = Array();
	foreach( $festivos as $festivo ){
	    $a_festivos[]= $festivo->dia;
	}
	//echo $sql;
	$db->sql("SET lc_time_names = 'es_ES'");
	$sql = "select
                id_cierre
				,concat( 'Cierre Semana<br/>' , DATE_FORMAT( semana_inicio,'%b-%d' ) , ' - ', DATE_FORMAT( semana_fin,'%b-%d' )  ) cierre
                ,fecha_cierre
				,DAY( fecha_cierre) dia				
			from cierre
			where YEAR( fecha_cierre ) = ".$year."
			and MONTH( fecha_cierre ) = ".$month."
			";
	//echo $sql;
	$citas = $db->selectObjectsBySql($sql);
	//print_r( $citas );
	$a_citas = Array();
	foreach( $citas as $cita ){	
	    $a_citas[ $cita->dia ]->cierre = $cita->cierre;
	    $a_citas[ $cita->dia ]->id_cierre = $cita->id_cierre;
	    $a_citas[ $cita->dia ]->fecha_cierre = $cita->fecha_cierre;
		$a_citas[ $cita->dia ]->confirmadas = $cita->confirmadas;
	}

	//echo "Dia semana ".$diaSemana ." Ultimo dia mes ".$ultimoDiaMes;
	$meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
	"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");	
	$enlace = "index.php?view=cierre&action=calendar";
	$enlacedia = "index.php?modulo=".$_REQUEST["modulo"]."&accion=day_calendar";
?>
<div class="p-3">
<div class="h3" >Calendario Cierres</div>	
<div class="table-responsive-sm">
    <table class="table table-sm" >
    	<tr style="text-align:center;" class="table-primary">
    		<th scope="col" colspan="1"><a href='<?php echo $enlace."&m=".$prev_month."&y=".$prev_year;?>'><?php echo "<< ".$meses[$prev_month];?> </a></th>		
    		<th scope="col" colspan="5"><?php echo $meses[$month]." ".$year?></th>	
    		<th scope="col" colspan="1"><a href='<?php echo $enlace."&m=".$post_month."&y=".$post_year;?>'> <?php echo $meses[$post_month]." >>";?></a></th>
    		<!-- <th style="width:100px"><a href='<?php echo $enlace."&m=".$mtoday."&y=".$ytoday;?>'> Mes actual </a></th> -->
    	</tr>
    	<tr style="text-align:center;" class="table-primary">
    		<th style="width:14%;" >Lunes</th>
    		<th  style="width:14%;">Martes</th>
    		<th  style="width:14%;">Mi&eacute;rcoles</th><th>Jueves</th>
    		<th  style="width:14%;">Viernes</th><th>Sabado</th><th>Domingo</th>
    	</tr>
    	<tr>
    	<?php
    		$last_cell=$diaSemana+$ultimoDiaMes;
    		// hacemos un bucle hasta 42, que es el máximo de valores que puede
    		// haber... 6 columnas de 7 dias
    		for($i=1;$i<=42;$i++){
    			if($i==$diaSemana){
    				// determinamos en que dia empieza
    				$day=1;
    			}
    			if($i<$diaSemana || $i>=$last_cell){ // celda vacia
    				echo "<td>&nbsp;</td>";
    			}else{
    			    echo "<td class='".(( $day == $dtoday && $month == $mtoday && $year == $ytoday )?"table-warning":(( array_search( $day , $a_festivos ) !== false )?"table-active":""))."'>";
    			    echo "<span>";
    			    //echo "<a href='".$enlacedia."&d=".$day."&m=".$month."&y=".$year."'>";
    			    echo $day;
    			    //echo "</a>";
    			    echo "</span>";
    				echo "<div class='clear'></div>";
    				if( $a_citas[ $day ] != ""){
    					echo "<div class='badge badge-primary' style='float:right;' onclick='xajax_cambiarFechaCierre(\"".$a_citas[ $day ]->id_cierre."\" )'>".$a_citas[ $day ]->cierre."</div>";
    				}
    				echo "</td>";
    				$day++;
    			}
    			// cuando llega al final de la semana, iniciamos una columna nueva
    			if($i%7==0){				
    		      if( $ultimoDiaMes >=  $day ){
    				echo "</tr><tr>";
    		      }else{
    		          break;
    		      }
    			}
    		}
    	?>
    	</tr>
    </table>
    	<div style="margin: 0 auto; width:200px;">
    		<table class="table table-sm"><tr><td style="width: 50%;text-align:center;" class="table-active">Festivo</td><td style="width: 50%;text-align:center;" class="table-warning">Hoy</td></tr></table>
    	</div>
</div>
<form name="form2" id="form2" method="post" onsubmit="return false;">
        <!-- Modal -->
        <div class="modal fade" id="ModalConvert" tabindex="-1"  data-backdrop="static" data-keyboard="true" role="dialog" aria-labelledby="ModalConvert" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cambiar Fecha Cierre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
              	<div class="form-row">	
                	<div class="form-group col-md-6">
                    	<label for="fecha">Semana</label>
                    	<span id="semana_cierre"></span>
					</div>
				</div>
              	<div class="form-row">	
                	<div class="form-group col-md-6">
                    	<label for="fecha_cierre">Fecha</label>
                    	<input type="text" autocomplete="off"  placeholder="YYYY-MM-DD" class="form-control" id="fecha_cierre" name="fecha_cierre" value="<?php echo $object->fecha;?>">
					</div>
				</div>
              </div>
              <div class="modal-footer">
              	<input type="hidden" id="id_cierre" name="id_cierre" value="">      	      
                <button id="save" name='save' type="button" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-dark" data-dismiss="modal">Cancelar</button>
              </div>
            </div>
          </div>
        </div>
	</form>
</div>
<script>

    $( function() {
    	$( "#fecha_cierre" ).datetimepicker({
    		dateFormat: "yy-mm-dd",
    		monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],    		
    		firstDay: 1,
    		dayNamesMin: ["Do","Lu","Ma","Mi","Ju","Vi","Sa"]
    	});
    } );
    $( "#save" ).click(function() {
      	xajax_guardarCambiarCierre( xajax.getFormValues( this.form , true ) );
    });
</script>
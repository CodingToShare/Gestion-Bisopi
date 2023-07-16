<div class="h3" >Notificación Recursos</div>
<?php     
    $mesant = $_REQUEST["fecha_inicio"];
    $mespos = $_REQUEST["fecha_fin"];
    $semana_filtro = $_REQUEST["semana"];
    /*
    $datenow2 = date('Y-m-d');// date now
    
    $datenow = date('Y-m-d',  strtotime( $mesant ) );// date now
    $mon = new DateTime($datenow);
    $fri = new DateTime($datenow);
    $mon->modify('Monday this week');
    $fri->modify('Friday this week');
    
    echo 'Monday: '.$mon->format('d')." ".$meses[ $mon->format('n')] ." ".$mon->format('Y') .'<br>';
    echo 'Friday: '.$fri->format('Y-m-d').'<br>';
    echo 'Semana: '.$mon->format('d')." ".$meses[ $mon->format('n')] .' al '.$fri->format('d')." ".$meses[ $fri->format('n')] ." del ".$fri->format('Y').'<br>';
    */
    $semana_act =  new DateTime(date('Y-m-d'));
    $semana_ant =  new DateTime(date('Y-m-d'));
    $semana_pro =  new DateTime(date('Y-m-d'));
    $semana_act_fin =  new DateTime(date('Y-m-d'));
    $semana_ant_fin =  new DateTime(date('Y-m-d'));
    $semana_pro_fin =  new DateTime(date('Y-m-d'));
    $semana_act->modify("this week");
    $semana_ant->modify("last week");
    $semana_pro->modify("next week");
    
    
    
    $semana_act_fin->modify("Friday this week");
    $semana_ant_fin->modify("Friday last week");
    $semana_pro_fin->modify("Friday next week");
    
    
    $semanas = array(
        $semana_ant->format('Y-m-d') =>  'Semana: '.$semana_ant->format('d')." ".$meses[ $semana_ant->format('n')] .' al '.$semana_ant_fin->format('d')." ".$meses[ $semana_ant_fin->format('n')] ." del ".$semana_ant_fin->format('Y'),
        $semana_act->format('Y-m-d') =>  'Semana: '.$semana_act->format('d')." ".$meses[ $semana_act->format('n')] .' al '.$semana_act_fin->format('d')." ".$meses[ $semana_act_fin->format('n')] ." del ".$semana_act_fin->format('Y'),
        $semana_pro->format('Y-m-d') =>  'Semana: '.$semana_pro->format('d')." ".$meses[ $semana_pro->format('n')] .' al '.$semana_pro_fin->format('d')." ".$meses[ $semana_pro_fin->format('n')] ." del ".$semana_pro_fin->format('Y')        
    );
    /*echo "Semana anterior:".$semana_ant->format('Y-m-d')." ".$semana_ant_fin->format('Y-m-d')."<br/>";
    echo "Semana actual:".$semana_act->format('Y-m-d')." ".$semana_act_fin->format('Y-m-d')."<br/>";
    echo "Semana siguiente:".$semana_pro->format('Y-m-d')." ".$semana_pro_fin->format('Y-m-d')."<br/>";
    
    echo $semana_filtro;*/
    
    $datenow = date('Y-m-d',  strtotime( $semana_filtro ) );    
    $fri = new DateTime($datenow);    
    $fri->modify('Friday this week');    
    $mesant = $semana_filtro;
    $mespos = $fri->format('Y-m-d');
?>

<form name="form1" id="form1" method="post"  action="index.php?view=notificacion">
    <input type="hidden" name="view" id="view" value="notificacion" />
    <div class="form-row">
        <div class="form-group col-md-4">
        <input  autocomplete="off" type="hidden" placeholder="YYYY-MM-DD" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo $mesant;?>">
        <input  autocomplete="off" type="hidden" placeholder="YYYY-MM-DD" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo $mespos;?>">
          <label for="fecha_fin">Semana</label>          
          <select class="custom-select" id="semana" name="semana">
    			<option value='-' selected>Seleccione un semana</option>
            <?php
                foreach( $semanas as $semana => $val ){
                    echo "<option ".(( $semana == $semana_filtro )?"selected":"")." value='".$semana."'>".$val."</option>";
                }
              	?>
    		</select>
        </div>
        <div class="form-group col-md-1">    	
        	<button id="save" name='save' type="submit" class="btn btn-success mt-4">Filtrar</button>
        </div>
    </div>    

<?php
    global $db;
    $today = date("Ymd");
    $array_estados = $_REQUEST["cod_estado"];
    /*
    if( count( $array_estados ) == 0 ){
        if( $_SESSION["SES_SEG_ESTADOS"] == "" ){
        $array_estados = $db->selectArraysBySql("select id_estado_proyecto from estado_proyecto where abreviatura = 'C'");
        $array_estados = $array_estados[0];
        }else{
            $array_estados =$_SESSION["SES_SEG_ESTADOS"];
        }
    }*/
    $filtro_estado = implode( "," , $array_estados );
    //$mesant = (($_REQUEST["fecha_inicio"]=="" )? ( ( $_SESSION["SES_SEG_FECHA_INI"]=="" )?date("Y-m-d",strtotime($today."- 3 days")):$_SESSION["SES_SEG_FECHA_INI"]) :$_REQUEST["fecha_inicio"]);
    //$mespos = (($_REQUEST["fecha_fin"]=="" )? ( ( $_SESSION["SES_SEG_FECHA_FIN"]=="" )?date("Y-m-d",strtotime($today."+ 2 weeks")): $_SESSION["SES_SEG_FECHA_FIN"]) :$_REQUEST["fecha_fin"]);

    if( $semana_filtro == "-" ){
        echo "";
    }else{
        $filtro_pais = (($_REQUEST["cod_pais"]=="" )? ( ( $_SESSION["SES_SEG_PAIS"]=="" )?"": $_SESSION["SES_SEG_PAIS"]) :(($_REQUEST["cod_pais"]=="-")?"":$_REQUEST["cod_pais"]));
        $filtro_proyecto = (($_REQUEST["cod_proyecto"]=="" )? ( ( $_SESSION["SES_SEG_PROYECTO"]=="" )?"": $_SESSION["SES_SEG_PROYECTO"]) :(($_REQUEST["cod_proyecto"]=="-")?"":$_REQUEST["cod_proyecto"]));
        $_SESSION["SES_SEG_FECHA_INI"] = $mesant;
        $_SESSION["SES_SEG_FECHA_FIN"] = $mespos;
        $_SESSION["SES_SEG_ESTADOS"] = $array_estados;
        $_SESSION["SES_SEG_PAIS"] = $filtro_pais;
        $_SESSION["SES_SEG_PROYECTO"] = $filtro_proyecto;
    
    /*
    echo date('Ymd w W');
    echo (7-date('w'));
    echo "<hr>";
    $semana_actual = 1;
    echo 1%52;
    */
    /*
    $fecha = new DateTime( date("Y-m-d")  );
    $fecha->add( new DateInterval('P1W'));
    echo $fecha->format('Y-m-d H:i:s');
    */
    $sql = "select
                    	id_programacion
                    	,p.cod_proyecto
                        ,proyecto
                    	,p.cod_recurso
                        ,concat( p.cod_proyecto ,'_', p.cod_recurso) llave
                    	,r.recurso
                    	,r.correo
                        ,g.recurso gerente
                    	,date_format( p.fecha , '%Y%m%d') fecha
                    	,p.asignacion asignacion
                    from programacion p
                    inner join recurso r
                    on r.id_recurso = p.cod_recurso
                    inner join proyecto pr
                    on pr.id_proyecto = p.cod_proyecto
                    left join recurso g
                    on g.id_recurso = pr.cod_gerente
                    inner join estado_proyecto ep
                    on ep.id_estado_proyecto = pr.cod_estado_proyecto
                    inner join pais pa
                    on pa.id_pais = pr.cod_pais
                    where p.estado = 1                 
                    and date_format( p.fecha , '%Y-%m-%d') between '".$mesant."' and '".$mespos."'
                    order by recurso, p.fecha, pr.proyecto";
    $programaciones = $db->selectObjectsBySql($sql);
    
    $array_programacion = array();
    $array_fechas = array();
    $array_recursos = array();
    foreach( $programaciones as $programacion ){    
        $array_programacion[$programacion->cod_recurso][ $programacion->proyecto][ $programacion->fecha ]+= $programacion->asignacion;
        $array_programacion[$programacion->cod_recurso][ $programacion->proyecto]["gerente"] = $programacion->gerente;
        $array_fechas[]=$programacion->fecha;
        $array_recursos[$programacion->cod_recurso]->codigo = $programacion->cod_recurso;
        $array_recursos[$programacion->cod_recurso]->nombre = $programacion->recurso;
        $array_recursos[$programacion->cod_recurso]->correo = $programacion->correo;
    }
    $array_fechas = array_unique($array_fechas);
    //echo "<pre>".print_r( $array_recursos )."</pre>";
    
    
      $formato_msj = "Buen día @recurso@,<br/><br/>
    Tu asignación para la próxima semana es la siguiente:<br/><br/>
    @tabla_asignacion@<br/>
    Cualquier inquietud comunicarse con su gerente de proyecto o en caso de no tener con el gerente de operaciones.<br/><br/>
    <img title='SIGO Bision' src='cid:img-logo' />
    ";
    
    $cabecera_tabla = "<tr><th>Proyecto</th>";
    foreach( $array_fechas as $fecha ){
        $m = date("n", strtotime( $fecha ) );
        $d = date("d", strtotime( $fecha ) );
        $cabecera_tabla.="<th style='width:80px'>".$meses_abr[$m]." ".$d."</th>";
    }
    $cabecera_tabla.="</tr>";
    $salida_recurso = "";
    $cuerpo_tabla ="";
    /*
    foreach( $array_programacion as $recurso => $proyectos ){
        $cuerpo_tabla = "<table border='1' style='border:1px solid #000 border-collapse:collapse;' cellpadding='3'>";        
        $cuerpo_tabla.=$cabecera_tabla; 
        foreach( $proyectos as $proyecto => $fechas ){
            $cuerpo_tabla.="<tr><td>".$proyecto." (".$fechas["gerente"]." )</td>" ;
            
            foreach( $array_fechas as $fecha ){
                $cuerpo_tabla.="<td style='text-align:center;'>".(( $fechas[$fecha] != "" )?$fechas[$fecha]."%":"")."</td>" ;                
            }
            $cuerpo_tabla.="</tr>";
        }
        $cuerpo_tabla.= "</table>";
        //$search = array( "@recurso@" , "@tabla_asignacion@" );
        //$replace = array( $recurso , $cuerpo_tabla );
        //$salida_recurso = str_replace(  $search , $replace , $formato_msj );
        
        $salida.="<tr><th class='p-1' scope='row'>".($contador++)."</th><td>".$recurso."</td>";
        $salida.="<td><input type='checkbox' name='enviar[]' id='enviar' value='' /></td></tr>";
        //echo $salida_recurso;
    }*/
    
    
    $salida = '<table class="table table-striped table-hover col-12">
                  <thead class="thead-dark">
                    <tr>
                        <th class="p-1" style="width:32px"scope="col"></th>
                        <th class="p-1" style="width:32px" scope="col"></th>
                        <th class="p-1" scope="col">Recurso</th>
                        <th class="p-1" style="width:32px" scope="col"></th>
                        <th class="p-1" style="width:900px" scope="col"></th>
                    </tr>
                    </thead>
                    <tbody id="tb_search" class="">
                ';
    $contador=1;
    $total = array();
    foreach( $array_recursos as $cod_recurso ){
        unset( $total );
        $salida.="<tr><th class='p-1' scope='row'>".($contador++)."</th>";
        $salida.="<td><input type='checkbox' name='recurso_enviar[]' id='recurso_enviar_".$cod_recurso->codigo."' value='".$cod_recurso->codigo."' /></td>";
        $salida.="<td><label for='recurso_enviar_".$cod_recurso->codigo."'>".$cod_recurso->nombre."</label></td><td id='td_recurso_".$cod_recurso->codigo."'></td>";
        $cuerpo_tabla = "<table border='1' width='100%' style='border:1px solid #000 border-collapse:collapse;' cellpadding='3'>";
        $cuerpo_tabla.=$cabecera_tabla;
        $proyectos = $array_programacion[ $cod_recurso->codigo ];
        
        foreach( $proyectos as $proyecto => $fechas ){
            $cuerpo_tabla.="<tr><td>".$proyecto." (".$fechas["gerente"]." )</td>" ;            
            foreach( $array_fechas as $fecha ){
                $cuerpo_tabla.="<td style='text-align:center;'>".(( $fechas[$fecha] != "" )?$fechas[$fecha]."%":"")."</td>" ;
                $total[$fecha]+=$fechas[$fecha];
            }
            $cuerpo_tabla.="</tr>";
        }
        $cuerpo_tabla.="<tr class='thead-dark'><th>Total</th>" ;
        foreach( $array_fechas as $fecha ){
            $cuerpo_tabla.="<th style='text-align:center;'>".(( $total[$fecha] != "" )?$total[$fecha]."%":"0%")."</th>" ;            
        }
        $cuerpo_tabla.="</tr>";
        $cuerpo_tabla.= "</table>";
        $salida.="<td>".$cuerpo_tabla."</td>";
        $salida.="</tr>";        
    }
    $salida.="</tbody></table>";
    echo '<div><h6>Los siguientes recursos tienen progración para la semana seleccionada.</h6></div>';
    echo $salida;
?>    
<div id="divmensajetest" name="divmensajetest"></div>
        <div class="form-group col-md-1">    	
        	<button id="enviar" name='enviar' type="button" class="btn btn-success mt-4">Enviar</button>
        </div>
<?php } ?>
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
    $( "#enviar" ).click(function() {
      	xajax_enviarNotificacionPlanificacion( xajax.getFormValues( document.forms['form1'] , true ) , $( "#semana option:selected" ).text()  );
    });
    
    
</script>

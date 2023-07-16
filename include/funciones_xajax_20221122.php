<?php
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    function test( $message ){
        $resp = new xajaxResponse();
        $resp->alert( $message );
        return $resp;
    }
    $xajax->registerFunction("test");
    
    function iniciarSesion( $form ){
        $resp = new xajaxResponse();
        //$resp->alert( print_r( $form , true ) );
        
        if( DEBUG ){ $resp->alert( print_r( $form , true ) );}
        
        global $usuario;
        $inicio = $usuario->iniciarSesion( $form["inputEmail"] , $form["inputPassword"] );
        
        // $resp->alert( $inicio );
        if( !$inicio ){
            $message = $usuario->getMensaje();
            $resp->script("crearDialogo('Advertencia','".$message."');");
            return $resp;
        }
        
        //$resp->assign( "mensaje" , "innerHTML" , $usuario->getMensaje() );
        $resp->redirect( "index.php?".$_SERVER["QUERY_STRING"] , 1 );
        return $resp;
    }
    $xajax->registerFunction("iniciarSesion");
    
    function cerrarSesion(){
        $resp = new xajaxResponse();
        global $usuario;        
        $usuario->salir();
        $resp->redirect( "index.php" );
        return $resp;
    }
    $xajax->registerFunction("cerrarSesion");
        
    function guardarPerfil( $form ){
        $resp = new xajaxResponse();        
        
        if( $form[ "recurso" ] == "" )
            $message.="<li>Nombre</li>";
            
        if( $form[ "correo" ] == "" )
            $message.="<li>Correo</li>";
        $object = new stdClass();
        $object->recurso = $form["recurso"];
        $object->correo = $form["correo"];
        $object->telefono = $form["telefono"];
        $object->cod_ciudad = $form["cod_ciudad"];
        if( $form["foto_new"] != ""){
            $new = DIR_PATH_PERFILES.$form["id_recurso"].".".retornaExt($form["foto_new"]);
            //$resp->alert("Actualiza Foto");
            if( @rename( $form["foto_new"] , $new ) ){
                $object->foto = $new;                
            }
        }
        $id = $form["id_recurso"];
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios:<ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        global $db;
        if( !$db->updateObject( $object , "recurso" , " id_recurso = '".$id."'" ) ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;
        }
        $message = "Se guardo correctamente";
        if( $form["foto_new"] != ""){
            unlink( $form["foto_new"] );
            if( retornaExt( $form["foto_new"] ) != retornaExt( $form["foto"] ) ){
                unlink( $form["foto"] );
            }
        }
        $sURL = "index.php?";
        //$sURL = (( $form["back"]!= "")? $form["back"]:$sURL);
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
    }
    $xajax->registerFunction("guardarPerfil");
    
    function cambiarContrasena( $form ){
        $resp = new xajaxResponse();        
        global $db;
        global $user;
        
        if( $form[ "pass" ] == "" )
            $message.="<li>Contraseña actual</li>";
            
        if( $form[ "npass" ] == "" )
            $message.="<li>Contraseña nueva</li>";
        
        if( $form[ "cpass" ] == "" )
            $message.="<li>Confirmar Contraseña</li>";
        if( strlen( $form[ "npass" ]) <6 )
                $message.="<li>La nueva contraseña deber ser mayor de 6 caracteres</li>";
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios:<ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }        
        if( $form[ "npass" ] != $form[ "cpass" ] )
            $message.="<li>La contraseña nueva no coincide con la confirmación</li>";
        $id = $form["id_recurso"];
        $bdpass = $db->selectValue("recurso", "password","id_recurso = ".$user->id_recurso);
        //$resp->alert( $bdpass ." - ".md5( $form[ "pass" ]) );
        if( md5( $form[ "pass" ]) != $bdpass ){
            $message.="<li>La contraseña actual no coincide</li>";
        }
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios:<ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        $object = new stdClass();
        $object->password = md5( $form["npass"] );
                
        if( !$db->updateObject( $object , "recurso" , " id_recurso = '".$id."'" ) ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?";
        $sURL = (( $form["back"]!= "")? $form["back"]:$sURL);
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
    }
    $xajax->registerFunction("cambiarContrasena");
    
    function guardar( $form , $table , $fields ){
        $resp = new xajaxResponse();        
        global $db;
        global $user;
        //$resp->alert( print_r(  $form , true )) ; return $resp;
        $object = new stdClass();
        $c_id = "id_".$table ;
        $message = "";
        $valor = "";
        
        foreach( $fields as $field ){
            if( $field["disabled"] == 1 ){
                continue;
            }else{
            $val = ( ( $field["type"] == "combo" )?(( $field["default"]!="")?$field["default"]:"-"):"");            
            if( $field["required"] == 1 ){
                if( $form[ $field["id"] ] == $val )
                    $message.="<li>".$field["name"]."</li>";
            }
            $valor = $field["id"];
            if( $field["type"] == "password" && $form[$c_id] == "" ){
                $object->$valor= EMP_DEFAULT_PW;
            }elseif( $field["type"] != "password" ){
                $object->$valor = ( (  ($field["type"] == "combo" || $field["type"] == "date" || $field["type"] == "int" || $field["type"] == "check") && $form[ $field["id"] ] == $val )?"NULL":$form[ $field["id"] ] );
            }
            }
        }
        
        /*$resp->alert( print_r( $object , true ) );
        return $resp;*/
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios:<ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");            
            return $resp;
        }
        
        
        
        
        if( $form[$c_id] != "" ){
            $message = "actualiza";
            if( !$db->updateObject( $object , $table , $c_id." = '".$form[ $c_id ]."'" ) ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
            
        }else{
            $message = "Inserta";            
            $object->cod_usuario = $user->id_recurso;
            $object->$c_id = $db->insertObject( $object , $table );
            if( $object->$c_id== -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }
        
        
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"];
        $sURL = (( $form["back"]!= "")? $form["back"]:$sURL);        
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
    }
    $xajax->registerFunction("guardar");

    
    function eliminar( $table , $id ){
        $resp = new xajaxResponse();
        global $db;
        if( LOGIC_DELETE ){
            $item = new stdClass();
            $item->estado = 0;
            if( !$db->updateObject( $item, $table , "id_".$table." = '".$id."'") ){
                $message = ">>Error<< ".$db->error();
                $resp->call( "xajax_generarAlerta" , 'danger' , $message );
                return $resp;
            }
        }else{
            if( !$db->delete( $table , "id_".$table." ='".$id."'") ){
                $message = ">>Error<< ".$db->error();
                $resp->call( "xajax_generarAlerta" , 'danger' , $message );
                return $resp;
            }
        }
        $message = "Se elimino correctamente";
        $sURL = "index.php?".$_SERVER['QUERY_STRING'];
        
        $resp->script("crearDialogo('Respuesta','".$message."');");
        //$resp->call( "xajax_generarAlerta" , 'success' , $message );
        $resp->redirect( $sURL ,1 );
        return $resp;
    }
    $xajax->registerFunction("eliminar");
    
    
    function eliminarFuncion( $table , $id , $function , $id_function ){
        $resp = new xajaxResponse();
        global $db;
        if( LOGIC_DELETE ){
            $item = new stdClass();
            $item->estado = 0;
            if( !$db->updateObject( $item, $table , "id_".$table." = '".$id."'") ){
                $message = ">>Error<< ".$db->error();
                $resp->call( "xajax_generarAlerta" , 'danger' , $message );
                return $resp;
            }
        }else{
            if( !$db->delete( $table , "id_".$table." ='".$id."'") ){
                $message = ">>Error<< ".$db->error();
                $resp->call( "xajax_generarAlerta" , 'danger' , $message );
                return $resp;
            }
        }
        $resp->call( "xajax_".$function , $id_function );
        return $resp;
    }
    $xajax->registerFunction("eliminarFuncion");
    
    function buscar( $form ){
        $resp = new xajaxResponse();
        global $user;
        if( !$user ){ $resp->script("crearDialogo('Respuesta','".MSG_EXPIRED."','window.location.href=\'index.php\'');"); return $resp; }
        if( DEBUG ){ $resp->alert( print_r( $form , true ) );}
        
        $texto = tratarVariable( $form["busqueda"] );
        $campos = array(
            'curso'
            ,'taller'
            ,'anio'
        );
        
        if( $texto == "" || strlen( $texto ) < LIMITE_BUSCADOR ){
            return $resp;
        }
        global $db;
        $resultado = $db->selectObject( "busqueda" , "modulo='".$_REQUEST["modulo"]."' and texto ='".$texto."'" );
        if( $resultado->token != "" ){
            $resp->redirect( "index.php?modulo=".$_REQUEST["modulo"]."&token=".$resultado->token );
            return $resp;
        }
        $textos = explode( "+" , $texto );
        $sql = "";
        if( count( $textos ) > 1 ){
            // Limpiar el arreglo
            $textos2 = remove_empties( $textos );
            foreach( $textos2 as $texto ){
                $sql.= " and (";
                foreach( $campos  as $pos => $campo){
                    $sql.= (($pos == 0)?"":" or ").$campo." like '%".$texto."%'";
                }
                $sql.= " ) ";
            }
        }else{
            $sql.= " and (";
            foreach( $campos  as $pos => $campo){
                $sql.= (($pos == 0)?"":" or ").$campo." like '%".$texto."%'";
            }
            $sql.= " ) ";
        }
        global $user;
        $busqueda = new stdClass();
        $busqueda->modulo = $_REQUEST["modulo"];
        $token = substr( md5( uniqid( rand()+time() , true) ) , 0 , 12 );
        $busqueda->token = $token;
        $busqueda->texto = $form["busqueda"];
        $busqueda->sql = $sql;
        $busqueda->id_busqueda = $db->insertObject( $busqueda , "busqueda" );
        if( $busqueda->id_busqueda == -1 ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecuciÃ³n.<br>Por favor intÃ©ntelo mÃ¡s tarde.".( ( DEBUG )? ( "<br><br>".mysqli_errno( $db->connection )." - ".htmlentities( mysqli_error( $db->connection ), ENT_QUOTES ) ) : "" )."','');");
            return $resp;
        }
        $resp->redirect( "index.php?modulo=".$_REQUEST["modulo"]."&token=".$token );
        return $resp;
    }
    $xajax->registerFunction("buscar");
    
    function confirmarEliminar( $table , $id ){
        $resp = new xajaxResponse();
        $resp->confirmCommands(1, "Esta seguro de eliminar?");
        $resp->call("xajax_eliminar", $table, $id );
        return $resp;
    }
    $xajax->registerFunction("confirmarEliminar");
    
    function enviarCorreo( $asunto , $mensaje , $para , $cc = "" , $adjuntos = ""){
        $resp = new xajaxResponse();
        //Import PHPMailer classes into the global namespace
        include 'include/PHPMailer/Exception.php';
        include 'include/PHPMailer/PHPMailer.php';
        include 'include/PHPMailer/SMTP.php';
                
        
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
                
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = SMTP_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = SMTP_USER;                     //SMTP username
            $mail->Password   = SMTP_PASS;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = SMTP_PORT;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
            //Recipients
            $mail->setFrom( SMTP_FROM, SMTP_USERNAME );
            
            if( is_array( $para ) ){
                foreach( $para as $email){
                    $mail->AddAddress( $email );
                }
            }else{
                $mail->AddAddress( $para );
            }
            
            
            if( $cc != "" ){
                if( is_array( $cc ) ){
                    foreach( $cc as $ccs){
                        $mail->AddCC( $ccs );
                    }
                }else{
                    $mail->AddCC( $cc );
                }
            }
            if( SMTP_CCO != "" ){
                $mail->AddBCC( SMTP_CCO );
            }
            
            if( $adjuntos != "" ){
                foreach( $adjuntos as $adjunto ){
                    $mail->AddAttachment( $adjunto["ruta"] , $adjunto["nombre"] );
                }
            }            
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body    = $mensaje;
            $mail->AddEmbeddedImage( IMG_LOGO, "img-logo", "logo.png");
            
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
            $mail->send();
            //$resp->alert( 'Message has been sent' );
        } catch (Exception $e) {
            $resp->alert( "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
        return $resp;
    }
    $xajax->registerFunction("enviarCorreo");
    
    function enviarCorreo2( $asunto , $mensaje , $para , $cc = "" , $adjuntos = ""){
        $resp = new xajaxResponse();
        
        //Incluir la libreria para enviar los correos
        include("include/phpmailer.php");
        
        $mail             = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        if( SMTP_SECURE != ""){
            $mail->SMTPSecure = SMTP_SECURE;                 // sets the prefix to the server
        }
        $mail->Host       = SMTP_HOST;     				// sets the SMTP server
        $mail->Port       = SMTP_PORT;                   // set the SMTP port for the  server
        $mail->Username   = SMTP_USER;  //  username
        $mail->Password   = SMTP_PASS;            //  password
        $mail->AddReplyTo( SMTP_USER ,  SMTP_USERNAME );
        $mail->From       = SMTP_FROM;
        $mail->FromName   = SMTP_USERNAME;
        $mail->Subject    = $asunto;
        
        if( is_array( $para ) ){
            foreach( $para as $email){
                $mail->AddAddress( $email );
            }
        }else{
            $mail->AddAddress( $para );
        }
        if( $cc != "" ){
            $mail->AddCC( $cc );
        }
        if( SMTP_CCO != "" ){
            $mail->AddBCC( SMTP_CCO );
        }
        //return $resp;
        
        if( $adjuntos != "" ){
            foreach( $adjuntos as $adjunto ){
                $mail->AddAttachment( $adjunto["ruta"] , $adjunto["nombre"] );
            }
        }
        // $mail->AddEmbeddedImage( EMP_LOGOFIRMA , "my-attach" );
        $mail->MsgHTML( $mensaje );
        
        $mail->IsHTML(true); // send as HTML
        /*
        $resp->alert( print_r( $mail , true ) );
        return $resp;
        */
        if( !@$mail->Send() ){
            $resp->alert("Ocurrio un error".$mail->GetError() );
            return $resp;
        }
        $resp->alert("Se envio el correo". $mail->GetError() );
        //return true;
        return $resp;
    }
    $xajax->registerFunction("enviarCorreo2");
    
    function guardarProgramacion( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        //$resp->alert( print_r( $form , true ) );
        
        //$resp->alert( print_r( $fields , true ) );
        
                
        $fechaInicio = $form["fecha_inicio"];
        $fechaFin = $form["fecha_fin"];
        $message ="";
        if( $fechaInicio == "" )
            $message.= "<li>Fecha Inicio</li>";
        if( $fechaFin == "" )
            $message.= "<li>Fecha Fin</li>";
        
        if( $form["cod_recurso"] == "-")
            $message.= "<li>Recurso</li>";
            
        if( $form["cod_cargo"] == "-")
            $message.= "<li>Cargo</li>";
        
        if( $form["cod_tipo_actividad"] == "-")
            $message.= "<li>Tipo Actividad</li>";
        if( $form["asignacion"] == "")
            $message.= "<li>Asignación</li>";
            
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        if( $fechaFin < $fechaInicio ){
            $message = "La fecha Fin debe ser mayor a la fecha inicio";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }        
        # Fecha como segundos
        $tiempoInicio = strtotime($fechaInicio);
        $tiempoFin = strtotime($fechaFin);
        # 24 horas * 60 minutos por hora * 60 segundos por minuto
        $dia = 86400;
        $mensaje = "";
        while($tiempoInicio <= $tiempoFin){
            # Podemos recuperar la fecha actual y formatearla
            # Más información: http://php.net/manual/es/function.date.php
            $fechaActual = date("Ymd", $tiempoInicio);
            
            if( !isFeriado( $fechaActual ) ){
                $object = new stdClass();
                unset( $object );
                $object->fecha = date("Y-m-d", $tiempoInicio);
                $object->cod_proyecto = $form["cod_proyecto"];
                $object->cod_tipo_actividad = $form["cod_tipo_actividad"];
                $object->cod_recurso = $form["cod_recurso"];
                $object->asignacion = $form["asignacion"];
                $object->comentario = $form["comentario"];
                $object->cod_cargo = $form["cod_cargo"];
                $object->cod_usuario = $user->id_recurso;
                $object->id_programacion = $db->insertObject( $object , "programacion" );
                if( $object->id_programacion== -1 ){
                    if( $db->error() == "1062" ){
                        $mensaje.="<li>".$object->fecha."</li>";
                    }else{
                        $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                        return $resp;
                    }
                }
            }
            # Sumar el incremento para que en algún momento termine el ciclo
            $tiempoInicio += $dia;
        }
        
        $message ="";
        
        if( $mensaje !="")
            $message ="Los siguientes días se omitieron porque ya tenian programación: <ul>".$mensaje."</ul><br/>";            
        
        $message.= "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=programacion&id=".$form["cod_proyecto"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
    }
    $xajax->registerFunction("guardarProgramacion");
    
    function actualizarProgramacion( $form ){
        $resp = new xajaxResponse();
        global $db;
        
        $fechaInicio = $form["fecha_inicio_u"];
        $fechaFin = $form["fecha_fin_u"];
        $message ="";
        if( $fechaInicio == "" )
            $message.= "<li>Fecha Inicio</li>";
        if( $fechaFin == "" )
            $message.= "<li>Fecha Fin</li>";
            
        if( $form["cod_recurso_u"] == "-")
            $message.= "<li>Recurso</li>";
        
        if( $form["asignacion_u"] == "-")
            $message.= "<li>Asignación</li>";
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        if( $fechaFin < $fechaInicio ){
            $message = "La fecha Fin debe ser mayor a la fecha inicio";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        $where = " cod_proyecto = '".$form["cod_proyecto"]."' and cod_recurso = '".$form["cod_recurso_u"]."' and fecha between '".$fechaInicio."' and '".$fechaFin."' ";
        
        //$resp->alert( $where );
        $obj =  new stdClass();
        $obj->asignacion = $form["asignacion_u"];
        if( !$db->updateObject( $obj , "programacion" , $where ) ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;
        }
        
        $message.= "Se actualizo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=programacion&id=".$form["cod_proyecto"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
    }
    $xajax->registerFunction("actualizarProgramacion");
    
    function eliminarProgramacion( $form ){
        $resp = new xajaxResponse();
        global $db;       
        
        $fechaInicio = $form["fecha_inicio_e"];
        $fechaFin = $form["fecha_fin_e"];
        $message ="";
        if( $fechaInicio == "" )
            $message.= "<li>Fecha Inicio</li>";
        if( $fechaFin == "" )
            $message.= "<li>Fecha Fin</li>";
                
        if( $form["cod_recurso_e"] == "-")
            $message.= "<li>Recurso</li>";
                            
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        if( $fechaFin < $fechaInicio ){
            $message = "La fecha Fin debe ser mayor a la fecha inicio";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }

        $where = " cod_proyecto = '".$form["cod_proyecto"]."' and cod_recurso = '".$form["cod_recurso_e"]."' and fecha between '".$fechaInicio."' and '".$fechaFin."' ";
        
        //$resp->alert( $where );

        if( !$db->delete( "programacion" , $where ) ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;            
        }
        
        $message.= "Se elimino correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=programacion&id=".$form["cod_proyecto"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
    }
    $xajax->registerFunction("eliminarProgramacion");
    
    function actualizaValor( $table ,  $id , $field,  $value , $redirect = 1 ){
        $resp = new xajaxResponse();
        global $db;
        $item = new stdClass();
        $item->$field = $value;
        //$resp->alert( print_r( $item , true ));
        //return $resp;
        if( !$db->updateObject( $item , $table , "id_".$table." = '".$id."'") ){
            $resp->alert( "error bd".$db->error());
            return $resp;
        }
        if( $redirect ){
            $sURL = "index.php?".$_SERVER['QUERY_STRING'];
            $resp->redirect( $sURL );
        }
        return $resp;
    }
    $xajax->registerFunction("actualizaValor");
    
    
    function actualizaValorProgramacion( $table ,  $id , $field,  $value ,$cod_recurso = 0 , $fecha = 0){
        $resp = new xajaxResponse();        
        $resp->call( "xajax_actualizaValor", $table, $id , $field , $value , 0);
        $celda = generaComboProgramacion( $id , $value  ,$cod_recurso , $fecha);
        $resp->assign("td_ass_".$id, "innerHTML", $celda );
        if( $cod_recurso != 0 ){
            $resp->call( "xajax_actualizaTotalProgramacion", $table, $cod_recurso , $fecha );
        }
        return $resp;
    }
    $xajax->registerFunction("actualizaValorProgramacion");
        
    function actualizaTotalProgramacion( $table ,  $cod_recurso , $fecha  ){
        $resp = new xajaxResponse();
        global $db;
        global $colores_recursos;
        $total = $db->selectSum("programacion", "asignacion" , "cod_recurso ='".$cod_recurso."' and fecha ='".$fecha."'" );
        $id=$cod_recurso."_".$fecha;
        $celda = $total."%";
        //$resp->alert( $celda );
        //$celda = $value;
        $resp->assign("td_rec_fec_".$id, "innerHTML", $celda );
        $color = (($total == 100 )?"igual":(($total>100)?"mayor":"menor"));        
        $resp->script('$( "#td_rec_fec_'.$id.'" ).removeClass( );' );
        $resp->script('$( "#td_rec_fec_'.$id.'" ).addClass( "h6 small '.$colores_recursos[$color].'" );' );
        //$resp->script('$( "#td_rec_fec_'.$id.'" ).addClass( "bg-warning"  );' );
        
        return $resp;
    }
    $xajax->registerFunction("actualizaTotalProgramacion");
    
    function guardarHerramientaRecurso( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        if( $form["cod_herramienta"] == "-")
            $message.= "<li>Herramienta</li>";
        if( $form["cod_nivel"] == "-")
            $message.= "<li>Nivel</li>";
                
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        if($db->countObjects("recurso_herramienta", "cod_recurso='".$form["cod_recurso"]."' and cod_herramienta='".$form["cod_herramienta"]."'" )>0)
        {
            $message = "La herramienta seleccionada ya se encuentra en el listado.";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
            
        $object = new stdClass();
        unset( $object );
        $object->cod_recurso = $form["cod_recurso"];
        $object->cod_herramienta = $form["cod_herramienta"];
        $object->cod_nivel = $form["cod_nivel"];
        $object->cod_usuario = $user->id_recurso;
        $object->id_recurso_herramienta = $db->insertObject( $object , "recurso_herramienta" );
        if( $object->id_recurso_herramienta== -1 ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=agregar&id=".$form["cod_recurso"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");        
        return $resp;
        
    }
    $xajax->registerFunction("guardarHerramientaRecurso");
    
    
    function guardarEstudioRecurso( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        if( $form["cod_estudio"] == "-")
            $message.= "<li>Estudio</li>";
            if( $message != "" ){
                $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
                $resp->script("crearDialogo('Advertencia','$message','');");
                return $resp;
            }
            if($db->countObjects("recurso_estudio", "cod_recurso='".$form["cod_recurso"]."' and cod_estudio='".$form["cod_estudio"]."'" )>0)
            {
                $message = "El estudio seleccionada ya se encuentra en el listado.";
                $resp->script("crearDialogo('Advertencia','$message','');");
                return $resp;
            }
            
            $object = new stdClass();
            unset( $object );
            $object->cod_recurso = $form["cod_recurso"];
            $object->cod_estudio = $form["cod_estudio"];
            $object->cod_usuario = $user->id_recurso;
            $object->id_recurso_estudio = $db->insertObject( $object , "recurso_estudio" );
            if( $object->id_recurso_estudio== -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
            $message = "Se guardo correctamente";
            $sURL = "index.php?view=".$_REQUEST["view"]."&action=agregar&id=".$form["cod_recurso"];
            $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
            return $resp;
            
    }
    $xajax->registerFunction("guardarEstudioRecurso");
    
    function guardarCostoRecurso( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        if( $form["fecha_desde"] == ""){
            $message.= "<li>Fecha Inicio</li>";
        }
        if( $form["valor_hora"] == ""){
            $message.= "<li>Valor Hora</li>";
        }
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        //$resp->alert( print_r( $form , true )); 
        
        $max_id = $db->max("recurso_costo", "id_recurso_costo","","cod_recurso='".$form["cod_recurso"]."'");
        if( $max_id ){
            $object2 = new stdClass();            
            $date = new DateTime( $form["fecha_desde"] );            
            $date->modify('-1 day');
            $fecha_hasta = $date->format("Y-m-d");
            /*$resp->alert( $max_id ) ;
            $resp->alert( $fecha_hasta ) ;*/
            $object2->fecha_hasta = $fecha_hasta;
            if( !$db->updateObject( $object2 , "recurso_costo" , "id_recurso_costo=".$max_id ) ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                $resp->alert( $db->ssqqll );
                return $resp;
            }
        }
        $object = new stdClass();
        unset( $object );
        $object->cod_recurso = $form["cod_recurso"];
        $object->fecha_desde = $form["fecha_desde"];
        $object->fecha_hasta = "NULL";
        $object->valor_hora = $form["valor_hora"];
        $object->cod_usuario = $user->id_recurso;
        $object->id_recurso_costo = $db->insertObject( $object , "recurso_costo" );
        if( $object->id_recurso_costo== -1 ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=agregar&id=".$form["cod_recurso"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
            
    }
    $xajax->registerFunction("guardarCostoRecurso");
    
    function guardarHito( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        if( $form["fecha_hito"] == ""){
            $message.= "<li>Fecha Hito</li>";
        }
        if( $form["porcentaje"] == ""){
            $message.= "<li>Porcentaje</li>";
        }
        if( $form["cod_moneda"] == "0"){
            $message.= "<li>Moneda</li>";
        }
        
        if( $form["nro_hito"] == ""){
            $message.= "<li>Nro. Hito</li>";
        }
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        $message = "";
       
        $año = date("Y");
        $trm = $db->selectObject("trm", "estado = 1 and cod_moneda = ".$form["cod_moneda"]." and anio = ".$año);
        
        if( $trm->trm == ""){
            $message.= "<li>No se tiene especificada TRM Proyectada para la moneda y el año actual</li>";
        }
        
        $retencion = $db->selectObject("retencion", "estado = 1 and cod_pais = ".$form["cod_pais"]." and date('".$form["fecha_hito"]."' ) between fecha_inicio and coalesce( fecha_fin , ADDDATE( CURRENT_DATE , INTERVAL 10 YEAR ) ) ");
        
        if( count( $retencion ) == 0){
            $message.= "<li>El país no tiene especificada retención</li>";
        }
        
        if( $message != "" ){
            $message = "<ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        if( $form["porcentaje"] == "")
            $form["porcentaje"] = "NULL";
        
        $object = new stdClass();
        unset( $object );
        
        $object->fecha_hito = $form["fecha_hito"];
        $object->cod_moneda = $form["cod_moneda"];
        $object->valor = $form["valor"];
        $object->porcentaje = $form["porcentaje"];
        $object->retencion = $retencion->retencion;
        $object->nro_hito = $form["nro_hito"];
        $object->mca_control_cambio = (($form["mca_control_cambio"]=="")?0:1);
        
        
        /*if($form["facturado"]=="")
            $object->facturado = 0;
        else
            $object->facturado = 1;
        */
        $object->comentario = $form["comentario"];
        
        
        if( $form["id_proyecto_hito"] != "" ){
            if( !$db->updateObject( $object , "proyecto_hito" ,"id_proyecto_hito='".$form["id_proyecto_hito"]."'" ) ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }else{
            $object->cod_proyecto = $form["cod_proyecto"];            
            $object->porcentaje_iva = PCT_IVA;
            $object->porcentaje_rete_iva = PCT_RETE_IVA;
            $object->trm = $trm->trm;
            $object->cod_estado_hito = 1;
            $object->cod_usuario = $user->id_recurso;
            $object->id_proyecto_hito = $db->insertObject( $object , "proyecto_hito" );
            if( $object->id_proyecto_hito== -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=hito&id=".$form["cod_proyecto"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
                    
    }
    $xajax->registerFunction("guardarHito");
    
    function guardarRecursoPlanificacion( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        // $resp->alert(print_r( $form , true ));
        // return  $resp;
        if( $form["cod_recurso"] == "-"){
            $message.= "<li>Recurso</li>";
        }
        if( $form["cod_cargo"] == "-"){
            $message.= "<li>Cargo</li>";
        }
        if( $form["horas"] == ""){
            $message.= "<li>Horas</li>";
        }
        
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }        
        
        $object = new stdClass();
        $object->cod_proyecto = $form["cod_proyecto"];
        $object->cod_recurso = $form["cod_recurso"];
        $object->cod_cargo = $form["cod_cargo"];
        $object->horas = $form["horas"];
        $object->horas_etc = (($form["horas_etc"]=="")?0:$form["horas_etc"]);
                
        if( $form["id_proyecto_recurso_planificado"] == "" ){
            $object->cod_usuario = $user->id_recurso;
            $object->id_proyecto_recurso_planificado = $db->insertObject( $object , "proyecto_recurso_planificado" );
            if( $object->id_proyecto_recurso_planificado == -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }else{           
            if( !$db->updateObject( $object , "proyecto_recurso_planificado" , "id_proyecto_recurso_planificado='".$form["id_proyecto_recurso_planificado"]."'" ) ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=uso_recurso&id=".$form["cod_proyecto"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
    }
    $xajax->registerFunction("guardarRecursoPlanificacion");
    
    function editarRecursoPlanificados( $id ){
        $resp = new xajaxResponse();
        global $db;
        $item = $db->selectObject("proyecto_recurso_planificado", "id_proyecto_recurso_planificado='".$id."'");
        //$resp->script("$('#fecha_envio_factura').val('".$hito->fecha_envio_factura."');");
        $resp->assign("id_proyecto_recurso_planificado","value", $id);
        $resp->assign("cod_recurso","value", $item->cod_recurso);
        $resp->assign("cod_cargo","value", $item->cod_cargo);
        $resp->assign("horas","value", $item->horas);
        $resp->assign("horas_etc","value", $item->horas_etc);
        $resp->script("$('#ModalPlanificacion').modal('show');");
        return $resp;
    }
    $xajax->registerFunction("editarRecursoPlanificados");
        
    function editarRecursoReal( $id ){
        $resp = new xajaxResponse();
        global $db;
        $item = $db->selectObject("proyecto_recurso_real", "id_proyecto_recurso_real='".$id."'");
        //$resp->script("$('#fecha_envio_factura').val('".$hito->fecha_envio_factura."');");
        $resp->assign("id_proyecto_recurso_real","value", $id);
        $resp->assign("cod_recurso_real","value", $item->cod_recurso);
        $resp->assign("horas_real","value", $item->horas);
        $resp->assign("porcentaje_completado","value", $item->porcentaje_completado);
        $resp->assign("cod_cargo_real","value", $item->cod_cargo);
        $resp->assign("fecha_analisis","value", $item->fecha_analisis);
        $resp->script("$('#ModalReales').modal('show');");
        return $resp;
    }
    $xajax->registerFunction("editarRecursoReal");
    
    function guardarRecursoReal( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        if( $form["fecha_analisis"] == ""){
            $message.= "<li>Fecha An&aacute;lisis</li>";
        }
        if( $form["cod_recurso_real"] == "-"){
            $message.= "<li>Recurso</li>";
        }
        if( $form["cod_cargo_real"] == "-"){
            $message.= "<li>Cargo</li>";
        }
        if( $form["horas_real"] == ""){
            $message.= "<li>Horas</li>";
        }
        if( $form["porcentaje_completado"] == ""){
            $message.= "<li>Porcentaje Completado</li>";
        }
        
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        
        
        $object = new stdClass();
        $object->cod_proyecto = $form["cod_proyecto"];
        $object->fecha_analisis = $form["fecha_analisis"];
        $object->cod_recurso = $form["cod_recurso_real"];
        $object->cod_cargo = $form["cod_cargo_real"];
        $object->horas = $form["horas_real"];
        $object->porcentaje_completado = $form["porcentaje_completado"];
        
        if( $form["id_proyecto_recurso_real"] == "" ){
            $object->cod_usuario = $user->id_recurso;
            $object->id_proyecto_recurso_real = $db->insertObject( $object , "proyecto_recurso_real" );
            if( $object->id_proyecto_recurso_real == -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }else{
            if( !$db->updateObject( $object , "proyecto_recurso_real" , "id_proyecto_recurso_real ='".$form["id_proyecto_recurso_real"]."'" ) ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=uso_recurso&id=".$form["cod_proyecto"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
        
    }
    $xajax->registerFunction("guardarRecursoReal");
    
    function calculaPorcentajeReal( $form ){
        $resp = new xajaxResponse();
        
        global $db;
        
        $sql = "select  horas total from proyecto_recurso_planificado where cod_proyecto = '".$form["cod_proyecto"]."' and cod_recurso = '".$form["cod_recurso_real"]."' and cod_cargo ='".$form["cod_cargo_real"]."' and estado = 1 ";
        //$resp->alert( $sql );
        $total = $db->selectObjectsBySql($sql);        
        $porcentaje =$total[0]->total;
        $resp->alert( $porcentaje );
        //eval( "$porcentaje = ( ".$form['horas_real']."  / ".$horas_totales." ) *100 ");
        //$resp->alert( $porcentaje );
/*        if( $horas_totales != ""){
            $porcentaje = ( $form["horas_real"] / $horas_totales ) *100;
        }else{
            $porcentaje = 0;
        }
  */      
        
        $resp->assign("horas_recurso", "value", round( $porcentaje , 2 ) );
        return $resp;
    }
    $xajax->registerFunction("calculaPorcentajeReal");
    
    function cambiarEstadoHito( $id , $cod_estado , $id_proyecto , $correo ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        global $plantilla_correo;
        $notificados = getCorreos( $correo , $id_proyecto );
        // $resp->alert( print_r($notificados , true ));
        // return $resp;
        $copiaComercial = getCorreos("comercial");
        
        $resp->call("xajax_actualizaValor","proyecto_hito",$id,"cod_estado_hito",$cod_estado ,0 );
        
        $object = new stdClass();
        $object->cod_proyecto_hito = $id;
        $object->cod_estado_hito = $cod_estado;
        $object->notificado = implode( "," , $notificados );
        $object->cod_usuario = $user->id_recurso;
        $object->id_proyecto_hito_estado = $db->insertObject( $object , "proyecto_hito_estado" );
        if( $object->id_proyecto_hito_estado == -1 ){
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;
        }
        $proyecto = $db->selectObject("proyecto", "id_proyecto='".$id_proyecto."'");
        $hito = $db->selectObject("proyecto_hito", "id_proyecto_hito='".$id."'");
        $correo = $plantilla_correo[$cod_estado];
        
        $url = SITE_URL."/index.php?view=proyecto&action=hito&id=".$id_proyecto;
        $search = array( "@project@" , "@hito@" , "@recurso@" , "@url@" , "@datos_hito@" );
        $replace = array( $proyecto->proyecto , $hito->nro_hito , $user->recurso , $url);
        $subject = str_replace(  $search , $replace , $correo["subject"] );
        $message = str_replace( $search , $replace , $correo["message"] );
        
        $sURL = "index.php?".$_SERVER['QUERY_STRING'];        
        $resp->script("crearDialogo('Respuesta','Se guardo correctamente');");
        $resp->redirect( $sURL ,1 );
        
        //$resp->call("xajax_enviarCorreo" , $subject ,$message , "joseluis.forero@gmail.com");
        $resp->call("xajax_enviarCorreo" , $subject ,$message , $notificados , $copiaComercial );
        return $resp;
    }
    $xajax->registerFunction("cambiarEstadoHito");  
    
    function editarDatosHito( $id ){
        $resp = new xajaxResponse();
        global $db;
        $hito = $db->selectObject("proyecto_hito", "id_proyecto_hito='".$id."'");
        //$resp->script("$('#fecha_envio_factura').val('".$hito->fecha_envio_factura."');");
        $resp->assign("id_proyecto_hito","value", $id);
        $resp->assign("fecha_hito","value", $hito->fecha_hito);
        $resp->assign("cod_moneda","value", $hito->cod_moneda);
        $resp->assign("porcentaje","value", $hito->porcentaje);
        $resp->assign("valor","value", $hito->valor);
        $resp->assign("nro_hito","value", $hito->nro_hito);
        $resp->assign("comentario","value", $hito->comentario);
        $resp->assign("mca_control_cambio","checked", (($hito->mca_control_cambio)?"checked":""));
        // $resp->assign("valor_proyecto","value", $hito->valor_proyecto);
        //$resp->assign("retencion","value", $hito->retencion);
        $resp->script("$('#ModalHito').modal('show');");
        return $resp;
    }
    $xajax->registerFunction("editarDatosHito");
        
    function editarDatosFactura( $id ){
        $resp = new xajaxResponse();
        global $db;
        $hito = $db->selectObject("proyecto_hito", "id_proyecto_hito='".$id."'");
        //$resp->script("$('#fecha_envio_factura').val('".$hito->fecha_envio_factura."');");
        $resp->assign("id_proyecto_hito_factura","value", $id);
        $resp->assign("fecha_envio_factura","value", $hito->fecha_envio_factura);
        $resp->assign("nro_documento_emitido","value", $hito->nro_documento_emitido);
        $resp->assign("ruta_documento_pago","value", $hito->ruta_documento_pago);
        $resp->assign("nota_factura","value", $hito->nota_factura);
        $resp->assign("datos_factura","value", $hito->datos_factura);
        $resp->assign("concepto_factura","value", $hito->concepto_factura);
        $resp->assign("documento_propuesta","value", $hito->documento_propuesta);
        $resp->script("$('#ModalFactura').modal('show');");
        return $resp;
    }
    $xajax->registerFunction("editarDatosFactura");
        
    function guardarDatosFactura( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
    
        $object = new stdClass();
        unset( $object );
        $object->fecha_envio_factura = (( $form["fecha_envio_factura"] == "" )?"NULL":$form["fecha_envio_factura"]);
        $object->nro_documento_emitido = $form["nro_documento_emitido"];
        $object->ruta_documento_pago = $form["ruta_documento_pago"];
        $object->nota_factura = $form["nota_factura"];
        $object->datos_factura = $form["datos_factura"];
        $object->concepto_factura = $form["concepto_factura"];
        $object->documento_propuesta = $form["documento_propuesta"];
        
        //$resp->alert( print_r( $form , true ) );
        //return $resp;
        if( !$db->updateObject( $object , "proyecto_hito" ,"id_proyecto_hito='".$form["id_proyecto_hito_factura"]."'") ){        
            $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
            return $resp;
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=hito&id=".$_REQUEST["id"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
                    
    }
    $xajax->registerFunction("guardarDatosFactura");
    
    
    function enviarNotificacionPlanificacion( $form , $txt_semana ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        global $meses;
        
        $mensaje = "";
        $filtro_recursos = "";
        if( count( $form["recurso_enviar"] ) > 0 ){
            $filtro_recursos= implode("," , $form["recurso_enviar"]  );
            $filtro_recursos = "and p.cod_recurso in ( ".$filtro_recursos." ) ";
        }        
        
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
                and date_format( p.fecha , '%Y-%m-%d') between '".$form["fecha_inicio"]."' and '".$form["fecha_fin"]."'
                ".$filtro_recursos."
                order by recurso, p.fecha, pr.proyecto";
        
        
        $programaciones = $db->selectObjectsBySql($sql);
        
        $array_programacion = array();
        $array_fechas = array();
        $array_recursos = array();
        foreach( $programaciones as $programacion ){
            $array_programacion[$programacion->cod_recurso][ $programacion->proyecto][ $programacion->fecha ]+= $programacion->asignacion;
            $array_programacion[$programacion->cod_recurso][ $programacion->proyecto]["gerente"] = $programacion->gerente;
            $array_fechas[]=$programacion->fecha;
            
            $array_recursos[$programacion->cod_recurso]->recurso = $programacion->recurso;
            $array_recursos[$programacion->cod_recurso]->correo = $programacion->correo;
        }
        $array_fechas = array_unique($array_fechas);
        $subject = ABR_TITLE." - Planificación ".$txt_semana;
        
        $formato_msj = "Buen día @recurso@,<br/><br/>
            Tu asignación para la @semana@ es la siguiente:<br/><br/>
            @tabla_asignacion@<br/><br/>
            Cualquier inquietud comunicarse con su gerente de proyecto o en caso de no tener con el gerente de operaciones.<br/><br/>
            <img title='SIGO Bision' src='cid:img-logo' />
            ";
        
        $cabecera_tabla = "<tr><th  style='padding:3px;'>Proyecto</th>";
        foreach( $array_fechas as $fecha ){
            $m = date("n", strtotime( $fecha ) );
            $d = date("d", strtotime( $fecha ) );
            $cabecera_tabla.="<th  style='padding:3px;'>".$meses[$m]." ".$d."</th>";
        }
        $cabecera_tabla.="</tr>";
        $salida_recurso = "";
        $cuerpo_tabla ="";
        $historia = new stdClass();
        foreach( $array_programacion as $cod_recurso => $proyectos ){
            unset( $total);
            unset( $historia );
            $cuerpo_tabla = "<table border='1' style='border:1px solid #000; border-collapse:collapse;' >";
            $cuerpo_tabla.=$cabecera_tabla;
            $total = array();
            foreach( $proyectos as $proyecto => $fechas ){
                $cuerpo_tabla.="<tr><td style='padding:3px;'>".$proyecto." (".$fechas["gerente"]." )</td>" ;
                
                foreach( $array_fechas as $fecha ){
                    $cuerpo_tabla.="<td style='text-align:center;padding:3px;'>".(( $fechas[$fecha] != "" )?$fechas[$fecha]."%":"")."</td>" ;
                    $total[$fecha]+=$fechas[$fecha];
                }
                $cuerpo_tabla.="</tr>";
            }
            $cuerpo_tabla.="<tr style='font-weight:bold;'><td style='padding:3px;'>Total</td>" ;            
            foreach( $array_fechas as $fecha ){
                $cuerpo_tabla.="<td style='text-align:center;padding:3px;'>".(( $total[$fecha] != "" )?$total[$fecha]."%":"0%")."</td>" ;                
            }
            $cuerpo_tabla.="</tr>";
            $cuerpo_tabla.= "</table>";
            $search = array( "@recurso@" , "@tabla_asignacion@" , "@semana@" );
            $replace = array( $array_recursos[$cod_recurso]->recurso , $cuerpo_tabla , $txt_semana );
            $message = str_replace(  $search , $replace , $formato_msj );
            unset( $total);
            
            $mensaje.= "<li>".$array_recursos[$cod_recurso]->recurso." - ".$array_recursos[$cod_recurso]->correo."</li>";
            $resp->assign( "divmensajetest","innerHTML","Enviando mensaje a: ".$array_recursos[$cod_recurso]->recurso." - ".$array_recursos[$cod_recurso]->correo);
            
            $historia->cod_recurso = $cod_recurso;
            $historia->mensaje = $subject." ".$message;
            $historia->fecha_inicio=$form["fecha_inicio"];
            $historia->fecha_fin=$form["fecha_fin"];
            $historia->cod_usuario = $user->id_recurso;
            
            if( $db->insertObject($historia, "historial_notificacion_planificacion") == -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
            $resp->assign( "td_recurso_".$cod_recurso,"innerHTML","<img src='".IMG_SENT."' title='Mensaje enviado' style='width:32px;' />");
            $resp->call("xajax_enviarCorreo" , $subject ,$message ,  $array_recursos[$cod_recurso]->correo );
            $resp->sleep(20);            
        }
        $mensaje = "Se enviaron todas las notificaciones a los siguiente recursos.<br /><ul>".$mensaje."</ul>";
        $resp->assign( "divmensajetest","innerHTML",$mensaje );        
        return $resp;
    }
    $xajax->registerFunction("enviarNotificacionPlanificacion");
    
    function editarTarea( $id ){
        $resp = new xajaxResponse();
        global $db;
        $tarea = $db->selectObject("proyecto_tarea", "id_proyecto_tarea='".$id."'");        
        //$resp->script("$('#fecha_envio_factura').val('".$hito->fecha_envio_factura."');");
        $resp->assign("id_proyecto_tarea","value", $id);
        $resp->assign("fecha_inicio","value", $tarea->fecha_inicio);
        $resp->assign("fecha_fin","value", $tarea->fecha_fin);
        $resp->assign("proyecto_tarea","value", $tarea->proyecto_tarea);
        $resp->assign("cod_grupo_tarea","value", $tarea->cod_grupo_tarea);
        $resp->assign("cod_cargo","value", $tarea->cod_cargo);
        $resp->assign("cod_estado_tarea","value", $tarea->cod_estado_tarea);
        $resp->script("$('#cod_estado_tarea').prop('disabled', '');");
        
        $resp->assign("cod_responsable","value", $tarea->cod_responsable);
        $resp->script("$('#cod_responsable').prop('disabled', '');");

        if( $tarea->tiempo_ejecutado > 0 ){
            $resp->script("$('#cod_responsable').prop('disabled', 'disabled');");
        }
        $resp->assign("comentario","value", $tarea->comentario);        
        $resp->assign("hora","value", extrae_tiempo( $tarea->tiempo_estimado , "hora" ) );
        $resp->assign("minuto","value", extrae_tiempo( $tarea->tiempo_estimado , "minutos" ) );
        //$resp->assign("retencion","value", $hito->retencion);
        $resp->script("$('#ModalTarea').modal('show');");
        $resp->assign("tareaModalLabel","innerHTML", "Editar Tarea");
        return $resp;
    }
    $xajax->registerFunction("editarTarea");
    
    function guardarTarea( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        
        $message ="";
        if( $form["proyecto_tarea"] == ""){
            $message.= "<li>Tarea</li>";
        }/*
        if( $form["fecha_inicio"] == ""){
            $message.= "<li>Fecha Inicio</li>";
        }
        if( $form["fecha_fin"] == ""){
            $message.= "<li>Fecha Fin</li>";
            }*/
        if( $form["cod_cargo"] == "-"){
            $message.= "<li>Cargo</li>";
        }
        if( $form["cod_responsable"] == "-"){
            $message.= "<li>Responsable</li>";
        }
        if( $form["cod_estado_tarea"] == "0"){
            $message.= "<li>Estado Tarea</li>";
        }
        if( $form["cod_grupo_tarea"] == "0"){
            $message.= "<li>Grupo Tarea</li>";
        }
        $tiempo_estimado =  (( $form["hora"] == "")?0:$form["hora"]*3600)+(($form["minuto"] == "")?0:$form["minuto"]*60);
        if( ( $form["hora"] == "" || $form["hora"] < 0 ) && ( $form["minuto"] == "" || $form["minuto"] < 0 )  ){
            $message.= "<li>Tiempo Estimado</li>";
        }
        
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        //$resp->alert( print_r( $form , true ) ); return $resp;
        
        $object = new stdClass();
        unset( $object );
        
        $object->proyecto_tarea = $form["proyecto_tarea"];
        $object->fecha_inicio = (( $form["fecha_inicio"] =="")?"NULL":$form["fecha_inicio"] );
        $object->fecha_fin = (( $form["fecha_fin"] == "")?"NULL":$form["fecha_fin"] );
        $object->cod_responsable = $form["cod_responsable"];
        $object->cod_cargo = $form["cod_cargo"];
        $object->cod_grupo_tarea = $form["cod_grupo_tarea"];
        $object->cod_estado_tarea = $form["cod_estado_tarea"];
        $object->tiempo_estimado = $tiempo_estimado;
                
        $object->comentario = $form["comentario"];

        if( $form["id_proyecto_tarea"] != "" ){
             if( !$db->updateObject( $object , "proyecto_tarea" ,"id_proyecto_tarea='".$form["id_proyecto_tarea"]."'" ) ){
                 $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                 return $resp;
             }
        }else{
            $object->id_bitrix= "NULL";
            $object->cod_proyecto = $form["cod_proyecto"];
            $object->cod_usuario = $user->id_recurso;
            $object->tiempo_ejecutado = 0;
            $object->id_proyecto_tarea = $db->insertObject( $object , "proyecto_tarea" );
            if( $object->id_proyecto_tarea== -1 ){
                 $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                 return $resp;
            }
        }
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"]."&action=tarea&id=".$form["cod_proyecto"];
        $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        return $resp;
             
    }
    $xajax->registerFunction("guardarTarea");
    
    function registrarTarea( $id , $id_registro = null ){
        $resp = new xajaxResponse();
        global $db;
        $tarea = $db->selectObject("proyecto_tarea", "id_proyecto_tarea='".$id."'");
        $resp->assign("id_proyecto_tarea","value", $id);
        if( $id_registro != null ){
            $hora = $db->selectObject("proyecto_tarea_registro", "id_proyecto_tarea_registro='".$id_registro."'");
            $resp->assign("fecha_registro","value", $hora->fecha_registro);
            $resp->assign("hora","value", extrae_tiempo( $hora->tiempo_ejecutado, "hora" ));
            $resp->assign("minuto","value", extrae_tiempo( $hora->tiempo_ejecutado , "minutos" ));
            $resp->assign("segundo","value", extrae_tiempo( $hora->tiempo_ejecutado , "segundo" ));
            $resp->assign("comentario","value", $hora->comentario);
            $resp->assign("id_proyecto_tarea_registro","value", $id_registro);
            $resp->assign("vista","value", "editar");
        }else{
            $resp->assign("fecha_registro","value", date("Y-m-d"));
            $resp->assign("hora","value", "");
            $resp->assign("minuto","value", "");
            $resp->assign("segundo","value", "");
            $resp->assign("comentario","value", "");
            $resp->assign("id_proyecto_tarea_registro","value", "");
            $resp->assign("vista","value", "");
        }
        
        $resp->script("$('#ModalRegistro').modal('show');");
        $resp->script("$('#ModalHistorialRegistro').modal('hide');");
        return $resp;
    }
    $xajax->registerFunction("registrarTarea");
    
    
    function playTarea( $id , $id_registro = 0 ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        //$tarea = $db->selectObject("proyecto_tarea", "id_proyecto_tarea='".$id."'");        
        $object = new stdClass();
        unset( $object );
        if( $id_registro == 0 ){
            $object->fecha_registro = date("Y-m-d");
            $object->fecha_registro_ejecucion = "CURRENT_TIMESTAMP";
            $object->tiempo_ejecutado = 0;
            $object->comentario = '';    
            $object->cod_proyecto_tarea = $id;
            $object->mca_manual = 0;
            $object->mca_historico = 0;
            $object->mca_ejecucion = 1;
            $object->id_bitrix = "NULL";
            $object->cod_usuario = $user->id_recurso;
            $object->fecha_actualizacion = "NULL";
            
            //$resp->alert( print_r( $object , true )); return $resp;
            $object->id_proyecto_tarea_registro = $db->insertObject( $object , "proyecto_tarea_registro" );
            
            
            if( $object->id_proyecto_tarea_registro == -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
            $registro = new stdClass();
            $registro = $db->selectObject("proyecto_tarea_registro", "id_proyecto_tarea_registro='".$object->id_proyecto_tarea_registro."'");
            $_SESSION["SES_PLAYTIME"] = $registro->fecha_registro_ejecucion;
            $_SESSION["SES_TAREA"] = $id;
            $_SESSION["SES_TAREA_REG"] = $object->id_proyecto_tarea_registro;
        }else{
            $registro = new stdClass();
            $registro = $db->selectObject("proyecto_tarea_registro", "id_proyecto_tarea_registro='".$id_registro."'");
            $object->mca_ejecucion = 0;
            $object->fecha_actualizacion = "CURRENT_TIMESTAMP";
            $object->tiempo_ejecutado = (time()-strtotime($registro->fecha_registro_ejecucion ) );
            $object->fecha_registro_ejecucion = "NULL";
            if( !$db->updateObject( $object , "proyecto_tarea_registro" ,"id_proyecto_tarea_registro='".$id_registro."'" ) ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }           
            unset( $_SESSION["SES_PLAYTIME"] );
            unset( $_SESSION["SES_TAREA"] );
            unset( $_SESSION["SES_TAREA_REG"] );
            $tiempo_total = $db->selectSum("proyecto_tarea_registro", "tiempo_ejecutado" , "cod_proyecto_tarea='".$id."'");
            
            $resp->call("xajax_actualizaValor","proyecto_tarea",$id,"tiempo_ejecutado",$tiempo_total,0);
            
        }
        
        $message = "Se guardo correctamente";
        $sURL = "index.php?view=".$_REQUEST["view"];
        //$resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        $resp->redirect( $sURL ,1 );
        return $resp;
    }
    $xajax->registerFunction("playTarea");
    
    function guardarRegistroTarea( $form ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        if( $form["fecha_registro"] == ""){
            $message.= "<li>Fecha Registro</li>";
        }
        
        $tiempo_ejecutado =  (( $form["hora"] == "")?0:$form["hora"]*3600)+(($form["minuto"] == "")?0:$form["minuto"]*60)+(($form["segundo"]=="")?0:$form["segundo"]);
        //if( ( $form["hora"] == "" || $form["hora"] < 0 ) || ( $form["minuto"] == "" || $form["minuto"] < 0 || $form["minuto"] > 59 )  ){
        if( ( $form["hora"] =="" && $form["minuto"] == "" ) || ( $form["hora"] != "" && $form["hora"] <0 ) || ( $form["minuto"] != "" && $form["minuto"] <0 ) ){
            $message.= "<li>Tiempo Ejecutado</li>";
        }
        
        if( $message != "" ){
            $message = "Los siguientes campos son obligatorios: <ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        $message = "";
        
        if( $message != "" ){
            $message = "<ul>".$message."</ul>";
            $resp->script("crearDialogo('Advertencia','$message','');");
            return $resp;
        }
        
        $cierre = $db->selectObject("cierre", "'".$form["fecha_registro"]."' between semana_inicio and semana_fin");
        //$resp->alert( date('Y-m-d H:i:s') );
        //$resp->alert(print_r( $cierre , true ) );
        
        $date1 = new DateTime("now");
        $date2 = new DateTime( $cierre->fecha_cierre );
        if( $date2 < $date1 ){            
            $permiso = $db->selectObject("cierre_permiso", "cod_responsable='".$user->id_recurso."' and estado_permiso = 1 and cod_cierre = '".$cierre->id_cierre."' and fecha >='".date('Y-m-d H:i:s')."'");
            
            if( count( $permiso ) == 0){
                /*$resp->alert( $db->ssqqll );
                $resp->alert(print_r( $permiso , true ) );*/            
                $resp->script("crearDialogo('Advertencia','No se puede realizar el registro de horas en la fecha indicada ya que la semana se encuentra cerrada.<br/>Por favor comuniquese con su líder o gerente de operaciones.')");
                return $resp;
            }
        }
        
        //$resp->alert(print_r( $form , true )); 
        //return $resp;
        
        
        $object = new stdClass();
        unset( $object );
        
        $object->fecha_registro = $form["fecha_registro"];
        $object->tiempo_ejecutado = $tiempo_ejecutado;
        
        $object->comentario = $form["comentario"];
        
        //$resp->alert( print_r( $form , true ) );
        //return $resp;
        if( $form["id_proyecto_tarea_registro"] != "" ){
            $object->fecha_actualizacion = "CURRENT_TIMESTAMP";
            if( !$db->updateObject( $object , "proyecto_tarea_registro" ,"id_proyecto_tarea_registro='".$form["id_proyecto_tarea_registro"]."'" ) ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }else{
            
            $object->cod_proyecto_tarea = $form["id_proyecto_tarea"];
            $object->mca_manual = 1;
            $object->mca_ejecucion = 0;
            $object->mca_historico = 0;
            $object->id_bitrix = "NULL";
            $object->cod_usuario = $user->id_recurso;
            $object->fecha_actualizacion = "NULL";
            
            $object->id_proyecto_tarea_registro = $db->insertObject( $object , "proyecto_tarea_registro" );            
            if( $object->id_proyecto_tarea_registro == -1 ){
                $resp->script("crearDialogo('Error','A ocurrido un error durante la ejecución.<br/>Por favor inténtelo más tarde.<br/>".htmlentities( $db->error() , ENT_QUOTES )."')");
                return $resp;
            }
        }
        $tiempo_total = $db->selectSum("proyecto_tarea_registro", "tiempo_ejecutado" , "cod_proyecto_tarea='".$form["id_proyecto_tarea"]."'");
        
        $resp->call("xajax_actualizaValor","proyecto_tarea",$form["id_proyecto_tarea"],"tiempo_ejecutado",$tiempo_total,0);
        if( $form["vista"] != "" ){
            $resp->call("xajax_historialTarea",$form["id_proyecto_tarea"]);
        }else{
            $message = "Se guardo correctamente";
            $sURL = "index.php?view=".$_REQUEST["view"];
            $resp->script("crearDialogo('Respuesta','".$message."', 'window.location.href=\'".$sURL."\'');");
        }
        return $resp;
        
    }
    $xajax->registerFunction("guardarRegistroTarea");
    
    
    function historialTarea( $id ){
        $resp = new xajaxResponse();
        global $db;
        global $user;
        $tarea = $db->selectobject( "proyecto_tarea","id_proyecto_tarea='".$id."'");
        $horas = $db->selectObjects("proyecto_tarea_registro","cod_proyecto_tarea='".$id."' and estado = 1 and mca_ejecucion=0","fecha_registro desc, fecha_creacion desc");
        $salida = "";
        $salida.='
            <div class="form-row">
        		<div class="form-group col-md-12">
            		'.$tarea->comentario.'
				</div>
        		<div class="form-group col-md-16">
            		<label for="fecha_registro">Tiempo Ejecutado</label> <span id="div_crono_3_'.$id.'">'.segundos_tiempo( $tarea->tiempo_ejecutado+(( $_SESSION["SES_PLAYTIME"]!="" && $_SESSION["SES_TAREA"] == $id )?(time()-strtotime($_SESSION["SES_PLAYTIME"])):0) ).'</span>
				</div>
            </div>
           <table class="table table-striped">
            <thead class="thead-dark">
        	   <tr style="text-align:center;">
                <th class="p-1" scope="col">Fecha Registro</th>
                <th class="p-1" scope="col">Tiempo Reportado</th>
                <th class="p-1" scope="col">Comentario</th>
                <th class="p-1" scope="col"></th>
                <th class="p-1" scope="col"></th>
                </tr>
            </thead><tbody>';
        //<th class="p-1" scope="col"></th>
        foreach( $horas as $hora ){
            $salida.='
        	   <tr style="text-align:center;">
                <td class="p-1">'.$hora->fecha_registro.'</td>
                <td class="p-1">'.segundos_tiempo( $hora->tiempo_ejecutado ).'</td>
                <td class="p-1"  style="text-align:left;">'.$hora->comentario.'</td>
                <td class="p-1"  style="text-align:left;"><img src="'.IMG_EDIT.'" style="width:20px;cursor:pointer" title="Editar" onclick="xajax_registrarTarea(\''.$id.'\',\''.$hora->id_proyecto_tarea_registro.'\');"/></td>
                <td class="p-1"  style="text-align:left;">';
            if( validaCierre($hora->fecha_registro) ){
                $salida.="<img onclick=\"crearDialogo('confirmacion','¿Está seguro de eliminar el registro seleccionado?','xajax_eliminarFuncion','\'proyecto_tarea_registro\'','".$hora->id_proyecto_tarea_registro."','\'actualizaHoras\'', '".$id."');\" src='".IMG_DELETE."' style='width:20px;cursor:pointer' title='Eliminar' />";
            }
            $salida.='</td>
                </tr>
             ';
            //<td class="p-1"  style="text-align:left;"><img onclick="xajax_registrarTarea(\''.$id.'\',\''.$hora->id_proyecto_tarea_registro.'\');" src="'.IMG_EDIT.'" style="width:16px"/></td>
        }
        $salida.="</tbody></table>";
        $resp->assign("div_historial", "innerHTML", $salida );
        $resp->assign("ModalTitleHistorial", "innerHTML", "Historia: ".$tarea->proyecto_tarea );
        //$resp->assign("id_proyecto_tarea","value", $id);
        /*
         //$resp->script("$('#fecha_envio_factura').val('".$hito->fecha_envio_factura."');");
         
         $resp->assign("fecha_envio_factura","value", $hito->fecha_envio_factura);
         $resp->assign("nro_documento_emitido","value", $hito->nro_documento_emitido);
         $resp->assign("ruta_documento_pago","value", $hito->ruta_documento_pago);
         $resp->assign("nota_factura","value", $hito->nota_factura);
         $resp->assign("datos_factura","value", $hito->datos_factura);
         $resp->assign("concepto_factura","value", $hito->concepto_factura);
         $resp->assign("documento_propuesta","value", $hito->documento_propuesta);*/
        $resp->script("$('#ModalHistorialRegistro').modal('show');");
        return $resp;
    }
    $xajax->registerFunction("historialTarea");
    
    function actualizaHoras( $id ){
        $resp = new xajaxResponse();
        global $db;
        
        $tiempo_total = $db->selectSum("proyecto_tarea_registro", "tiempo_ejecutado" , "cod_proyecto_tarea='".$id."'");
        //$resp->alert($tiempo_total ); return $resp;
        $resp->call("xajax_actualizaValor","proyecto_tarea",$id,"tiempo_ejecutado",$tiempo_total,0);
        $message = "Se elimino correctamente";
        $sURL = "index.php?".$_SERVER['QUERY_STRING'];
        
        $resp->script("crearDialogo('Respuesta','".$message."');");
        $resp->call( "xajax_historialTarea", $id );
        //$resp->redirect( $sURL ,1 );
        return $resp;
    }
    $xajax->registerFunction("actualizaHoras");    
    
    function showTime( $time ){
        $hours = floor( $time / 3600  );
        $mins = floor( $time / 60 ) % 60 ;
        $segs = $time % 60;
        return str_pad( $hours , 2 ,'0' , STR_PAD_LEFT ).":".str_pad( $mins , 2 , '0' , STR_PAD_LEFT ).":".str_pad( $segs , 2 , '0' , STR_PAD_LEFT );
    }
    
    function showCrono(){
        $resp = new xajaxResponse();
        global $db;
        $tarea = $db->selectObject("proyecto_tarea", "id_proyecto_tarea = '".$_SESSION["SES_TAREA"]."'");
        $proyecto = $db->selectObject("proyecto", "id_proyecto = '".$tarea->cod_proyecto."'");
        $time = showTime( $tarea->tiempo_ejecutado+time()-strtotime($_SESSION["SES_PLAYTIME"]) );
        $actual_time =showTime(time()-strtotime($_SESSION["SES_PLAYTIME"]));
        $time2 = segundos_tiempo( $tarea->tiempo_ejecutado+time()-strtotime($_SESSION["SES_PLAYTIME"]) );
        $salida1 = "<span class='badge bg-success text-light p-1 mt-1'>".$time2."</span>";
        $salida2 = "<span title='Proyecto:  ".$proyecto->proyecto."'>".$tarea->proyecto_tarea."</span>";        
        $salida2.= "&nbsp;<span style='font-weight:bold;'>Actual:</span>&nbsp;".$actual_time; 
        $salida2.= "&nbsp;<span style='font-weight:bold;'>Total: ".$time."</span>&nbsp;";
        $salida2.= "<img onclick='xajax_playTarea(". $tarea->id_proyecto_tarea." ,".$_SESSION["SES_TAREA_REG"].");' src='".IMG_STOPTIME."' style='width:16px;cursor:pointer;' title='Detener Tarea'/>";
        $salidatitle = $actual_time." ".ABR_TITLE." ".$tarea->proyecto_tarea." ";
        $salida3 = $time2;
        $resp->script("document.title = '".$salidatitle."'");
        
        $resp->assign("div_crono_1_".$_SESSION["SES_TAREA"], "innerHTML", $salida1 ); // Lista de tareas
        $resp->assign("div_crono_2", "innerHTML", $salida2 ); // Barra Superior
        $resp->assign("div_crono_3_".$_SESSION["SES_TAREA"], "innerHTML", $salida3 ); // Historial Tareas
        return $resp;
    }
    $xajax->registerFunction("showCrono");
?>

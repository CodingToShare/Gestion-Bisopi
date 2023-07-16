<?php
    class usuario{
        var $mensaje = "";
        var $caducada = false;
        function tratarVariable( $variable ){
            $variable = trim( $variable );
            $variable = stripslashes( $variable );
            global $db;
            $variable = mysqli_real_escape_string( $db->connection ,  $variable );
            return $variable;
        }
        
        function getMensaje(){
            return $this->mensaje;
        }
        
        function iniciarSesion( $usuario , $clave ){
            $this->mensaje = "";
			//Tratar variable usuario
            $usuario =  $this->tratarVariable( $usuario );
            if( $usuario == "" ){
                // $this->mensaje = "Ingrese su C&eacute;dula";
                $this->mensaje = "Ingrese su correo";
                return false;
            }
            // Tratar variable clave
            $clave = $this->tratarVariable( $clave );
            
            if( $clave == ""){
                $this->mensaje = "Ingrese su Contrase&ntilde;a";
                return false;
            }
            global $db;
            $user = $db->selectObject( "recurso" , "correo='".$usuario."'");
            if( count( $user ) == 0 ){
                //$this->mensaje = "La c&eacute;dula o la contrase&ntilde;a estan equivocadas.";
                $this->mensaje = "El correo o la contrase&ntilde;a estan equivocadas.";
                return false;
            }
            if( $user->password != md5( $clave ) ){                
                $this->mensaje = "El correo o la contrase&ntilde;a estan equivocadas.";
                return false;
            }
            $this->iniciarVariables( $user->id_recurso , 1 );            
            return true;
        }
        
        function iniciarVariables( $id_usuario , $tipo ){
            $_SESSION[ SES_ID ]  = session_id();
            $_SESSION[ SES_USER ]  = $id_usuario;
            $_SESSION[ SES_TIPO ]  = $tipo;
			$_SESSION[ SES_FECHA ] = time();
			
			global $db;
			$registro = new stdClass();
			$sql = "select id_proyecto_tarea , fecha_registro_ejecucion , id_proyecto_tarea_registro
            from proyecto_tarea pt
            inner join proyecto_tarea_registro ptr on pt.id_proyecto_tarea = ptr.cod_proyecto_tarea
            where pt.cod_responsable = ".$id_usuario." and ptr.mca_ejecucion = 1";
			$registros = $db->selectObjectsBySql($sql);
			if( count($registros) > 0 ){
			    $registro = $registros[0];
			    $_SESSION["SES_PLAYTIME"] = $registro->fecha_registro_ejecucion;
			    $_SESSION["SES_TAREA"] = $registro->id_proyecto_tarea;;
			    $_SESSION["SES_TAREA_REG"] = $registro->id_proyecto_tarea_registro;;
			}
			
			
			//$this->setIngresoUsuario($id_usuario);
            return true;
        }
        function salir(){
            //$this->setSalidaUsuario( $_SESSION[ SES_USER ] );
            unset( $_SESSION[ SES_USER ] );
            unset( $_SESSION[ SES_ID ] );
            session_unset();
            session_destroy();
            session_start();
            session_regenerate_id(true);
            unset($_SESSION);
            $_SESSION=array();
            return true;
        }
        /*
        
        
        
    
        function setIngresoUsuario( $id ){
        	global $db;
        	$item = new stdClass();
        	$item->cod_usuario = $id;
        	$item->browser = $_SERVER["HTTP_USER_AGENT"];
        	$item->ingreso = time();
        	$item->salida = "NULL";
        	$item->session_id = $_SESSION[ SES_ID ];
        	$item->direccion_ip = $_SERVER["REMOTE_ADDR"];
        	$item->id_usuario_historia = $db->insertObject( $item , "usuario_historia" );
			if( $item->id_usuario_historia == -1 ){
				return false;
			}
			return true;
        	
        }
    
        function setSalidaUsuario( $id ){
        	global $db;
        	$item = new stdClass();
        	$item->salida = time();
        	if( !$db->updateObject( $item , "usuario_historia" ,"cod_usuario = ".$id." and salida is null and session_id = '".$_SESSION[ SES_ID ]."'" ) ){
				return false;
			}
			return true;
        	
        }
        
        
        function recuperarClave( $usuario ){
            if( isset( $_SESSION[ SES_USER ] ) )
                return false;
                
            $usuario = tratarVariable( $usuario );
            if( $usuario == "" ){
                $this->mensaje = "Ingrese su C&eacute;dula";
                return false;
            }
            
            if( !is_numeric( $usuario ) ){
                $this->mensaje = "La c&eacute;dula debe ser num&eacute;rica";
                return false;
            }
            
            global $db;
            $usuario = $db->selectObject( "usuario" , "cedula='".$usuario."'" );
            if( count( $usuario ) == 0 || !$usuario ){
                $this->mensaje = "La c&eacute;dula no se encuentra registrada";
                return false;
            }
            
            return true;
        }
		*/
            
        function getInfoUsuario(){
            $user = new stdClass();
            if( isset( $_SESSION[ SES_USER ] ) ){
                global $db;
                //$user = $db->selectObject( "usuario" , "id_usuario='".$_SESSION[ SES_USER ]."'");
                $sql =  "SELECT *
                    from recurso
					WHERE id_recurso='".$_SESSION[ SES_USER ]."'";
                $user = $db->selectObjectsBySql( $sql );
                $user = $user[0];
                /*$modulos_usuario = $db->selectObjectsBySql( "select
                 tum.permiso as permisos
                 ,m.modulo
                 from tipo_usuario_modulo tum
                 left outer join modulo m on tum.cod_modulo = m.id_modulo
                 where cod_tipo_usuario = '".$user->cod_tipo_usuario."'" );
                
                 $permisos = $db->selectObjects( "permiso" );
                 $array_permisos = Array();
                 if( is_array( $modulos_usuario ) && is_array( $permisos ) ){
                 foreach( $modulos_usuario as $modulo ){
                 $permisos_tmp = explode( ",", $modulo->permisos );
                 foreach( $permisos as $permiso ){
                 $nompermiso = strtolower( $permiso->permiso );
                 $array_permisos[ $modulo->modulo ]->$nompermiso = in_array( $permiso->id_permiso, $permisos_tmp );
                 }
                 }
                 }
                 $user->permisos = (object)$array_permisos; //*/
                 return $user;
                 }
                 
             return false;
         }
		function validaSesion(){
			$this->caducada = false;
			if( !isset( $_SESSION[ SES_USER ] ) )
                return false;
			$fechaGuardada = $_SESSION[ SES_FECHA ];
			$ahora = time();  
			$tiempo_transcurrido = $ahora-$fechaGuardada;
			$this->mensaje = $tiempo_transcurrido;
			if( $tiempo_transcurrido >= ( SES_TIMEOUT * 60 ) ){
				$this->salir();
				$this->caducada = true;
			}else{
				$_SESSION[ SES_FECHA ] = $ahora;
			}
			return true;
		}
    }
?>
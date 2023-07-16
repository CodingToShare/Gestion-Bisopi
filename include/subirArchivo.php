<?php
	session_start();
	include("../conf/configuracion.php");
	include("../include/mysqli.php");
	function retornaExt( $cadena ){
		$salida = substr( $cadena , strrpos( $cadena , "." )+1 );
		return $salida;
	}
	
?>
<html>
<head>
<script language="javascript">
<?php
    $file_name = $_REQUEST["file_name"];
    $form_name = $_REQUEST["form_name"];
    $file_target = $_REQUEST["file_target"];
    $fileIsImage = $_REQUEST["fileIsImage"];    
    $dir_target = $_REQUEST["dir_target"];
    
    $nomarchivo= $_FILES[ $file_name ]["name"];
    $temparchivo=$_FILES[ $file_name ]["tmp_name"];
    $tipoarchivo=$_FILES[ $file_name ]["type"];
    $errarchivo= $_FILES[ $file_name ]["error"];
    $tamarchivo= $_FILES[ $file_name ]["size"];	
	
    $id = session_id();
	$indicador=true;	
	$carpeta = "../".$dir_target;	

	if( $nomarchivo != "" ){
		$tamano=( ( 1024 * 1024 ) * ( FILE_TAM ) );
		if($tamarchivo>$tamano){
			echo("alert('El archivo excede el tamaño recomendado ( ".FILE_TAM." MB ) para subir. No se pudo guardar el archivo');\r\n");
			echo("parent.document.getElementById('btn_upload').disabled=false;");
		}
		else{
			
			/*
			 * $permitidos = explode("@", FILE_TYPES );
			if( array_search($tipoarchivo, $permitidos ) === false )
				$indicador= false;
			*/
			
			if($indicador==false){
				echo("alert('El archivo no tiene el formato permitido. No se pudo guardar el archivo');\r\n");
				echo("parent.document.getElementById('btn_upload').disabled=false;");
			}else{
				if($errarchivo==1){
					echo("alert('Ha ocurrido un error al guardar el archivo. Por favor, intente de nuevo más tarde1');\r\n");
					echo("parent.document.getElementById('btn_upload').disabled=false;");					
				}
				else{
					//$nomarchivototal = strtolower( $campo.$id.substr( $nomarchivo , strrpos( $nomarchivo , "." ) ) );
					$ext = retornaExt( $nomarchivo );
					//$prefijo_documento
					//$nombrearch = $prefijo_documento."_".date('Ymd_H_i_s');
					$nombrearch = substr( md5( uniqid( rand() ) ) , 0 , 12 )."_".date('Ymd_H_i_s');
					$rutadestino=$carpeta.$nombrearch.".".$ext;
					//echo("alert('".$rutadestino."');\r\n");
					while( file_exists( $rutadestino ) ){
					    $nombrearch = substr( md5( uniqid( rand() ) ) , 0 , 12 )."_".date('Ymd_H_i_s');
						$rutadestino=$carpeta.$nombrearch.".".$ext;
					}				
					if( move_uploaded_file( $temparchivo , $rutadestino ) ){
						//echo "parent.document.getElementById('ruta_".$campo."').value='".$rutadestino."';";
						//echo "parent.document.getElementById('tipo_".$campo."').value='".$tipoarchivo."';";
						//echo "parent.document.getElementById('tam_".$campo."').value='".$tamarchivo."';";
						//echo "parent.document.getElementById('nombre_".$campo."').value='".$nomarchivo."';";
						
						//$archivo1 = array( "nombre" => $nombre, "tipo" => $tipoarchivo,"tamano" => $tamarchivo,"ruta" => $rutadestino );
						//echo("parent.xajax_guardarArchivo( '".$nombre."' ,'".$tipoarchivo."', '".$tamarchivo."' , '".$rutadestino."' , '".$idcarpeta."' );");
					    echo("parent.document.getElementById('".$file_target."_new').value='".substr( $rutadestino , 3 )."';");					    
					    if( $fileIsImage ){					        
					        echo("parent.document.getElementById('img_".$file_target."').src='".substr( $rutadestino , 3 ) ."';");
					    }else{
					        echo("alert('Archivo cargado correctamente');\r\n");
					    }
					    //echo("parent.document.getElementById('btn_subir').disabled=false;");
					    //echo("parent.document.getElementById('archivo').disabled=true;");
					    
						//$salida = "El archivo ".$nomarchivo." se subio correctamente. <a href=javascript:; onClick=xajax_eliminarArchivoAdjunto('".$nomarchivo."');>Eliminar</a>";
						//echo("alert('OK');\r\n");
						//echo "parent.document.getElementById('pon_".$campo."').innerHTML=\"".$salida."\";";
						//echo "parent.document.getElementById('puente').src=\"\";";
					}
					else{
						echo("alert('Ha ocurrido un error al guardar el archivo. Por favor, intente de nuevo más tarde2');\r\n");
						//echo("parent.subirArchivo( 0 );");
						echo("parent.document.getElementById('btn_upload').disabled=false;");
					}
				}
			}
		}
	}
	else{
		echo("alert('Seleccione un archivo');\r\n");
		echo("parent.document.getElementById('btn_upload').disabled=false;");
	}
	
?>
</script>
</head>
</html>

<?php
	// Depuracion
	define( "DEBUG" , FALSE );
	define( "DEBUG_XAJAX" , FALSE );
	// Projecto
	define("PRJ_NAME","recursos");
	define("DEFAULT_USER","1");
	define( "ERRORS" , "E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_WARNING & ~E_NOTICE");
	define("SITE_ACTIVE", 1 ); // 1 Activo - 0 en mantenimiento
	define("SITE_SAVE_LOGS", 1 ); // 1 guardar - 0 no genera logs
	// Sesiones
	
	define( "SES_ID" , "id_".PRJ_NAME."_".session_id() );
	define( "SES_USER" , "user_".PRJ_NAME."_".session_id() );
	define( "SES_TIPO" , "type_".PRJ_NAME."_".session_id() );
	define( "SES_FECHA" , "timelogin_".PRJ_NAME."_".session_id() );
	define( "SES_TIMEOUT" , 600 ); //Valor en minutos
	define( "SES_NROWS" , "numrows_".session_id() );
	// Login
	define("PERMITIR_REGISTRO" , false );
	define("RECUPERAR_CLAVE" , false );
	//
	define("LOGIC_DELETE" , false );
	define("LIMITE_BUSCADOR" , 2 );
	//Imagenes
	define("IMG_PATH", "images/");
	define("IMG_LOGO",IMG_PATH."logo_2.png");
	define("IMG_ADD",IMG_PATH."add.png");
	define("IMG_EDIT",IMG_PATH."edit.png");
	define("IMG_DELETE",IMG_PATH."trash.png");
	define("IMG_VIEW",IMG_PATH."view.png");
	define("IMG_SCHEDULE",IMG_PATH."schedule.png");
	define("IMG_HITO",IMG_PATH."hito.png");
	define("IMG_PEOPLE",IMG_PATH."recursos.png");
	
	define("IMG_RETURN",IMG_PATH."undo.png");
	define("IMG_SEARCH",IMG_PATH."search.png");
	define("IMG_CLEAN",IMG_PATH."empty.png");
	define("IMG_UP",IMG_PATH."arrow_up.png");
	define("IMG_DOWN",IMG_PATH."arrow_down.png");
	define("IMG_USER",IMG_PATH."user.png");
	define("IMG_LOGOUT",IMG_PATH."logout.png");
	define("IMG_CLOSE",IMG_PATH."close.png");
	define("IMG_PROFILE",IMG_PATH."profile.png");
	define("IMG_PROFILE_256",IMG_PATH."profile_256.png");
	define("IMG_PREVIEW",IMG_PATH."preview.png");
	define("IMG_SENT",IMG_PATH."sent.png");
	define("IMG_LINK",IMG_PATH."link.png");
	define("IMG_COPY",IMG_PATH."copy.png");
	define("IMG_KEY",IMG_PATH."key.png");
	define("IMG_GIFT",IMG_PATH."present.png");
	define("IMG_DOWNLOAD",IMG_PATH."download.png");
	define("IMG_CLEAR",IMG_PATH."clear.png");
	define("IMG_LOCK",IMG_PATH."lock.png");
	define("IMG_UNLOCK",IMG_PATH."unlock.png");
	define("IMG_EXPORT",IMG_PATH."export.png");
	define("IMG_REPORT",IMG_PATH."report.png");
	define("IMG_MSJOK",IMG_PATH."message_accept.png");
	define("IMG_MSJERROR",IMG_PATH."message_delete.png");
	define("IMG_MSJWAIT",IMG_PATH."message_information.png");
	define("IMG_CARD",IMG_PATH."card.png");
	define("IMG_OK",IMG_PATH."approve.png");
	define("IMG_WAIT",IMG_PATH."wait.png");
	define("IMG_IO",IMG_PATH."in_out.png");
	define("IMG_CONVERT",IMG_PATH."convert.png");
	define("IMG_PRINT",IMG_PATH."print1.png");
	define("IMG_PRINT2",IMG_PATH."print2.png");
	define("IMG_DEPOSIT",IMG_PATH."deposit.png");
	define("IMG_PDF",IMG_PATH."pdf.png");
	define("IMG_FIRST_CONTACT",IMG_PATH."star.png");
	define("IMG_ATTACH",IMG_PATH."attachment.png");
	define("IMG_AGREEMENT",IMG_PATH."agreement1.png");
	define("IMG_SEND",IMG_PATH."send.png");
	define("IMG_OK_OK",IMG_PATH."yes.png");
	define("IMG_WARN",IMG_PATH."exclamation_diamond.png");
	define("IMG_INDICATOR",IMG_PATH."light.png");
	define("IMG_LIST",IMG_PATH."list.png");
	define("IMG_GRID",IMG_PATH."grid.png");
	define("IMG_KANBAN",IMG_PATH."kanban.png");
	define("IMG_CALENDAR",IMG_PATH."calendar.png");
	define("IMG_MAINTENANCE",IMG_PATH."mantenimiento.png");
	define("IMG_TASK",IMG_PATH."task.png");
	define("IMG_COMPROMISE",IMG_PATH."compromise.png");
	define("IMG_ADDTIME",IMG_PATH."add_time.png");
	define("IMG_PLAYTIME",IMG_PATH."play_time.png");
	define("IMG_STOPTIME",IMG_PATH."stop_time.png");
	define("IMG_HISTORY",IMG_PATH."history_time.png");

	//Mensajes
	define( "MSG_EXPIRED" , "La sesión se ha cerrado por tiempo de inactividad." );
	
	// Paginacion
	define("MAX_ROWS" , ( ( !isset( $_SESSION[ SES_NROWS ] )  )?15:$_SESSION[ SES_NROWS ] ) );
	define("MAX_ROWS_AUTOCOMPLETE" , 50 );
	
	//Pagina
	define("ABR_TITLE","BISOPI");
	define("TITLE","BISOPI - Sistema de Información de Gestión de Operaciones Bision Consulting SAS");
	define("DESCRIPTION", "BISOPI - Sistema de Información de Gestión de Operaciones Bision Consulting SAS" );
	define("KEYWORDS","BISOPI - Sistema de Información de Gestión de Operaciones Bision Consulting SAS");
	define("SITE_URL","https://gestion.bisionconsulting.com");
	
	//Datos Empresa
	define( "EMP_SUBJECT" , "" );
	define( "EMP_NIT" , "" );
	define( "EMP_URL" , "" );
	define( "EMP_NOMBRE" , '"' );
	define( "EMP_DIR" , "" );
	define( "EMP_TEL" , "" );
	define( "EMP_CIUDAD" , "" );
	define( "EMP_CORREO" , "" );
	define( "EMP_DEFAULT_PW" , "827ccb0eea8a706c4c34a16891f84e7b" );
	
	
	define("LIST_SEPARATOR", "@");
	define("PARAMETERS_LIST" , "project_concern".LIST_SEPARATOR."project_type".LIST_SEPARATOR."strategic_goal".LIST_SEPARATOR."target_groups".LIST_SEPARATOR."project_state".LIST_SEPARATOR."budget_line");
	define("PRIORIDAD_LIST", "Urgente".LIST_SEPARATOR."Importante".LIST_SEPARATOR."Moderado");
	
	//Archivos
	define( "DIR_PATH" , "files/" );
	define( "DIR_PATH_PERFILES" , "files/perfil/" );
	define( "TMP_DIR_PATH_PERFILES" , "tmp/perfil/" );
	//TamaÃ±o en MB
	define( "FILE_TAM" , 3 );
	define( "VIDEOS_TYPES" ,"");
	define( "IMAGES_TYPES" ,"image/png".LIST_SEPARATOR."image/bmp".LIST_SEPARATOR."image/gif".LIST_SEPARATOR."image/jpeg");
	define( "DOCS_TYPES" , "application/pdf".LIST_SEPARATOR."application/msword".LIST_SEPARATOR."application/vnd.ms-excel".LIST_SEPARATOR."application/vnd.ms-powerpoint".LIST_SEPARATOR."application/vnd.openxmlformats-officedocument.spre".LIST_SEPARATOR."application/vnd.openxmlformats-officedocument.word".LIST_SEPARATOR."application/vnd.openxmlformats-officedocument.pres" );
	define( "FILE_TYPES" , DOCS_TYPES.LIST_SEPARATOR.IMAGES_TYPES );
	define( "CLIENTES" , "" );
	define( "LET_USERS_CLIENT" , false );
	require_once dirname(__FILE__).'/db_configuration.php';
	require_once dirname(__FILE__).'/mail_configuration.php';
	
	define( "AREA_ADMON" , 1 );
	define( "AREA_COMERCIAL" , 4 );
	define( "CARGOS_GG" , 11);
	define( "CARGOS_GO" , 6);
	
	define( "PCT_IVA", 19 );
	define( "PCT_RETE_IVA", 15 );
	
	$meses=array(1=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$meses_abr=array(1=>"Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
	$weekdays = array(0=>"D",1=>"L",2=>"M",3=>"X",4=>"J",5=>"V",6=>"S");
	$porcentajes = array(25,50,75,100);
	$colores_recursos = array( "igual" => "bg-success" , "mayor"=> "bg-danger" , "menor" => "bg-warning" );
	$estados_hito = array(
	    "1" => "Pendiente Facturar",
	    "2" => "Aprobado para Facturar",
	    "3" => "Facturado",
	    "4" => "Pagado"
	);
	
	$plantilla_correo = array ( 
       "2" => array( 
           "subject" => ABR_TITLE." - @project@ - Hito #@hito@ Aprobado para facturar", 
           "message" => "Buen día,<br /><br />El hito #@hito@ del proyecto @project@   ha cambiado de estado a <i><b>Aprobado para facturar</b></i>, ingrese a SIGO para revisar el seguimiento dando clic <a href='@url@' target='_blank' >aquí</a>.<br /><br/>@datos_hito@<br /><br/>Realizado por @recurso@<br/><br/><img title='SIGO Bision' src='cid:img-logo' />"
       ),
	    "3" => array(
	        "subject" => ABR_TITLE." - @project@ - Hito #@hito@ Facturado",
	        "message" => "Buen día,<br /><br />El hito #@hito@ del proyecto @project@  ha cambiado de estado a <i><b>Facturado</b></i>, ingrese a SIGO para revisar el seguimiento dando clic <a href='@url@' target='_blank' >aquí</a>.<br /><br/>Realizado por @recurso@<br/><br/><img title='SIGO Bision' src='cid:img-logo' />"
	    ),
	    "4" => array(
	        "subject" => ABR_TITLE." - @project@ - Hito #@hito@ Pagado",
	        "message" => "Buen día,<br /><br />El hito #@hito@ del proyecto @project@ ha cambiado de estado a <i><b>Pagado</b></i>, ingrese a SIGO para revisar el seguimiento dando clic <a href='@url@' target='_blank' >aquí</a>.<br /><br/>Realizado por @recurso@<br/><br/><img title='SIGO Bision' src='cid:img-logo' />"
	    )
	    
	);
?>
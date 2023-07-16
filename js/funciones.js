function crearDialogo( ){
	var parametros = func_get_args();	
	var cuerpo_mensaje = '';
	var parametros_accion = new Array();
	var accion = parametros[2];
	for( var x = 3; x < parametros.length; x++ ){
		parametros_accion[x - 3] = parametros[x];
	}
	if( parametros_accion.length > 0 )
		accion = accion += '('+parametros_accion.join()+');';
	var myTitle = 'test';
	var myBodyHtml = 'body';
	$('#dialogTitle').html(myTitle);
   	$('#dialogBody').html(myBodyHtml);
	$('#dialogHeader').removeClass('alert-info');
	$('#dialogHeader').removeClass('alert-primary');
	$('#dialogHeader').removeClass('alert-secondary');
	$('#dialogHeader').removeClass('alert-danger');
	$('#dialogHeader').removeClass('alert-success');
	$('#dialogHeader').removeClass('alert-warning');
	$('#dialog-btn-opt1').removeClass('visible')	
   	switch( parametros[0].toLowerCase() ){
		case "confirmacion":
			$('#dialogTitle').html( 'Confirmación' );
   			$('#dialogBody').html( parametros[1] );
			$('#dialogHeader').addClass('alert-secondary');
			$('#dialog-btn-opt1').addClass('visible')
			$('#dialog-btn-opt1').html('Si');
			$('#dialog-btn-opt2').html('No');
			$("#dialog-btn-opt1").on("click", function(){
				eval(''+accion+';');				
				// $("#dialogGeneral").modal('hide');
			  });
			$("#dialog-btn-opt2").on("click", function(){
				$("#dialogGeneral").modal('hide');
			  });
		break;
		case "respuesta":			
			$('#dialogTitle').html( 'Respuesta' );
   			$('#dialogBody').html( parametros[1] );
			$('#dialogHeader').addClass('alert-success');
			$('#dialog-btn-opt1').addClass('invisible')
			$('#dialog-btn-opt2').html('Continuar');
			$("#dialog-btn-opt2").on("click", function(){	    
			    $("#dialogGeneral").modal('hide');
				eval(''+accion+';');				
			  });
		break;
		case "advertencia":
			$('#dialogTitle').html( 'Advertencia' );
   			$('#dialogBody').html( parametros[1] );
			$('#dialogHeader').addClass('alert-warning');
			$('#dialog-btn-opt1').addClass('invisible').removeClass('visible');
			$('#dialog-btn-opt2').html('Cerrar');
			$("#dialog-btn-opt2").on("click", function(){	    
			    $("#dialogGeneral").modal('hide');
				eval(''+accion+';');
			  });
		break;
		case "error":
			$('#dialogTitle').html( 'Error' );
   			$('#dialogBody').html( parametros[1] );
			$('#dialogHeader').addClass('alert-danger');
			$('#dialog-btn-opt1').addClass('invisible');
			$('#dialog-btn-opt2').html('Cerrar');
			$("#dialog-btn-opt2").on("click", function(){	    
			    $("#dialogGeneral").modal('hide');				
				window.location.reload();
			  });
		break;
		default:
			$('#dialogTitle').html( parametros[0] );
   			$('#dialogBody').html( parametros[1] );
			$('#dialogHeader').addClass('alert-info');
			$('#dialog-btn-opt1').addClass('invisible');
			$('#dialog-btn-opt2').html('Cerrar');
			$("#dialog-btn-opt2").on("click", function(){	    
			    $("#dialogGeneral").modal('hide');				
			  });
		break;
	}
	$('#dialogGeneral').modal('show');
	//$('#dialogGeneral').modal('toggle');	
	return false;		
}

function func_get_args ( ) {
	// Get the $arg_num'th argument that was passed to the function  
	// 
	// version: 1102.614
	// discuss at: http://phpjs.org/functions/func_get_arg    // +   original by: Brett Zamir (http://brett-zamir.me)
	// %        note 1: May not work in all JS implementations
	// *     example 1: function tmp_a() {return func_get_arg(1);}
	// *     example 1: tmp_a('a', 'b');
	// *     returns 1: { 'a', 'b'}
	if (!arguments.callee.caller) {
		try {
			throw new Error('Either you are using this in a browser which does not support the "caller" property or you are calling this from a global context');
			//return false;
		} catch (e) {            return false;
		}
	}
	
	return arguments.callee.caller.arguments;
}
function disableButton( obj ){	
	obj.disabled =true;	
}
<div class="row justify-content-md-center" >	
    <div class="col-md-4 mt-5 border border-secondary p-5 rounded shadow bg-light">
      <form method="post" name="form_login" id="form_login" onsubmit="return false;" class="form-signin">
        	<h1 class="h3 mb-3 font-weight-normal">Iniciar Sesión</h1>
        	<div class="form-group">
            	<label for="inputEmail" class="sr-only">Correo</label> 
            	<input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="Correo" required autofocus>
        	</div>
        	<div class="form-group"> 
            	<label for="inputPassword" class="sr-only">Contraseña</label> 
            	<input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Contraseña" required>
        	</div>
        	<div class="form-group">	
        		<button id="login" class="btn btn-lg btn-success btn-block" type="submit">Iniciar Sesión</button>        		
        	</div>
        </form>
    </div>
</div>

<script>
    $( "#login" ).click(function() {
      	xajax_iniciarSesion( xajax.getFormValues( this.form , true ) );
    });
</script>
<?php 
    function tieneHijos( $cod_hijo ){
        global $db;
        global $user;
        $sql = "select * from menu where  estado = 1 and cod_menu = ".$cod_hijo." and ( admin = 0 or cod_cargo is not null ) ";
        $hijos = $db->selectObjectsBySql($sql);
        $total = 0;
        foreach( $hijos as $hijo ){
            $cargos = explode(',', $hijo->cod_cargo);
            $areas = explode(',', $hijo->cod_area);
            if( $hijo->cod_cargo == "" &&  $hijo->cod_area == "" || ( array_search( $user->cod_cargo , $cargos ) !== false ||  array_search( $user->cod_area , $areas ) !== false ) ){
                $total++;
            }
        }
        return ( ( $total > 0 )?true:false);
    }
    function generaArbol( $cod_hijo ){
        global $db;
        global $_view;
        global $user;
        $sql = "select * from menu where estado = 1 and cod_menu = ".$cod_hijo."  and ( admin = 0 or cod_cargo is not null )  order by posicion";
        $hijos = $db->selectObjectsBySql($sql);
        //return count($hijos);
        $menu = "";
        if( count($hijos) > 0 ){
            foreach( $hijos as $hijo ){
                $cargos = explode(',', $hijo->cod_cargo);
                $areas = explode(',', $hijo->cod_area);
                if( $hijo->cod_cargo == "" &&  $hijo->cod_area == "" || ( array_search( $user->cod_cargo , $cargos ) !== false ||  array_search( $user->cod_area , $areas ) !== false ) ){
                    if( $cod_hijo != 0 ){ // subniveles
                        $menu.= '<a class="dropdown-item" onclick="window.location.href=\'index.php?view='. $hijo->accion.(($hijo->opcion!="")?"&action=".$hijo->opcion:"").'\'" href="index.php?view='. $hijo->accion.(($hijo->opcion!="")?"&action=".$hijo->opcion:"").'">'.$hijo->nombre.'</a>';
                    }else{
                        if( tieneHijos( $hijo->id_menu ) ){
                            $menu.= '<li class="nav-item dropdown" data-toggle="dropdown" >';
                            $menu.= '<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink_'.$hijo->id_menu.'" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$hijo->nombre.'</a>';
                            $menu.= '<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink_'.$hijo->id_menu.'">';
                            $menu.= generaArbol($hijo->id_menu);
                            $menu.= '</div>';
                            $menu.= '</li>';
                        }else{
                            if( !tieneHijos( $hijo->id_menu ) &&  $hijo->tipo != "texto" ){
                                $menu.= '<li class="nav-item '.( ( $hijo->accion == $_view )?"active":"").'">';
                                $menu.= '<a class="nav-link" href="index.php?view='.$hijo->accion.(($hijo->opcion!="")?"&action=".$hijo->opcion:"").'">'.$hijo->nombre.'</a>';
                                $menu.= '</li>';
                            }
                        }
                    }
                }
            }
            return $menu;
        }else{
            return "";
        }
        
    }
    
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="index.php" title="<?php echo TITLE; ?>">
    	<img src="<?php echo IMG_LOGO;?>" height="40" class="d-inline-block align-top" alt="" loading="lazy">		
	</a>
	<a class="navbar-brand" href="#"></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
    <?php if( $user ){		/*Si el usuario "SI" ha iniciado sesion*/ ?>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <?php echo generaArbol(0); ?>
        </ul>
    </div>
    <?php if( $_SESSION["SES_TAREA"] != "" ){?>
    <div class="navbar-brand alert alert-success bg-success text-light p-2" role="alert" id="div_crono_2" style='font-size:12px;'>Cargando...</div>
    <?php } ?>
    <div class="navbar-nav-icons ml-auto flex-row align-items-center">
        <div class="nav-item dropdown flex-row align-items-center">
            <a href="#" data-toggle="dropdown" class="nav-item nav-link dropdown-toggle user-action">        	
            	<span class="navbar-text"><?php echo $user->recurso;?></span>&nbsp;&nbsp;
            	<img src="<?php echo (($user->recurso!="")?$user->foto:IMG_PROFILE);?>" class="rounded-circle" style="width:24px" />
            </a>
            <div class="dropdown-menu">
            	<a href="#" class="dropdown-item" onclick="window.location.href='index.php?view=perfil'">Perfil</a>
            	<a href="#" class="dropdown-item" onclick="window.location.href='index.php?view=perfil&action=change'">Cambiar Contraseña</a>
            	
            	<?php 
            	if( $user->admin ){
                	$sql = "select * from menu where estado =1 and  admin = 1 order by posicion";
            		$hijos = $db->selectObjectsBySql($sql);
            		if( count( $hijos ) > 0 ){
            		    echo '<div class="divider dropdown-divider"></div>';
            		    foreach( $hijos as $hijo ){
            		        echo '<a class="dropdown-item" href="index.php?view='.$hijo->accion.'">'.$hijo->nombre.'</a>';
            		    }
            		}
            	}
        		?>
            	<!-- <a href="#" class="dropdown-item">Calendar</a>
                <a href="#" class="dropdown-item">Settings</a> -->
            	<div class="divider dropdown-divider"></div>
            	<a href="javascript:void(0);" onclick="xajax_cerrarSesion();" class="dropdown-item">Cerrar Sesi&oacute;n</a>
        	</div>
        </div>
    </div>
    <?php } ?>
</nav>

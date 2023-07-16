<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'nombre',
            'name' => "Nombre",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'tipo',
            'name' => "Tipo",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'cod_menu',
            'name' => "Menú Padre",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'accion',
            'name' => "Acción",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'opcion',
            'name' => "Opción",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'posicion',
            'name' => "Posición",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'cod_area',
            'name' => "Áreas",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'cod_cargo',
            'name' => "Cargos",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'admin',
            'name' => "Disponible para Administrador",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>
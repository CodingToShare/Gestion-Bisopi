<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'recurso',
            'name' => "Nombre",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'codigo_recurso',
            'name' => "Código Recurso",
            'required' => false,
            'disabled' => true
        )),
        array_map(utf8_encode ,array('id' => 'abreviatura',
            'name' => "Abreviatura",
            'required' => true
        )),
        array_map(utf8_encode ,array( 'id' => 'correo',
            'name' => "Correo",
            'required' => true
        )),
        array_map(utf8_encode ,array( 'id' => 'password',
            'name' => "Password",
            'required' => false,
            'type' => "password"
        )),
        array_map(utf8_encode ,array('id' => 'cod_cargo',
            'name' => "Cargo",
            'required' => true,
            'type' => "combo"
        )),
        array_map(utf8_encode ,array('id' => 'cod_area',
            'name' => "Área",
            'required' => false,
            'type' => "combo"
        )),
        array_map(utf8_encode ,array( 'id' => 'descripcion',
            'name' => "Descripción",
            'required' => false
        ))
        ,
        array_map(utf8_encode ,array( 'id' => 'cod_estado_recurso',
            'name' => "Estado",
            'required' => true,
            'type' => "combo"
        ))
        ,
        array_map(utf8_encode ,array( 'id' => 'cod_ciudad',
            'name' => "Ciudad",
            'required' => false,
            'type' => "combo"
        ))
        ,
        array_map(utf8_encode ,array( 'id' => 'telefono',
            'name' => "Teléfono",
            'required' => false
        ))
    );
    
    $json_fields = json_encode( $model );
?>
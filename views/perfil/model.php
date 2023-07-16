<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'recurso',
            'name' => "Recurso",
            'required' => true
        )),
        array_map(utf8_encode ,array( 'id' => 'correo',
            'name' => "Correo",
            'required' => true
        )),
        array_map(utf8_encode ,array( 'id' => 'telefono',
            'name' => "Teléfono",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'cod_cargo',
            'name' => "Cargo",
            'required' => true,
            'type' => "combo"
        )),
        array_map(utf8_encode ,array( 'id' => 'descripcion',
            'name' => "Descripción",
            'required' => false
        ))
        ,
        array_map(utf8_encode ,array( 'id' => 'cod_estado_recurso',
            'name' => "Estado",
            'required' => true
        ))
    );
    
    $json_fields = json_encode( $model );
?>
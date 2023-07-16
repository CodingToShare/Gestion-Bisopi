<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'estado_proyecto',
            'name' => "Estado Proyecto",
            'required' => true
        )),
        array_map(utf8_encode , array('id' => 'abreviatura',
            'name' => "Abreviatura",
            'required' => true
        )),
        array_map(utf8_encode , array('id' => 'filtra_tarea',
            'name' => "Filtra tarea",
            'required' => false,
            'type' => "combo",
            'default' => 0
        ))
    );
    
    $json_fields = json_encode( $model );
?>
<?php
    $model = array(
        array_map(utf8_encode ,array('id' => 'cod_responsable',
            'name' => "Responsable",
            'required' => true,
            'type' => "combo"
        )),
        array_map(utf8_encode ,array('id' => 'fecha',
            'name' => "Fecha Limite Permiso",
            'required' => true,
            'type' => "date"
        )),
        array_map(utf8_encode ,array('id' => 'cod_cierre',
            'name' => "Semana",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'estado_permiso',
            'name' => "Estado",
            'required' => false
        ))
    );
    
    $json_fields = json_encode( $model );
?>
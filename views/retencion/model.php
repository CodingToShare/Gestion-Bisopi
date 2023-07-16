<?php
$model = array(    
    array_map(utf8_encode ,array('id' => 'retencion',
        'name' => "% Retención",
        'required' => true
    )),
    array_map(utf8_encode ,array('id' => 'cod_pais',
        'name' => "País",
        'required' => true,
        'type' => "combo"
    )),
    array_map(utf8_encode ,array( 'id' => 'fecha_inicio',
        'name' => "Fecha Inicio Vigencia",
        'required' => true,
        'type' => "date"
    ))
    ,
    array_map(utf8_encode ,array( 'id' => 'fecha_fin',
        'name' => "Fecha Fin Vigencia",
        'required' => false,
        'type' => "date"
    ))
);

$json_fields = json_encode( $model );
?>
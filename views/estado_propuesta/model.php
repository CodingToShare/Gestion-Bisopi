<?php
$model = array(
    array_map(utf8_encode ,array('id' => 'estado_propuesta',
        'name' => "Estado Deal",
        'required' => true
    )),
    array_map(utf8_encode ,array('id' => 'abreviatura',
        'name' => "Abreviatura",
        'required' => false,
    )),
    array_map(utf8_encode ,array('id' => 'color',
        'name' => "Color",
        'required' => true,
        'type'=>'color'
    )),
    array_map(utf8_encode ,array('id' => 'orden',
        'name' => "Orden",
        'required' => true
    )),
    array_map(utf8_encode ,array('id' => 'visible',
        'name' => "Visible",
        'required' => false
    ))
);

$json_fields = json_encode( $model );
?>
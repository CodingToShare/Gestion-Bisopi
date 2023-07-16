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
            'name' => "Men� Padre",
            'required' => true
        )),
        array_map(utf8_encode ,array('id' => 'accion',
            'name' => "Acci�n",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'opcion',
            'name' => "Opci�n",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'posicion',
            'name' => "Posici�n",
            'required' => false
        )),
        array_map(utf8_encode ,array('id' => 'cod_area',
            'name' => "�reas",
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
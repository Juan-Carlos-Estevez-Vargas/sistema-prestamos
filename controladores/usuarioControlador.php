<?php
    if ($peticionAjax) require_once "../modelos/usuarioModelo.php";
    else require_once "./modelos/usuarioModelo.php";

    class usuarioControlador extends usuarioModelo {

        /**
         * Controlador encargado de manejar la inserción de usuarios al sistema.
         */
        public function agregar_usuario_controlador() {
            
        }
    }
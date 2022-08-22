<?php
    if ($peticionAjax) require_once "../modelos/usuarioModelo.php";
    else require_once "./modelos/usuarioModelo.php";

    class usuarioControlador extends usuarioModelo {

        /**
         * Controlador encargado de manejar la inserción de usuarios al sistema.
         */
        public function agregar_usuario_controlador() {

            /**
             * Utilizando la función para limpiar los campos del formulario 'user-new-view.php'
             * de posible inyección SQL y almacenando el valor en variables.
             */
            $dni = mainModel::limpiar_cadena($_POST["usuario_dni_reg"]);
            $nombre = mainModel::limpiar_cadena($_POST["usuario_nombre_reg"]);
            $apellido = mainModel::limpiar_cadena($_POST["usuario_apellido_reg"]);
            $telefono = mainModel::limpiar_cadena($_POST["usuario_telefono_reg"]);
            $direccion = mainModel::limpiar_cadena($_POST["usuario_direccion_reg"]);
            $usuario = mainModel::limpiar_cadena($_POST["usuario_usuario_reg"]);
            $email = mainModel::limpiar_cadena($_POST["usuario_email_reg"]);
            $clave1 = mainModel::limpiar_cadena($_POST["usuario_clave_1_reg"]);
            $clave2 = mainModel::limpiar_cadena($_POST["usuario_clave_2_reg"]);
            $privilegio = mainModel::limpiar_cadena($_POST["usuario_privilegio_reg"]);

            /**
             * Comprobando que los datos requeridos del formulario 'user-new-view.php' 
             * no estén vacíos
             */ 
            if ( $dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $clave1 == "" || $clave2 == "" ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado.",
                    "Texto" => "No has llenado todos los campos requeridos.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
    }
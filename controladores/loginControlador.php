<?php
    if ($peticionAjax) require_once "../modelos/loginModelo.php";
    else require_once "./modelos/loginModelo.php";

    class loginControlador extends loginModelo {
        
        /**
         * Controlador encargado de iniciar sesión en el sistema.
         */
        public function iniciar_sesion_controlador() {

            /**
             * Utilizando la función para limpiar los campos del formulario 'login-view.php'
             * de posible inyección SQL y almacenando el valor en variables.
             */
            $usuario = mainModel::limpiarCadena($_POST["usuario_login"]);
            $clave = mainModel::limpiarCadena($_POST["clave_login"]);

            /**
             * Comprobando que los datos requeridos del formulario 'login-view.php' 
             * no estén vacíos
             */ 
            if ( $usuario == "" || $clave == "" ) {
               echo "<script>
                        Swal.fire({
                            title: 'Ocurrió un error inesperado',
                            text: 'No has llenado todos los campos solicitados',
                            type: 'error',
                            confirmButtonText 'Aceptar'
                        });
                    </script>";
            }

            /**
             * Verificando la integridad de los datos, es decir, validando el tipo y tamaño de caracteres
             * perimitidos en el formulario.
             */ 
            if ( mainModel::verificar_datos("[a-zA-Z0-9]{10,35}", $usuario) ) {
                echo "<script>
                        Swal.fire({
                            title: 'Ocurrió un error inesperado',
                            text: 'El campo NOMBRE DE USUARIO no concide con el formato solicitado',
                            type: 'error',
                            confirmButtonText 'Aceptar'
                        });
                    </script>";
            }

            if ( mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave) ) {
                echo "<script>
                        Swal.fire({
                            title: 'Ocurrió un error inesperado',
                            text: 'La CLAVE o CONTRASEÑA no concide con el formato solicitado',
                            type: 'error',
                            confirmButtonText 'Aceptar'
                        });
                    </script>";
            }

            /**
             * Encriptación de la contraseña.
             */
            $clave = mainModel::encryption();

            $datos_login = [
                "usuario" => $usuario,
                "clave" => $clave
            ];
        }

    }
<?php
    /** Incluyendo el modelo con las operaciones CRUD. */
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
            $usuario = mainModel::limpiar_cadena($_POST["usuario_login"]);
            $clave = mainModel::limpiar_cadena($_POST["clave_login"]);

            /**
             * Comprobando que los datos requeridos del formulario 'login-view.php' 
             * no estén vacíos, en caso de estarlo se envía una alerta de error.
             */ 
            if ( $usuario == "" || $clave == "" ) {
               echo "<script>
                        Swal.fire({
                            title: 'Ocurrió un error inesperado',
                            text: 'No has llenado todos los campos solicitados',
                            type: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";
                exit();
            }

            /**
             * Verificando la integridad de los datos, es decir, validando el tipo y tamaño de caracteres
             * perimitidos en el formulario.
             */ 
            if ( mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario) ) {
                echo "<script>
                        Swal.fire({
                            title: 'Ocurrió un error inesperado',
                            text: 'El campo NOMBRE DE USUARIO no concide con el formato solicitado',
                            type: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";
                exit();
            }

            if ( mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave) ) {
                echo "<script>
                        Swal.fire({
                            title: 'Ocurrió un error inesperado',
                            text: 'La CLAVE o CONTRASEÑA no concide con el formato solicitado',
                            type: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";
                exit();
            }

            /**
             * Encriptación de la contraseña.
             */
            $clave_encriptada = mainModel::encryption($clave);

            /** Datos para iniciar la sesión en el sistema. */
            $datos_login = [
                "usuario" => $usuario,
                "clave" => $clave_encriptada
            ];

            /** Iniciando sesión en el sistema utilizando variables de sesión. */
            $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);
            if ( $datos_cuenta->rowCount() == 1 ) {
                $row = $datos_cuenta->fetch();

                /** Creando variables de sesión. */
                session_start(['name'=>'SPM']);
                $_SESSION["id_spm"] = $row["usuario_id"];
                $_SESSION["nombre_spm"] = $row["usuario_nombre"];
                $_SESSION["apellido_spm"] = $row["usuario_apellido"];
                $_SESSION["usuario_spm"] = $row["usuario_usuario"];
                $_SESSION["privilegio_spm"] = $row["usuario_privilegio"];
                $_SESSION["token_spm"] = md5(uniqid(mt_rand(), true));

                return header("Location: ".SERVERURL."home/");
            } else {
                echo "<script>
                        Swal.fire({
                            title: 'Ocurrió un error inesperado',
                            text: 'El USUARIO o CLAVE son incorrectos',
                            type: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";
                exit();
            }
        } /** Fin de la funcionalidad de inicio de sesión. */

        /**
         * Controlador encargado de forzar el cierre de sesión en el sistema.
         */
        public function forzar_cierre_sesion_controlador() {
            session_unset();
            session_destroy();
            if ( headers_sent() ) return "<script> window.location.href = '".SERVERURL."login/'; </script>";
            else header("Location: ".SERVERURL."login/");
        }

        /**
         * Controlador encargado de cerrar la sesión cuando el usuario lo indique.
         */
        public function cerrar_sesion_controlador() {
            session_start(['name'=>'SPM']);
            $token = mainModel::decryption($_POST["token"]);
            $usuario = mainModel::decryption($_POST["usuario"]);

            if ( $token == $_SESSION["token_spm"] && $usuario == $_SESSION["usuario_spm"] ) {
                session_unset();
                session_destroy();
                $alerta = [
                    "Alerta" => "redireccionar",
                    "URL" => SERVERURL."login/"
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se pudo cerrar la sesión en el sistema",
                    "Tipo" => "error"
                ];
            }
            echo json_encode($alerta);
        } /** Fin del controlador. */

    }
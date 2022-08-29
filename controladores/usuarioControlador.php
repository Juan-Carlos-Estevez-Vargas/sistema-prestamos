<?php
    if ($peticionAjax) require_once "../modelos/usuarioModelo.php";
    else require_once "./modelos/usuarioModelo.php";

    class usuarioControlador extends usuarioModelo {

        /**
         * Controlador encargado de manejar la inserción de usuarios al sistema.
         */
        public function agregar_usuario_controlador() {

            # ---------- Validación de datos pre inserción. ------------- #

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
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No has llenado todos los campos requeridos",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /**
             * Verificando la integridad de los datos, es decir, validando el tipo y tamaño de caracteres
             * perimitidos en el formulario.
             */ 
            if ( mainModel::verificar_datos("[0-9-]{10,20}", $dni) ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El campo DNI no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ( mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}", $nombre) ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El campo NOMBRE no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ( mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}", $apellido) ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El campo APELLIDO no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ( $telefono != "" ) {
                if ( mainModel::verificar_datos("[0-9()+]{8,20}", $telefono) ) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El campo TELÉFONO no coincide con el formato solicitado",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }  
            }

            if ( $direccion != "" ) {
                if ( mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion) ) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El campo DIRECCIÓN no coincide con el formato solicitado",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }  
            }

            if ( mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $usuario) ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El campo NOMBRE DE USUARIO no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ( mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave2) ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Las CLAVES no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /**
             * Comprobando los campos UNIQUE de la base de datos, es decir, que no existan repetidos.
             */
            $check_dni = mainModel::ejecutar_consulta_simple("SELECT usuario_dni FROM usuario WHERE usuario_dni = '$dni'");
            if ( $check_dni->rowCount() > 0 ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El DNI ingresado ya se encuentra registrado en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
            if ( $check_usuario->rowCount() > 0 ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El NOMBRE DE USUARIO ingresado ya se encuentra registrado en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            if ( $email != "" ){
                if ( filter_var($email, FILTER_VALIDATE_EMAIL ) ) {
                    $check_email = mainModel::ejecutar_consulta_simple("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
                    if ( $check_email->rowCount() > 0 ) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "El EMAIL ingresado ya se encuentra registrado en el sistema",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Ha ingresado un correo no válido",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /** Comprobando la contraseña y repetir contraseña. */
            if ( $clave1 != $clave2 ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Las CONTRASEÑAS no coinciden",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            } else $clave = mainModel::encryption($clave1);

            /** Comprobando privilegio */
            if ( $privilegio < 1 || $privilegio > 3 ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El PRIVILEGIO seleccionado no es válido",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            # ---------- Fin de la validación de datos post inserción. ------------- #

            $datos_usuario_reg = [
                "DNI" => $dni,
                "nombre" => $nombre,
                "apellido" => $apellido,
                "telefono" => $telefono,
                "direccion" => $direccion,
                "email" => $email,
                "usuario" => $usuario,
                "clave" => $clave,
                "estado" => "Activa",
                "privilegio" => $privilegio
            ];

            $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

            if ( $agregar_usuario->rowCount() == 1 ) {
                $alerta = [
                    "Alerta" => "limpiar",
                    "Titulo" => "Usuario registrado correctamente",
                    "Texto" => "Los datos del usuario han sido registrados con éxito",
                    "Tipo" => "success"
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No hemos podido registrar el usuario",
                    "Tipo" => "error"
                ];
            } echo json_encode($alerta);
            
        } /** Fin del controlador  */

        /**
         * Controlador encargado de paginar o listar los usuarios registrados en
         * el sistema
         */
        public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda) {
            $pagina = mainModel::limpiar_cadena($pagina);
            $registros = mainModel::limpiar_cadena($registros);
            $privilegio = mainModel::limpiar_cadena($privilegio);
            $id = mainModel::limpiar_cadena($id);
            $url = mainModel::limpiar_cadena($url);
            $url = SERVERURL.$url."/";
            $busqueda = mainModel::limpiar_cadena($busqueda);
            $tabla = "";
            $pagina = ( isset($pagina) && $pagina > 0 ) ? (int) $pagina : 1;
            $inicio = ( $pagina > 0 ) ? (($pagina * $registros) - $registros) : 0;
        } /** Fin del controlador  */
    }
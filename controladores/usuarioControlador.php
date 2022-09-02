<?php
    /** Incluyendo el modelo con las operaciones CRUD. */
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

            /** Comprobando la contraseña y repetir contraseña (que sean iguales). */
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

            /** Comprobando privilegio (que esté dentro del rango permitido)*/
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
            # ---------- Fin de la validación de datos pre inserción. ------------- #

            /** 
             * Array de datos con la información a insertar. 
             */
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

            /**
             * Registrando el usuario en el sistema.
             */
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
         * el sistema.
         */
        public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda) {
            
            /** 
             * Limpiando los datos de posible inyección SQL. 
             */
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

            /**
             * Si viene definido un parámetro de búsqueda de registros.
             */
            if ( isset($busqueda) && $busqueda != "" ) {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id != '$id' 
                            AND usuario_id != '1') AND (usuario_dni LIKE '%$busqueda%' 
                            OR usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%'
                            OR usuario_telefono LIKE '%$busqueda%' OR usuario_email LIKE '%$busqueda%'
                            OR usuario_usuario LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC 
                            LIMIT $inicio, $registros";
            } else {
                $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id != '$id' 
                            AND usuario_id != '1' ORDER BY usuario_nombre ASC LIMIT $inicio, $registros";
            }

            /** 
             * Ejecutando la consulta sql.
             */
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();

            /** Total de filas encontradas. */
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            /** Calculando el número de páginas. */
            $n_paginas = ceil($total / $registros);

            /**
             * Listando los registros en el sistema.
             */
            $tabla .= '<div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                                <tr class="text-center roboto-medium">
                                    <th>#</th>
                                    <th>DNI</th>
                                    <th>NOMBRE</th>
                                    <th>TELÉFONO</th>
                                    <th>USUARIO</th>
                                    <th>EMAIL</th>
                                    <th>ACTUALIZAR</th>
                                    <th>ELIMINAR</th>
                                </tr>
                            </thead>
                            <tbody>';
            
            if ( $total >= 1 && $pagina <= $n_paginas ) {
                $contador = $inicio + 1;
                $reg_inicio = $inicio + 1;
                foreach( $datos as $rows ) {
                    $tabla.='<tr class="text-center" >
                                <td>'.$contador.'</td>
                                <td>'.$rows['usuario_dni'].'</td>
                                <td>'.$rows['usuario_nombre'].' '.$rows['usuario_apellido'].'</td>
                                <td>'.$rows['usuario_telefono'].'</td>
                                <td>'.$rows['usuario_usuario'].'</td>
                                <td>'.$rows['usuario_email'].'</td>
                                <td>
                                    <a href="'.SERVERURL.'user-update/'.mainModel::encryption($rows["usuario_id"]).'/" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>	
                                    </a>
                                </td>
                                <td>
                                    <form class="FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php" method="POST" data-form="delete" autocomplete="off">
                                        <input type="hidden" name="usuario_id_del" value="'.mainModel::encryption($rows["usuario_id"]).'" />
                                        <button type="submit" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>';
                    $contador ++;
                }
                $reg_final = $contador - 1;
            } else {
                if ( $total >= 1 ) {
                    $tabla.='<tr class="text-center" >
                                <td colspan=9>
                                    <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga click acá para recargar el listado</a>
                                </td>
                            </tr>';
                } else {
                    $tabla.='<tr class="text-center" >
                                <td colspan=9>No hay registros en el sistema</td>
                            </tr>';
                }
            }
            
            $tabla .= '         </tbody>
                            </table>
                        </div>';
            
            if ( $total >= 1 && $pagina <= $n_paginas ) {
                $tabla.="<p class='text-right'>Mostrando usuario ".$reg_inicio." al ".$reg_final." de un total de ".$total." </p>";
                # $tabla .= mainModel::paginador_tablas($pagina, $n_paginas, $url, 5);
            }

            return $tabla;
        } /** Fin del controlador  */

        /**
         * Controlador encargado de eliminar usuarios del sistema.
         */
        public function eliminar_usuario_controlador() {

            /** Recibiendo el id a eliminar */
            $id = mainModel::decryption($_POST["usuario_id_del"]);
            $id = mainModel::limpiar_cadena($id);

            /** El id == 1 no se puede eliminar porque es el super administrador. */
            if ($id == 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No podemos eliminar el usuario principal del sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /** Comprobando el usuario en la base de datos (que exista). */
            $check_usuario = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM usuario WHERE usuario_id = '$id'");
            if ( $check_usuario->rowCount() <= 0 ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El usuario que intenta eliminar no existe en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /** Comprobando los préstamos asociados al usuario a eliminar. */
            $check_prestamos = mainModel::ejecutar_consulta_simple("SELECT usuario_id FROM prestamo WHERE usuario_id = '$id' LIMIT 1");
            if ( $check_prestamos->rowCount() > 0 ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No podemos eliminar el usuario seleccionado puesto que tiene préstamos asociados, recomendamos deshabilitar el usuario si ya no será utilizado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /** Comprobando el privilegio del usuario que desea ejecutar la eliminación de un registro */
            session_start(["name"=>"SPM"]);
            if ( $_SESSION["privilegio_spm"] != 1 ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No tienes los permisos necesarios para ejecutar esta acción",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /** Eliminando usuario del sistema luego de todas las comprobaciones */
            $eliminar_usuario = usuarioModelo::eliminar_usuario_modelo($id);
            if ( $eliminar_usuario->rowCount() == 1 ) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Usuario eliminado",
                    "Texto" => "El usuario ha sido eliminado del sistema exitosamente",
                    "Tipo" => "success"
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se pudo eliminar el usuario, intente nuevamente",
                    "Tipo" => "error"
                ];
            }
            echo json_encode($alerta);
        } /** Fin del controlador  */

        /**
         * Controlador encargado de validar los datos del usuario logueado en el sistema.
         */
        public function datos_usuario_controlador($tipo, $id) {
            $tipo = mainModel::limpiar_cadena($tipo);
            $id = mainModel::decryption($id);
            $id = mainModel::limpiar_cadena($id);
            return usuarioModelo::datos_usuario_modelo($tipo, $id);
        } /** Fin del controlador  */
    }
<!-- 
    Este archivo se encarga de procesar las operaciones CRUD de los usuarios 
    por medio de peticiones AJAX.
 -->
<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    /** Si viene definido el dni y el id del usuario a manipular. */
    if ( isset($_POST["usuario_dni_reg"]) || isset($_POST["usuario_id_del"]) ) {

        /**
         * Incluyendo el controlador
         */ 
        require_once "../controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();

        /**
         * Agregando un usuario.
         */
        if ( isset($_POST["usuario_dni_reg"]) && isset($_POST["usuario_nombre_reg"]) ) {
            echo $ins_usuario->agregar_usuario_controlador();
        }

        /**
         * Eliminando un usuario.
         */
        if ( isset($_POST["usuario_id_del"]) ) {
            echo $ins_usuario->eliminar_usuario_controlador();
        }
    } else { /** Vaciando y destruyendo la sesión. */
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
    }
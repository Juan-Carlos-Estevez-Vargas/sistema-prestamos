<!-- 
    Este archivo se encarga de procesar el cierre de sesión del usuario
    logueado por medio de peticiones AJAX.
-->
<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    /** Si viene definido el token de sesión y el usuario se cerrará ña sesión. */
    if ( isset($_POST["token"]) && isset($_POST["usuario"]) ) {
        
        /** Instancia del controlador */
        require_once "../controladores/loginControlador.php";
        $instancia_login = new loginControlador();
        echo $instancia_login->cerrar_sesion_controlador();
        
    } else { /** Si no vienen definidas se vacía y destruye la sesión */
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
    }
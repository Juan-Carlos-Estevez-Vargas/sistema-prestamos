<?php
    $peticionAjax = true;
    require_once "../config/app.php";

    if ( isset($_POST["token"]) && isset($_POST["usuario"]) ) {
        
        /** Instancia del controlador */
        require_once "../controladores/loginControlador.php";
        $instancia_login = new loginControlador();
        echo $instancia_login->cerrar_sesion_controlador();
    } else {
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
    }
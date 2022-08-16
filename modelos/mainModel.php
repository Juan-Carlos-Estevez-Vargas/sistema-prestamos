<?php
    if ( $peticionAjax ) {
        require_once "../config/SERVER.php";
    } else {
        require_once "./config/SERVER.php";
    }

    class mainModel {

        /**
         * Modelo encargado de conectar a la base de datos.
         */
        protected static function conectar() {
            $conexion = new PDO(SGBD, USER, PASS);
            $conexion->exec("SET CHARACTER SET utf8");
            return $conexion;
        }

        /**
         * Función para realizar o ejecutar consultas SQL simples.
         */
        protected static function ejecutar_consulta_simple($consulta) {
            $sql = self::conectar()->prepare($consulta);
            $sql->execute();
            return $sql;
        }

        /**
         * Se encarga de encriptar textos planos (parámetros, id's, etc).
         */
        public function encryption($string) {
            $output = FALSE;
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_ID), 0, 16);
            $output = openssl_encrypt($string, METHOD, $key, 0, $iv);
            $output = base64_encode($output);
            return $output;
        }

        /**
         * Se encarga de desencriptar textos planos (parámetros, id's, etc).
         */
        protected static function decryption($string) {
            $key = hash('sha256', SECRET_KEY);
            $iv = substr(hash('sha256', SECRET_ID), 0, 16);
            $output = openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
            return $output;
        }

        /**
         * Genera códigos aleatorios.
         */
        protected static function generar_codigo_aleatorio($letra, $longitud, $numero) {
            for ( $i = 1; $i <= $longitud; $i++ ) {
                $aleatorio = rand(0, 9);
                $letra .= $aleatorio;
            }
            return $letra."-".$numero;
        }

    }
<?php
    require_once "mainModel.php";

    class usuarioModelo extends mainModel {

        /**
         * Se encarga de agregar usuarios a la base de datos del sistema.
         */
        protected static function agregar_usuario_modelo($datos) {
            $sql = mainModel::conectar()->prepare(
                "INSERT INTO usuario (usuario_dni, usuario_nombre, usuario_apellido, usuario_telefono, 
                    usuario_direccion, usuario_email, usuario_usuario, usuario_clave, usuario_estado, usuario_privilegio) 
                VALUES (:DNI, :nombre, :apellido, :telefono, :direccion, :email, :usuario, :clave, :estado, :privilegio)");
            
            $sql->bindParam(":DNI", $datos["DNI"]);
            $sql->bindParam(":nombre", $datos["nombre"]);
            $sql->bindParam(":apellido", $datos["apellido"]);
            $sql->bindParam(":telefono", $datos["telefono"]);
            $sql->bindParam(":direccion", $datos["direccion"]);
            $sql->bindParam(":email", $datos["email"]);
            $sql->bindParam(":usuario", $datos["usuario"]);
            $sql->bindParam(":clave", $datos["clave"]);
            $sql->bindParam(":estado", $datos["estado"]);
            $sql->bindParam(":privilegio", $datos["privilegio"]);

            $sql->execute();
            return $sql;
        } /** Fin modelo agregar usuario */

        /**
         * Se encarga de eliminar un registro del sistema.
         */
        protected static function eliminar_usuario_modelo($id) {
            $sql = mainModel::conectar()->prepare("DELETE FROM usuario WHERE usuario_id = :id");
            $sql->bindParam(":id", $id);
            $sql->execute();
            return $sql;
        } /** Fin modelo eliminar usuario */

        /**
         * Se encarga de validar los datos del usuario logueado en el sistema.
         */
        protected static function datos_usuario_modelo($tipo, $id) {

            if ( $tipo == "Unico" ) {
                $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
                $sql->bindParam(":id", $id);
            } elseif ( $tipo == "Conteo" ) {
                $sql = mainModel::conectar()->prepare("SELECT usuario_id FROM usuario WHERE usuario_id != '1'");
            }
            
            $sql->execute();
            return $sql;
        } /** Fin modelo eliminar usuario */

        /**
         * Se encarga de validar los datos del usuario logueado en el sistema.
         */
        protected static function actualizar_usuario_modelo($datos) {

            $sql = mainModel::conectar()->prepare(
                "UPDATE usuario SET usuario_dni = :dni, usuario_nombre = :nombre, 
                    usuario_apellido = :apellido, usuario_telefono = :telefono,
                    usuario_direccion = :direccion, usuario_email = :email,
                    usuario_usuario = :usuario, usuario_clave = :clave, usuario_estado = :estado.
                    usuario_privilegio = :privilegio WHERE usuario_id = :id");

            $sql->bindParam(":dni", $datos["dni"]);
            $sql->bindParam(":nombre", $datos["nombre"]);
            $sql->bindParam(":apellido", $datos["apellido"]);
            $sql->bindParam(":telefono", $datos["telefono"]);
            $sql->bindParam(":direccion", $datos["direccion"]);
            $sql->bindParam(":email", $datos["email"]);
            $sql->bindParam(":usuario", $datos["usuario"]);
            $sql->bindParam(":clave", $datos["clave"]);
            $sql->bindParam(":estado", $datos["estado"]);
            $sql->bindParam(":privilegio", $datos["privilegio"]);
            $sql->bindParam(":id", $datos["id"]);

            $sql->execute();
            return $sql;

        } /** Fin modelo eliminar usuario */
    }
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
         * Se enecarga de eliminar un registro del sistema.
         */
        protected static function eliminar_usuario_modelo($id) {
            $sql = mainModel::conectar()->prepare("DELETE FROM usuario WHERE usuario_id = :id");
            $sql->bindParam(":id", $id);
            $sql->execute();
            return $sql;
        } /** Fin modelo eliminar usuario */
    }
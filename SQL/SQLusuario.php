<?php

/**
 * Description of SQLusuario
 *
 * @author mauro
 */
class SQLusuario {

    //put your code here
    public static function Actualizar_avatar($BaseDatos,$id_usuario, $datos) {
        try {

            $sql = "UPDATE " . tuser . " SET avatar='".$datos."' WHERE id=" . $id_usuario;
            $result = $BaseDatos->mandar($sql);
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    public static function Marcar_favorita($BaseDatos,$id_usuario, $datos) {
        try {
            $sql = "INSERT INTO " . tfavoritos . " (user_id, publicacion_id) value (".$id_usuario.", ".$datos.");";
            $result = $BaseDatos->mandar($sql);
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function BuscarById($BaseDatos,$id_usuario) {
        try {
            $sql = "SELECT * FROM " . tuser . " where id='" . $id_usuario . "' LIMIT 1";
            $result = $BaseDatos->mandar($sql);
            $cont = 0;
            $retorno = [];
            if ($result->num_rows > 0) {
                return $result->fetch_array(MYSQLI_ASSOC);
            } else {
                return [];
            }
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

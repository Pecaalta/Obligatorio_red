<?php

/**
 * Description of SQLfavoritos
 *
 * @author mauro
 */

class SQLfavoritos {

    public static function ListarByUser($BaseDatos,$id) {
        try {
            $sql = "SELECT p.title, p.id FROM " . tPublicacion . " p," . tfavoritos . " f where f.publicacion_id = p.id and f.user_id='" . $id . "'";
            $result = $BaseDatos->mandar($sql);
            $cont = 0;
            $retorno = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
                        $retorno[$cont][$key] = $value;
                    }
                    $cont++;
                }
                return $retorno;
            } else {
                return [];
            }
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

<?php

/**
 * Description of SQLpublicaciones-categorias
 *
 * @author mauro
 */
class SQLpublicaciones_categorias {

    public static function ListarById($BaseDatos,$id) {
        try {
            $sql = "SELECT * FROM " . tpublicacion_categoria . " where publicacion_id='" . $id . "'";
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

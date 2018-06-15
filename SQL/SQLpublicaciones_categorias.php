<?php

/**
 * Description of SQLpublicacion_categoria
 *
 * @author mauro
 */
class SQLpublicacion_categoria {

    public static function Actualiza($BaseDatos, $id, $categoria) {
        try {
            foreach ($categoria as $cat) {
                $Deletevalue[] = "categoria_id <> " . $cat;
            }
            foreach ($categoria as $cat) {
                $Insertvalue[] = "(" . $id . ", '" . $cat . "')";
            }
            $sql = "DELETE FROM " . tpublicacion_categoria . " WHERE publicacion_id=" . $id . " and " . join(" and ", $Deletevalue) . ";";
            $result = $BaseDatos->mandar($sql);

            $sql = "INSERT IGNORE INTO " . tpublicacion_categoria . " (publicacion_id,categoria_id) VALUES " . join(", ", $Insertvalue) . " ;";
            $result = $BaseDatos->mandar($sql);

            return true;

            $conn->close();
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function Agregar($BaseDatos, $id, $categoria) {
        try {
            if (sizeof($categoria)) {
                foreach ($categoria as $cat) {
                    $value[] = "(" . $id . ", '" . $cat . "')";
                }
                $sql = "INSERT IGNORE INTO " . tpublicacion_categoria . " (publicacion_id,categoria_id) VALUES " . join(", ", $value) . " ;";
                $result = $BaseDatos->mandar($sql);
            }
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function ListarById($BaseDatos, $id) {
        try {
            $sql = "SELECT name FROM " . tCategoria . " c," . tpublicacion_categoria . " p where p.categoria_id = c.id and p.publicacion_id='" . $id . "'";
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
            $conn->close();
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

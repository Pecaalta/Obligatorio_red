<?php

/**
 * Description of SQLarchivos
 *
 * @author mauro
 */
class SQLarchivos {

    public static function Actualiza($BaseDatos,$id, $categoria) {
        try {
            foreach ($archivos as $archivo) {
                $Deletevalue[] = "url <> '" . $archivo . "'";
            }
            foreach ($archivos as $archivo) {
                $Insertvalue[] = "(" . $id . ", '" . $archivo . "')";
            }

            $sql = "DELETE FROM " . tadjuntos . " WHERE idpublicacion=" . $id . " and " . join(" and ", $Deletevalue) . ";";
            $result = $conn->query($sql);

            $sql = "INSERT IGNORE INTO " . tadjuntos . " (idpublicacion,url) VALUES " . join(", ", $Insertvalue) . " ;";
            $result = $conn->query($sql);

            return true;

            $conn->close();
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function Agregar($BaseDatos,$id, $archivos) {
        try {
            $value = [];
            foreach ($archivos as $archivo) {
                $value[] = "(" . $id . ", '" . $archivo . "')";
            }
            $sql = "INSERT IGNORE INTO " . tadjuntos . " (idpublicacion,url) VALUES " . join(", ", $value) . " ;";
            $result = $BaseDatos->mandar($sql);

        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    //put your code here
    public static function ListarById($BaseDatos,$id) {
        try {
            $sql = "SELECT * FROM " . tadjuntos . " where idpublicacion='" . $id . "'";
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

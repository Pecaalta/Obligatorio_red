<?php

/**
 * Description of SQLcategorias
 *
 * @author mauro
 */
class SQLcategorias {

    public static function crear($BaseDatos,$datos) {
        try {
            $sql = "SELECT * FROM " . tCategoria;
            $result = $BaseDatos->mandar($sql);
            $cont = 0;
            $temp = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $temp[$cont] = $row["name"];
                    $cont++;
                }
            }
            $retorno = [];
            foreach ($datos as $i ){
                if (!in_array($i,$temp)){
                    $retorno[] = $i;
                } else {
                    $Ignore[] = $i;
                }
            }
            $datos = $retorno;
            $sql = "INSERT IGNORE INTO " . tCategoria . " (name) VALUES ('" . join("'), ('", $datos) . "');";
            $BaseDatos->mandar($sql);
            if (sizeof($Ignore) > 0)
                return "Los registros ". join(", ", $Ignore) . " fueron ignorados por q ya existian";
            else
                return "Todo salio bien";
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }
    
    //put your code here
    public static function Listar($BaseDatos) {
        try {
            $sql = "SELECT * FROM " . tCategoria;
            $result = $BaseDatos->mandar($sql);
            $cont = 0;
            $retorno = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $value) {
                       // echo var_dump($row)."   ";
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

    public static function Cont($BaseDatos) {
        try {
            $sql = "SELECT COUNT(*) AS cont FROM " . tCategoria;
            $result = $BaseDatos->mandar($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                return $row['cont'];
            } else {
                return [];
            }
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

}

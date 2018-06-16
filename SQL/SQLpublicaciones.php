<?php

/**
 * Description of SQLpublicacion_categoria
 *
 * @author mauro
 */
class SQLpublicaciones {

    public static function Publicar($BaseDatos,$id, $datos) {
        try {
            $sql = "UPDATE " . tPublicacion . " SET state='" . $datos . "' WHERE id=" . $id;
            $result = $BaseDatos->mandar($sql);
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function Actualiza($BaseDatos,$id, $datos) {
        try {
            foreach ($datos as $key => $value) {
                $array[] = "" . $key . "='" . $value . "'";
            }
            $sql = "UPDATE " . tPublicacion . " SET " . join(", ", $array) . " WHERE id=" . $id;
            $result = $BaseDatos->mandar($sql);
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function crear($BaseDatos,$datos) {
        try {
            $sql = "INSERT INTO " . tPublicacion . " (" . join(", ", array_keys($datos)) . ") VALUES ('" . join("', '", array_values($datos)) . "');";
            return $BaseDatos->create($sql);
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function actualizar($BaseDatos,$id_usuario, $id_publicacion, $datos) {
        return "sad";
    }

    public static function Listar($BaseDatos,$para) {
        try {
            if (!isset($para["_limit"])) {
                $_limit = limite;
            } else {
                $_limit = $para["_limit"];
            }
            if (!isset($para["_page"])) {
                if (!isset($para["_start"])) {
                    if (!isset($para["_end"])) {
                        $_end = $_limit;
                        $_start = 0;
                    } else {
                        $_end = $para["_end"];
                        $_start = $para["_end"] - $_limit;
                    }
                } else {
                    if (!isset($para["_end"])) {
                        $_end = $para["_start"] + $_limit;
                    } else {
                        $_end = $para["_end"];
                    }
                    $_start = $para["_start"];
                }
            } else {
                $_start = $para["_page"] * $_limit;
            }
            $_start = ($_start < 0) ? 0 : $_start;
            $WHERE = ["cant" => [], "otros" => [], "View" => "title, author"];
            foreach ($para as $key => $value) {
                if (!in_array($key, ["_limit", "_page", "_end", "_start", "q"])) {
                    if (strrpos($key, "_ne")) {
                        $filer = substr($key, 0, -3);
                        if ($filer !== "") {
                            $WHERE["cant"][$filer]["_ne"] = $value;
                        }
                    }if (strrpos($key, "_gte")) {
                        $filer = substr($key, 0, -4);
                        if ($filer !== "") {
                            $WHERE["cant"][$filer]["_gte"] = $value;
                        }
                    } else if (strrpos($key, "_lte")) {
                        $filer = substr($key, 0, -4);
                        if ($filer !== "") {
                            $WHERE["cant"][$filer]["_lte"] = $value;
                        }
                    } else if (strrpos($key, "_like")) {
                        $filer = substr($key, 0, -5);
                        if ($filer !== "") {
                            $WHERE[$filer]["_like"] = str_replace(["___", "__"], ['%', '_'], $value);
                        }
                    } else if (strrpos($key, "q")) {
                        $WHERE["q"] = $value;
                    } else if (strrpos($key, "_sort")) {
                        $ORDER[] = str_replace(",", " ", $value);
                    } else if ($key === "View") {
                        $WHERE["View"] = $value;
                    } else {
                        if (!isset($WHERE["otros"])) $WHERE["otros"] = [];
                        if (gettype($key) == 'string' and gettype($value) == 'string' ){
                        $WHERE["otros"][] = $key . " = " . $value;
                        }
                    }
                }
            }


            $WHERESQL = [];
            if (isset($WHERE["cant"]) and is_array($WHERE["cant"]) and sizeof($WHERE["cant"])) {
                foreach ($WHERE["cant"] as $key => $value) {
                    if (isset($value["_ne"])) {
                        $WHERESQL[] = $key . " = " . $value["_ne"];
                    } else {
                        if (isset($value["_gte"])) {
                            $WHERESQL[] = $key . " < " . $value["_gte"];
                        }
                        if (isset($value["_lte"])) {
                            $WHERESQL[] = $key . " > " . $value["_lte"];
                        }
                    }
                }
            }

            $sql = 'SELECT ' . $WHERE["View"] . ' FROM ' . tPublicacion;
            $WHERESQL = array_merge($WHERESQL, $WHERE["otros"]);
            if (sizeof($WHERESQL) !== 0)
                $sql .= ' WHERE ' . join(" AND ", $WHERESQL);
            $sql .= ' LIMIT ' . $_start . ', ' . $_end;
            $result = $BaseDatos->mandar($sql);
            $cont = 0;
            $retorno = [];
            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $key => $val) {
                        $retorno[$cont][$key] = $val;
                    }
                    $cont++;
                }
            } else {
                return [];
            }
            return $retorno;
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function BuscarById($BaseDatos,$id_post) {
        try {
            $sql = "SELECT * FROM " . tPublicacion . " where id='" . $id_post . "' LIMIT 1";
            $result = $BaseDatos->mandar($sql);
            $cont = 0;
            $retorno = [];
            if ($result->num_rows > 0) {
                return $result->fetch_array(MYSQLI_ASSOC);
                ;
            } else {
                return [];
            }
        } catch (Exception $exc) {
            return $exc->getTraceAsString();
        }
    }

    public static function ListarByUser($BaseDatos,$id) {
        try {
            $sql = "SELECT id,title FROM " . tPublicacion . " where author='" . $id . "'";
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

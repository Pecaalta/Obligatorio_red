<?php

/**
 * Description of SQLpublicacion_categoria
 *
 * @author mauro
 */
class Publicacion {

    public static function Post_Crear_publicacion_1($data) {
        $data_post = ["title", "tag", "introduction", "full", "state"];
        $datos = [];
        $categorias = [];
        $archivos = [];
        // Secciono los datos recibidos en un diccionario y dos arreglos
        foreach ($data as $key => $value) {
            if (in_array($key, $data_post)) {
                $datos[$key] = $value;
            } else if ($key == "category" || $key == "categoria" || $key == "categoria_id") {
                $categorias = $value;
            } else if ($key == "files" || $key == "adjunto") {
                $archivos = $value;
            }
        }

        // Creo el usuario y resibo su id
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $id = SQLpublicaciones::crear($BaseDatos,$datos);
        SQLpublicacion_categoria::Agregar($BaseDatos,$id, $categorias);
        SQLarchivos::Agregar($BaseDatos,$id, $archivos);
        $BaseDatos->finalisar();
        return array("id" => $id);
    }

    public static function Put_Publicar($id, $newState) {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        return SQLpublicaciones::Publicar($BaseDatos,$id, $newState);
        $BaseDatos->finalisar();
    }

    public static function Put_Editar_publicacion($id, $data) {
        $data_post = ["title", "tag", "introduction", "full", "state"];
        $datos = [];
        $categorias = [];
        $archivos = [];
        // Secciono los datos recibidos en un diccionario y dos arreglos
        foreach ($data as $key => $value) {
            if (in_array($key, $data_post)) {
                $datos[$key] = $value;
            } else if ($key == "category" || $key == "categoria") {
                $categorias = $value;
            } else if ($key == "files" || $key == "adjunto") {
                $archivos = $value;
            }
        }


        if (is_numeric($id)) {
            // Creo el usuario y resibo su id
            $BaseDatos = new DB();
            $BaseDatos->iniciar();
            SQLpublicaciones::Actualiza($BaseDatos,$id, $datos);
            // Verifico que sea un numero si lo es agrego la categoria a la tabla publicacion-categorias
            if (is_array($categorias) and sizeof($categorias) > 0) {
                $categorias = SQLpublicacion_categoria::Actualiza($BaseDatos,$id, $categorias);
            }
            if (is_array($archivos) and sizeof($archivos) > 0) {
                $archivos = SQLarchivos::Agregar($BaseDatos,$id, $archivos);
            }
            $BaseDatos->finalisar();
            return $id;
        } else {
            return false;
        }
    }

    public static function Get_Ver_publicacion($id) {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $publicacion = SQLpublicaciones::BuscarById($BaseDatos,$id);
        $publicacion["categoria"] = SQLpublicacion_categoria::ListarById($BaseDatos,$id);
        $publicacion["adjunto"] = SQLarchivos::ListarById($BaseDatos,$id);
        $BaseDatos->finalisar();
        return $publicacion;
    }

    public static function Get_Listar_publicaciones($para) {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $ret = SQLpublicaciones::Listar($BaseDatos, $para);
        $BaseDatos->finalisar();
        return $ret;
    }

}

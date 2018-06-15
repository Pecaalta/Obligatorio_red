<?php

/**
 * Description of User
 *
 * @author mauro
 */

class Categoria {

    public static function Get_Crear_publicacion_2() {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $return = SQLcategorias::Listar($BaseDatos);
        $BaseDatos->finalisar();
        return $return;
    }
    public static function Post_Crear_Categoria($data) {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $return = SQLcategorias::Crear($BaseDatos,$data["categorya"]);
        $BaseDatos->finalisar();
        return $return;
    }

}

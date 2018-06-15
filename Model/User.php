<?php

/**
 * Description of SQLpublicacion_categoria
 *
 * @author mauro
 */
class User {

    public static function Put_Actualizar_avatar($id, $avatar) {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $ret = SQLusuario::Actualizar_avatar($BaseDatos, $id, $avatar);
        $BaseDatos->finalisar();
        return $ret;
    }

    public static function Get_Mostrar_Perfil($id) {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $user = SQLusuario::BuscarById($BaseDatos, $id);
        $user["favoritos"] = SQLfavoritos::ListarByUser($BaseDatos, $id);
        $user["autoria"] = SQLpublicaciones::ListarByUser($BaseDatos, $id);
        $BaseDatos->finalisar();
        return $user;
    }

    public static function Put_Marcar_favorita($id, $id_posts) {
        $BaseDatos = new DB();
        $BaseDatos->iniciar();
        $ret = SQLusuario::Marcar_favorita($BaseDatos, $id, $id_posts);
        $BaseDatos->finalisar();
        return $ret;
    }

}

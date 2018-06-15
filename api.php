<?php

/**
 * Description of api
 *
 * @author mauro
 */
require './Model/Publicacion.php';
require './Model/User.php';
require './Model/Categoria.php';
require './Model/Constantes.php';
require './SQL/SQLcategorias.php';
require './SQL/SQLarchivos.php';
require './SQL/SQLusuario.php';
require './SQL/SQLpublicaciones_categorias.php';
require './SQL/SQLfavoritos.php';
require './SQL/SQLpublicaciones.php';
require './SQL/DB.php';

class api {

    private $retorno = [];
    private $resource;
    private $uri;

    public function serve() {

        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $paths = explode('/', $this->paths($uri));
        array_shift($paths);
        $this->resource = strtolower(array_shift($paths));
        $this->uri = ($paths);

        if (empty($this->resource)) {
            $this->handle_base($method);
        } else if ($this->resource == 'user') {
            $name = array_shift($paths);
            if (empty($name)) {
                $this->retorno["Error"] = 'HTTP/1.1 405 Method Not - 1';
                header('HTTP/1.1 405 Method Not Allowed');
                header('Allow: GET');
            } else {
                $this->method($method, $name);
            }
        } else if ($this->resource == 'post') {
            $name = array_shift($paths);
            $this->method($method, $name);
        } else if ($this->resource == 'category') {
            $name = array_shift($paths);
            $this->method($method, $name);
        } else {
            $this->retorno["Error"] = 'HTTP/1.1 404 Not Found - 2';
            header('HTTP/1.1 404 Not Found');
        }
        $this->result();
    }

    private function handle_base($method) {
        if ($method == 'GET') {
            header('Location: ' . "http://" . $_SERVER['SERVER_NAME'] . "\index.html");
        } else {
            $this->retorno["Error"] = 'HTTP/1.1 405 Method Not - 3';
        }
    }

    private function method($method, $name) {
        switch ($method) {
            case 'PUT':
                parse_str(file_get_contents('php://input'), $_PUT);
                $this->retorno["Data"] = $_PUT;
                $this->update($name);
                break;

            case 'POST':
                $this->retorno["Data"] = $_POST;
                $this->create($name);
                break;

            case 'GET':
                $this->retorno["Data"] = $_GET;
                $this->display($name);
                break;

            default:
                $this->retorno["Error"] = 'HTTP/1.1 405 Method Not - 4';
                //header('HTTP/1.1 405 Method Not Allowed');
                //header('Allow: GET, PUT, POST');
                break;
        }
    }

    private function create($name) {
        // POST -> /posts/       crear nuevo

        if (is_null($this->retorno["Data"]) || $this->retorno["Data"] === "" || sizeof($this->retorno["Data"]) === 0) {
            $this->retorno["Error"] = 'HTTP/1.1 400 Bad Request - 5';
            //header('HTTP/1.1 400 Bad Request');
        }
        if ($this->resource === 'post') {
            if (!isset($this->retorno["Data"]['title'])){
                $this->retorno["result"] = "Title faltante";
            }else if (!isset($this->retorno["Data"]['full']) ){
                $this->retorno["result"] = "Texto faltante";
            }else if (!isset($this->retorno["Data"]['categoria_id'])){
                $this->retorno["result"] = "Categorya faltante";
            }else if (strlen($this->retorno["Data"]['title'])== 0){
                $this->retorno["result"] = "Title esta vacio";
            }else if (strlen($this->retorno["Data"]['full'])< 60){
                $this->retorno["result"] = "Texto demasiado corto";
            }else if (sizeof($this->retorno["Data"]['categoria_id']) == 0){
                $this->retorno["result"] = "Faltan categorias";
            }else{
                $this->retorno["result"] = Publicacion::Post_Crear_publicacion_1($this->retorno["Data"]);
            }

        } else if ($this->resource === 'category') {
            $this->retorno["result"] = Categoria::Post_Crear_Categoria($this->retorno["Data"]);
        } else {
            $this->retorno["Error"] = 'HTTP/1.1 409 Conflict - 6';
            //header('HTTP/1.1 409 Conflict');
        }
    }

    private function update($name) {
        // POST -> /posts/{post_id}     editar
        // POST -> /posts/{post_id}     editar
        // POST -> /user/{user_id}      marcar publ fav
        // POST -> /user/{user_id}      actualisar avatar

        switch ($this->resource) {
            case 'user':
                $id = strtolower(array_shift($this->uri));
                if ($name !== "" and is_numeric($name)) {
                    if (isset($this->retorno["Data"]["id_posts"]) && !isset($this->retorno["Data"]["avatar"])) {
                        $this->retorno["result"] = User::Put_Marcar_favorita($name, $this->retorno["Data"]["id_posts"]);
                    } else if (!isset($this->retorno["Data"]["id_posts"]) && isset($this->retorno["Data"]["avatar"])) {
                        $this->retorno["result"] = User::Put_Actualizar_avatar($name, $this->retorno["Data"]["avatar"]);
                    } else {
                        $this->retorno["Error"] = 'HTTP/1.1 409 Conflict - 7';
                        //header('HTTP/1.1 409 Conflict');
                    }
                } else {
                    $this->retorno["Error"] = 'HTTP/1.1 409 Conflict - 8';
                    //header('HTTP/1.1 409 Conflict');
                }
                break;

            case 'post':
                $id = strtolower(array_shift($this->uri));
                if (isset($name) and isset($this->retorno["Data"]["estado"])) {
                    $this->retorno["result"] = Publicacion::Put_Publicar($name, $this->retorno["Data"]["estado"]);
                } else if (isset($name) and $id !== "" and is_numeric($name)) {
                    $this->retorno["result"] = Publicacion::Put_Editar_publicacion($name, $this->retorno["Data"]);
                } else {
                    $this->retorno["Error"] = 'HTTP/1.1 409 Conflict - 9';
                    //header('HTTP/1.1 409 Conflict');
                }
                break;

            default:
                $this->retorno["Error"] = 'HTTP/1.1 409 Conflict - 10';
            //header('HTTP/1.1 409 Conflict');
        }
    }

    private function display($name) {
        // GET -> /posts/           Listar 
        // GET -> /posts/{post_id}  ver una
        // GET -> /posts/create     ver tags
        // 
        // GET -> /user/{user_id}   perfil usuario
        switch ($this->resource) {
            case 'user':
                $id = strtolower(array_shift($this->uri));
                if ($id != "" and is_numeric($id)) {
                    $this->retorno["result"] = User::Get_Mostrar_Perfil($id);
                } else {
                    $this->retorno["Error"] = 'HTTP/1.1 409 Conflict - 11';
                    header('HTTP/1.1 409 Conflict');
                }
                break;

            case 'post':
                $uri = strtolower(array_shift($this->uri));
                if ($uri === "") {
                    $this->retorno["result"] = Publicacion::Get_Listar_publicaciones($this->retorno["Data"]);
                } else if ($uri === "create") {
                    $this->retorno["result"] = Categoria::Get_Crear_publicacion_2();
                } else if (is_numeric($uri)) {
                    $this->retorno["result"] = Publicacion::Get_Ver_publicacion($uri);
                } else {
                    $this->retorno["Error"] = 'HTTP/1.1 409 Conflict - 12';
                    //header('HTTP/1.1 409 Conflict');
                }
                break;

            default:
                //header('Location: ' . "http://" . $_SERVER['SERVER_NAME'] . "\index.html");
            //$this->retorno["Error"] = 'HTTP/1.1 404 Not Found';
            header('HTTP/1.1 409 Conflict');
        }
        //header('HTTP/1.1 404 Not Found');
    }

    private function paths($url) {
        $uri = parse_url($url);
        return $uri['path'];
    }

    /**
     * Displays a list of all retorno.
     */
    private function result() {
        header('Content-type: application/json');
        echo json_encode($this->retorno);
    }

}

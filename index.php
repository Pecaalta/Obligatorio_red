<?php

class DBace {

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "Blog";
    private $conn;

    public function DBace() {
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }

    public function insert($sql) {
        if ($this->conn->query($sql) === TRUE) {
            return true;
        } else {
            return $this->conn->error;
        }
    }

    public function Check() {
        if ($this->conn->connect_error)
            return false;
        else
            return true;
    }

    public function close() {
        $conn->close();
    }

}

class Server {

    private $retorno = [
        'cant' => 0
    ];
    private $resource;

    public function serve() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $paths = explode('/', $this->paths($uri));
        array_shift($paths);
        $this->resource = strtolower(array_shift($paths));
        if (empty($this->resource)) {
            $this->handle_base($method);
        } else if ($this->resource == 'user' or $this->resource == 'post') {
            $name = array_shift($paths);
            if (empty($name)) {
                $this->handle_base($method);
            } else {
                $this->handle_name($method, $name);
            }
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }

    private function handle_base($method) {
        switch ($method) {
            case 'GET':
                header('Location: index.php'); //no se  poner
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                header('Allow: GET');
                break;
        }
    }

    private function handle_name($method, $name) {
        switch ($method) {
            case 'PUT':
                $this->update($name);
                break;

            case 'POST':
                $this->create($name);
                break;

            case 'GET':
                $this->display($name);
                break;

            default:
                header('HTTP/1.1 405 Method Not Allowed');
                header('Allow: GET, PUT, DELETE');
                break;
        }
    }

    private function create($name) {
        $data = json_decode(file_get_contents('php://input'));
        if (is_null($data)) {
            header('HTTP/1.1 400 Bad Request');
            $this->result();
            return;
        }
        switch ($this->resource) {
            case 'user':
                echo "creacion de usuario";
                $this->retorno["tarea"] = "creacion de usuario";
                break;
            case 'post':
                echo "creacion de post";
                $this->retorno["tarea"] = "creacion de post";
                break;
            default:
                header('HTTP/1.1 409 Conflict');
                return;
        }

        $this->retorno["res"] = array_keys(serialize($data));
        $this->result();
    }

    private function update($name) {
        switch ($this->resource) {
            case 'user':
                $this->retorno["valor"] = "USER";
                $this->result();
                break;
            case 'post':
                $this->retorno["valor"] = "POST";
                $this->result();
                break;

            default:
                header('HTTP/1.1 409 Conflict');
                return;
        }
    }

    private function display($name) {
        switch ($this->resource) {
            case 'user':
                $this->retorno["valor"] = "solicita usuario " . $name;
                $this->result();
                break;
            case 'post':
                $this->retorno["valor"] = "solicita POST " . $name;
                $this->result();
                break;
            default:
                header('HTTP/1.1 409 Conflict');
                return;
        }
        //    header('HTTP/1.1 404 Not Found');
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

$server = new Server;
$server->serve();
/*
function consulta() {
    if (!isset($_GET["_limit"]))
        $_limit = 10;
    else
        $_limit = $_GET["_limit"];

    if (!isset($_GET["_page"])) {
        if (!isset($_GET["_start"])) {
            if (!isset($_GET["_end"])) {
                $_end = $_limit;
                $_start = 0;
            } else {
                $_end = $_GET["_end"];
                $_start = $_GET["_end"] - $_limit;
            }
        } else {
            if (!isset($_GET["_end"])) {
                $_end = $_GET["_start"] + $_limit;
            } else {
                $_end = $_GET["_end"];
            }
            $_start = $_GET["_start"];
        }
    } else {
        $_start = $_GET["_page"] * $_limit;
    }
    $_start = ($_start < 0) ? 0 : $_start;
    $WHERE = [
        "cant" => [],
        "otros" => []
    ];
    foreach ($_GET as $key => $value) {
        if (!in_array($key, ["_limit", "_page", "_end", "_start", "q"])) {
            if (strrpos($key, "_ne")) {
                $filer = substr($key, 0, -3);
                if ($filer != "") {
                    $WHERE["cant"][$filer]["_ne"] = $value;
                }
            }if (strrpos($key, "_gte")) {
                $filer = substr($key, 0, -4);
                if ($filer != "") {
                    $WHERE["cant"][$filer]["_gte"] = $value;
                }
            } else if (strrpos($key, "_lte")) {
                $filer = substr($key, 0, -4);
                if ($filer != "") {
                    $WHERE["cant"][$filer]["_lte"] = $value;
                }
            } else if (strrpos($key, "_like")) {
                $filer = substr($key, 0, -5);
                if ($filer != "") {
                    $WHERE[$filer]["_like"] = str_replace(["___", "__"], ['%', '_'], $value);
                }
            } else if (strrpos($key, "q")) {
                $WHERE["q"] = $value;
            } else if (strrpos($key, "_sort")) {
                $ORDER[] = str_replace(",", " ", $value);
            } else {
                $WHERE["otros"][] = $key . " = " . $value;
            }
        }
    }
    $WHERESQL = [];
    foreach ($WHERE["cant"] as $key => $value) {
        if (in_array("_ne", $WHERE["cant"][$Key])) {
            $WHERESQL[] = $Key . " = " . $WHERE["cant"][$Key]["_ne"];
        } else if (in_array("_gte", $WHERE["cant"][$Key])) {
            if (in_array("_lte", $WHERE["cant"][$Key])) {
                if ($WHERE["cant"][$Key]["_gte"] < $WHERE["cant"][$Key]["_lte"])
                    $WHERESQL[] = $Key . " BETWEEN " . $WHERE["cant"][$Key]["_gte"] . " AND " . $WHERE["cant"][$Key]["_lte"];
            }else {
                $WHERESQL[] = $Key . " < " . $WHERE["cant"][$Key]["_gte"];
            }
        } else if (in_array("_lte", $WHERE["cant"][$Key])) {
            $WHERESQL[] = $Key . " > " . $WHERE["cant"][$Key]["_lte"];
        }
    }

    $SQLs = 'SELECT * FROM POST';
    if (sizeof($WHERESQL) != 0 and sizeof($WHERE["otros"]) != 0)
        $SQLs .= ' WHERE ' . join(" AND ", $WHERESQL) . ' AND ' . join(" AND ", $WHERE["otros"]);
    else if (sizeof($WHERE["otros"]) != 0)
        $SQLs .= ' WHERE ' . join(" AND ", $WHERE["otros"]);
    else if (sizeof($WHERESQL) != 0)
        $SQLs .= ' WHERE ' . join(" AND ", $WHERESQL);
    $SQLs .= ' LIMIT ' . $_end . ' OFFSET ' . $_start;
    return $SQLs;
}
*/
function date_SERVER() {
    $indicesServer = array('PHP_SELF',
        'argv',
        'argc',
        'GATEWAY_INTERFACE',
        'SERVER_ADDR',
        'SERVER_NAME',
        'SERVER_SOFTWARE',
        'SERVER_PROTOCOL',
        'REQUEST_METHOD',
        'REQUEST_TIME',
        'REQUEST_TIME_FLOAT',
        'QUERY_STRING',
        'DOCUMENT_ROOT',
        'HTTP_ACCEPT',
        'HTTP_ACCEPT_CHARSET',
        'HTTP_ACCEPT_ENCODING',
        'HTTP_ACCEPT_LANGUAGE',
        'HTTP_CONNECTION',
        'HTTP_HOST',
        'HTTP_REFERER',
        'HTTP_USER_AGENT',
        'HTTPS',
        'REMOTE_ADDR',
        'REMOTE_HOST',
        'REMOTE_PORT',
        'REMOTE_USER',
        'REDIRECT_REMOTE_USER',
        'SCRIPT_FILENAME',
        'SERVER_ADMIN',
        'SERVER_PORT',
        'SERVER_SIGNATURE',
        'PATH_TRANSLATED',
        'SCRIPT_NAME',
        'REQUEST_URI',
        'PHP_AUTH_DIGEST',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'AUTH_TYPE',
        'PATH_INFO',
        'ORIG_PATH_INFO');
    echo '<table cellpadding="10">';
    foreach ($indicesServer as $arg) {
        if (isset($_SERVER[$arg])) {
            echo '<tr><td>' . $arg . '</td><td>' . $_SERVER[$arg] . '</td></tr>';
        } else {
            echo '<tr><td>' . $arg . '</td><td>-</td></tr>';
        }
    }
    echo '</table>';
}

?>
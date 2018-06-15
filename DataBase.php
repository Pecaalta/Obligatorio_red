<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author mauro
 */
class dataBase {

    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "Blog";
    private $conn;
    private $sql;
    private $result;

    public function Check() {
        if ($this->conn->connect_error)
            return false;
        else
            return true;
    }

    public function connect() {
        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        } catch (Exception $exc) {
            $this->result = $exc->getTraceAsString();
        }
    }

    public function guardar($sql) {
        $this->sql = $sql;
    }

    public function resultado() {
        return $this->result;
    }

    public function commit() {
        connect();
        /*
          if ($this->sql === "") {
          $this->result = false;
          } else if (!Check()) {
          $this->sql = "";
          $this->result = false;
          } else {
          $result = $conn->query($sql);
          if ($result === TRUE) {
          $this->result = $result;
          } else {
          $this->result = $conn->error;
          }
          }
         */
        $this->sql = "";
        //$conn->close();
    }

}

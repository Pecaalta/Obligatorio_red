<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author mauro
 */
class DB {

    private $conn;

    function __construct() {
        $this->conn = new mysqli(servername, username, password, dbname);
    }

    public function iniciar() {
        if ($this->conn->connect_error) {
            return $this->conn->connect_error;
        }
        return true;
    }

    public function mandar($sql) {
        try {
            return $this->conn->query($sql);
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    
    public function create($sql) {
        try {
            $resultat = $this->conn->query($sql);
            if (!$resultat) {
                return false;
            } else {
                return $this->conn->insert_id;
            }
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function finalisar() {
        $this->conn->commit();
    }

}

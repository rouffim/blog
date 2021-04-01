<?php

namespace service;

use Exception;
use PDO;

abstract class Service {
    protected $db;

    /**
     * Service constructor.
     */
    protected function __construct() {

    }


    /**
     *
     */
    protected function connectToDb() {
        $config = include('configuration.php');

        try {
            $this->db = new PDO("mysql:host=" . $config['database_host'] . ";dbname=" . $config['database_database'] . ";port=" . $config['database_port'], $config['database_user'], $config['database_password']);
            // set the PDO error mode to exception
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e) {
            $this->db = null;
        }
    }

    /**
     *
     */
    protected function disconnectFromDb() {
        $this->db = null;
    }

}

<?php
include(__ROOT__.'/config/config.php');

class Database {
    public $host   = DB_HOST;
    public $user   = DB_USER;
    public $pass   = DB_PASS;
    public $dbname = DB_NAME;
    public $link;
    public $error;
 
    public function __construct() {
        $this->connectDB();
    }
 
    private function connectDB() {
        $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        
        if ($this->link->connect_error) {
            $this->error = "Connection failed: " . $this->link->connect_error;
            return false;
        }
        
        // Set charset to UTF-8 by default for all connections
        $this->link->set_charset("utf8");
        return true;
    }
 
    // Select or Read data
    public function select($query) {
        $result = $this->link->query($query);
        if (!$result) {
            $this->error = $this->link->error . " (Line: " . __LINE__ . ")";
            return false;
        }
        return ($result->num_rows > 0) ? $result : false;
    }

    // Select with UTF-8 charset (redundant now as charset is set in connectDB)
    public function selectdc($query) {
        return $this->select($query);
    }

    // Insert data
    public function insert($query) {
        $insert_row = $this->link->query($query);
        if (!$insert_row) {
            $this->error = $this->link->error . " (Line: " . __LINE__ . ")";
        }
        return $insert_row;
    }
  
    // Update data
    public function update($query) {
        $update_row = $this->link->query($query);
        if (!$update_row) {
            $this->error = $this->link->error . " (Line: " . __LINE__ . ")";
        }
        return $update_row;
    }
  
    // Delete data
    public function delete($query) {
        $delete_row = $this->link->query($query);
        if (!$delete_row) {
            $this->error = $this->link->error . " (Line: " . __LINE__ . ")";
        }
        return $delete_row;
    }

    // Escape string to prevent SQL injection
    public function escape($str) {
        return $this->link->real_escape_string($str);
    }
}
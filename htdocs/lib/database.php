<?php
define('__ROOT__', dirname(dirname(__FILE__))); 
include (__ROOT__ . '/config/config.php');

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    public $link; // Thuộc tính kết nối
    private $error;

    public function __construct() {
        $this->connectDB();
    }

    private function connectDB() {
        try {
            $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            if ($this->link->connect_error) {
                throw new Exception("Kết nối cơ sở dữ liệu thất bại: " . $this->link->connect_error);
            }
            // Đặt charset UTF-8 để hỗ trợ tiếng Việt
            $this->link->set_charset("utf8");
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            // Ghi log lỗi trong môi trường sản xuất
            error_log($this->error, 3, __ROOT__ . '/logs/error.log');
            die("Lỗi kết nối cơ sở dữ liệu.");
        }
    }

    // Select or Read data
    public function select($query) {
        try {
            $result = $this->link->query($query);
            if (!$result) {
                throw new Exception("Lỗi truy vấn: " . $this->link->error);
            }
            return $result->num_rows > 0 ? $result : false;
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, __ROOT__ . '/logs/error.log');
            return false;
        }
    }

    // Insert data
    public function insert($query) {
        try {
            $result = $this->link->query($query);
            if (!$result) {
                throw new Exception("Lỗi chèn dữ liệu: " . $this->link->error);
            }
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, __ROOT__ . '/logs/error.log');
            return false;
        }
    }

    // Update data
    public function update($query) {
        try {
            $this->link->set_charset("utf8"); // Đặt charset trước khi update
            $result = $this->link->query($query);
            if (!$result) {
                throw new Exception("Lỗi cập nhật dữ liệu: " . $this->link->error);
            }
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, __ROOT__ . '/logs/error.log');
            return false;
        }
    }

    // Delete data
    public function delete($query) {
        try {
            $result = $this->link->query($query);
            if (!$result) {
                throw new Exception("Lỗi xóa dữ liệu: " . $this->link->error);
            }
            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, __ROOT__ . '/logs/error.log');
            return false;
        }
    }

    // Đóng kết nối (tùy chọn)
    public function __destruct() {
        if ($this->link) {
            $this->link->close();
        }
    }
}
?>
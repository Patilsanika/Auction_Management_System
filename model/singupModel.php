<?php
require_once 'config.php';

class SingupModel {
    private $conn;

    public function __construct() {
        $config = new Config();
        $this->conn = new mysqli($config->servername, $config->username, $config->password, $config->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function singup($email, $fname, $lname, $mno, $pass) {
        $stmt = $this->conn->prepare("SELECT * FROM details WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script type='text/javascript'>alert('Already Exist!')</script>";
        } else {
            $stmt = $this->conn->prepare("INSERT INTO details (Email, FirstName, LastName, Mob, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $email, $fname, $lname, $mno, $pass);

            if ($stmt->execute()) {
                echo "<script type='text/javascript'>alert('Successful')</script>";
            } else {
                echo "<script type='text/javascript'>alert('Insertion error')</script>";
            }
        }
    }

    public function login($email, $pass) {
        $stmt = $this->conn->prepare("SELECT * FROM details WHERE Email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_object();
        } else {
            return false;
        }
    }

    public function logout() {
        session_start();
        session_destroy();
    }
}
?>

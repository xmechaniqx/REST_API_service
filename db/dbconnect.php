<?php
require_once ('system/log.php');
class DBmysql {

    private $conn;
    public function __construct() {
        $log = new Log('db.log');

        $host = "185.177.216.77:3306";
        $dbname = "ICfBONvq";
        $user = "XOvVIp";
        $password = "NQXZEfgRlqqgmTDD";

        try {
            $this->conn = new mysqli($host, $user, $password, $dbname);
        } catch (Exception $error) {
            echo json_encode(value: "Ошибка подключения к базе данных {host:" . $host ." db:". $dbname ." user:". $user . " error:". $error->getMessage(). "}");
            $log->write("Ошибка подключения: " . $error->getMessage());
        }

        $log->write("Успешное подключение");
    }
    public function create($data) {
        $log = new Log('db.log');

        $sql = "INSERT INTO users (full_name, role, efficiency) VALUES (
        '" . $data['full_name'] . "', 
        '" . $data['role'] . "', 
        '" . (int)($data['efficiency']) ."'
        )"; 

        try {
            $this->conn->query($sql);
        } catch (Exception $error) {
            $log->write("Ошибка обработки POST-запроса при обращении к БД");
            throw new Exception("Ошибка обработки POST-запроса при обращении к БД");
        }
        $result = $this->getLastId();
            return $result;
    }

    public function get($data) {
        $log = new Log('db.log');

        $sql = "SELECT id, full_name, role, efficiency FROM users ";
        $result = [];
        $selector = "WHERE";

        if ($data['id'] != null) {
            $sql .=  $selector . " id=" . (int)$data['id'] . " "; 
            $selector = "AND";
        }
        if ($data['full_name'] != null) {
            $sql .= $selector . " full_name='" . $data['full_name'] . "' "; 
            $selector = "AND";
        }
        if ($data['role'] != null) {
            $sql .= $selector . " role='" . $data['role'] . "' "; 
            $selector = "AND";
        }
        if ($data['efficiency'] != null) {
            $sql .= $selector . " efficiency=" . (int)$data['efficiency'];
        }

            try {
                $query = $this->conn->query($sql);
                foreach ($query as $row) {
                    array_push($result, $row);
                }
            } catch (Exception $error) {
                $log->write("Ошибка обработки GET-запроса при обращении к БД");
                throw new Exception("Ошибка обработки GET-запроса при обращении к БД");
            }

            if (count($result) == 0) {
                    throw new Exception("Ошибка обработки GET-запроса при обращении к БД:: Отсутсвует запись в базе по заданным параметрам");
            }
                return $result;
    }
    public function update($data, $id) {
        $log = new Log('db.log');
        $result = $this->getUser($id);
        if (!isset($result)) {
            throw new Exception("Отсутствует пользователь по заданному идентификатору");
        }

        $sql = "UPDATE users SET ";
        if ($data['full_name']) {
            $sql.= "full_name='" . $data['full_name'] . "', ";
        }
        if ($data['role']) {
            $sql.= "role='" . $data["role"] . "', ";
        }
        if ($data['efficiency']) {
            $sql.= "efficiency='" . $data["efficiency"]. "', ";
        }
        $comma = strrpos($sql,",");
        $sql = substr_replace($sql, '',$comma, 1);
        $sql.= "WHERE id=" . $id . " ";
        $log->write($sql);
        try {
            $this->conn->query($sql);
        } catch (Exception $error) {
            $log->write("Ошибка обработки PATCH-запроса при обращении к БД");
            throw new Exception("Ошибка обработки PATCH-запроса при обращении к БД");
        }

        return $result;
    }

    public function delete($id) {
        $log = new Log('db.log');
        $user = $this->getUser($id);
        try{
            if ($id == null) {
                $sql = "DELETE FROM users";
                $this->conn->query($sql);
            } else {
                $sql = "DELETE FROM users WHERE id=" . (int)$id;
                $this->conn->query($sql);
                return $user;
            }
        } catch (Exception $error) {
            $log->write("Ошибка обработки DELETE-запроса при обращении к БД");
            throw new Exception("Ошибка обработки DELETE-запроса при обращении к БД");
        }
    }

    public function getLastId() {
        return (int)$this->conn->insert_id;
    }
    public function getUser($id) {
        $log = new Log('db.log');
        $sql = "SELECT * FROM users WHERE id=" . (int)$id;
        try{
            $res = $this->conn->query($sql)->fetch_object();
        } catch(Exception $error) {
            $log->write("Ошибка получения объекта пользователя при обращении к БД");
            throw new Exception("Ошибка получения объекта пользователя при обращении к БД");
        }
        return $res;
    }
}


    
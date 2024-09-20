<?php
require_once ('db/dbconnect.php');
class ModelRestApiService {

    public $db;

    public function __construct() {
        //Создаем экземпляр маппера для обращения к БД
        $this->db = new DBmysql(); 
    }

    /**
     * Метод обработки обращения для создания записи пользователя
     * @return int
     */
    public function create($data) {
        $log = new Log('rest.log');
        if (!isset($data['full_name'])) {
            $log->write("Ошибка получения имени");
            throw new Exception("Ошибка получения имени");
        }
        if (!isset($data['role'])) {
            $log->write("Ошибка получения роли");
            throw new Exception("Ошибка получения роли");
        }
        if (!isset($data['efficiency'])) {
            $log->write("Ошибка получения параметра эффективности");
            throw new Exception("Ошибка получения параметра эффективности");
        }
        $result = $this->db->create($data);
        return $result;
    }

    /**
     * Метод обработки обращения для получения записи пользователя
     * @return array
     */
    public function get($data) {
        if (!isset($data)){
            throw new Exception("Ошибка получения параметров в запросе");
        }
        $result = $this->db->get($data);
        return $result;
    }
    
    /**
     * Метод обработки обращения для обновления записи пользователя
     * @return object
     */
    public function update($data, $id) {
        if (!isset($data)){
            throw new Exception("Ошибка получения параметров в запросе");
        }
        if (!isset($id)){
            throw new Exception("Ошибка получения идентификатора в запросе");
        }
        $result = $this->db->update($data, $id);
        return $result;
    }

    /**
     * Метод обработки обращения для удаления записи пользователя
     * @return object
     */
    public function delete($id) {
        $result = $this->db->delete($id);
        return $result;
    }
}
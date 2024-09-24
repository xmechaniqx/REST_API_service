<?php

require_once ('system/log.php');
require_once ('model/rest.php');

class ControllerRestApiService {

    public $rest_model;

    /**
     * Конструктор класса ControllerRestApiService
     */
    public function __construct(){
        //Создаем экземпляр модели для обработки запросов
        $this->rest_model = new \ModelRestApiService();
    }

    /**
     * Метод обработки обращения для создания записи пользователя
     * @return void
     */
    public function create() {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        try {
            $result = $this->rest_model->create($data);
        if (isset($result)) {
            $response = [
                "success" => true,
                "result" => [
                    "id" => $result
                ]
            ];
        }
        } catch (Exception $error) {
            $response = [
                "success" => false,
                "result" => [
                    "error" => [
                        "code" => $error->getCode(),
                        "message" => $error->getMessage()
                    ]
                ]
            ];
        } finally {
            echo json_encode($response);
        }        
    }

    /**
     * Метод обработки обращения для чтения записи пользователя
     * @return void
     */
    public function get() {
        $data = [];
        if (isset($_GET['user_id'])){
         $data['id'] = $_GET['user_id'];
        } 
        if (isset($_GET['full_name'])){
         $data['full_name'] = $_GET['full_name'];
        } 
        if (isset($_GET['role'])){
         $data['role'] = $_GET['role'];
        } 
        if (isset($_GET['efficiency'])){
         $data['efficiency'] = $_GET['efficiency'];
        }
        try {
           $result = $this->rest_model->get($data);
            if (isset($result)) {
                $response = [
                    "success" => true,
                    "result" => [
                        "users" => $result
                    ]
                ];
            } 
        } catch (Exception $error) {
            $response = [
                "success" => false,
                "result" => [
                    "error" => [
                        "code" => $error->getCode(),
                        "message" => $error->getMessage()
                    ]
                ]
            ];
        } finally {
            echo json_encode($response);
        }
    }
    
    /**
     * Метод обработки обращения для обновления записи пользователя
     * @return void
     */
    public function update() {
        $log = new Log('rest.log');
        $json = file_get_contents('php://input');
        if ($_GET['user_id']){
            $id = $_GET['user_id'];
        } else {
            $id = null;
            $log->write(" Запрос с пустым идентификатором");
        }
        $data = json_decode($json, true);
        try {
            $result = $this->rest_model->update($data, $id);
            if (isset($result)) {
                $response = [
                    "success" => true,
                    "result" => $result
                ];
            }
        } catch (Exception $error) {
            $response = [
                "success" => false,
                "result" => [
                    "error" => [
                        "code" => $error->getCode(),
                        "message" => $error->getMessage()
                    ]
                ]           
            ];
        } finally {
            echo json_encode($response);
        }
    }

    /**
     * Метод обработки обращения для удаления записи пользователя
     * @return void
     */
    public function delete() {
        $log = new Log('rest.log');
        if ($_GET['user_id']){
            $id = $_GET['user_id'];
        } else {
            $id = null;
            $log->write(" Запрос с пустым идентификатором");
        }
        try {
            $result = $this->rest_model->delete($id);
            if (empty($result)) {
                $response = [
                    "success" => true,
                ];
            } else {
                $response = [
                    "success" => true,
                    "result" => $result
                ];
            }
        } catch (Exception $error) {
            $response = [
                "success" => false,
                "result" => [
                    "error" => [
                        "code" => $error->getCode(),
                        "message" => $error->getMessage()
                    ]
                ]           
            ];
        } finally {
            echo json_encode($response);
        }
    }
}

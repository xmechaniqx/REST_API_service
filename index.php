<?php

require_once ('system/log.php');
require_once ('controller/rest.php');

    $service = new ControllerRestApiService();
    $log = new Log('rest.log');
    if ($_SERVER['REQUEST_METHOD']) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method){
            case 'POST':
                $requestUri = $_SERVER['REQUEST_URI'];
                $trimUri = trim($requestUri,'/');
                if (isset($trimUri) && $trimUri === 'create'){
                   $service->create(); 
                }
                break;
            case 'GET':
                $requestUri = $_SERVER['REQUEST_URI'];
                $pattern = "/^\/get\/(\d+)$/";
                if (preg_match($pattern, $requestUri, $matches)) {
                    $id = intval($matches[1]);
                    $service->get($id);
                }
                break;
            case 'PATCH':
                $requestUri = $_SERVER['REQUEST_URI'];
                $pattern = "/^\/update\/(\d+)$/";
                if (preg_match($pattern, $requestUri, $matches)) {
                    $id = intval($matches[1]);
                } else {
                    $id = null;
                    $log->write(" Запрос с пустым идентификатором");
                }
                $service->update($id);
                break;
            case 'DELETE':
                $requestUri = $_SERVER['REQUEST_URI'];
                $pattern = "/^\/delete\/(\d+)$/";
                $trimUri = trim($requestUri,'/');
                if (isset($trimUri) && $trimUri === 'delete'){
                   if (preg_match($pattern, $requestUri, $matches)) {
                        $id = intval($matches[1]);
                    } else {
                        $id = null;
                        $log->write(" Запрос с пустым идентификатором");
                    }              
                    $service->delete($id);
                }
                break;
        }
    }
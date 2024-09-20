<?php

require_once ('system/log.php');
require_once ('controller/rest.php');

    $service = new ControllerRestApiService();
    
    if ($_SERVER['REQUEST_METHOD']) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method){
            case 'POST':
                $service->create();
                break;
            case 'GET':
                $service->get();
                break;
            case 'PATCH':
                $service->update();
                break;
            case 'DELETE':
                $service->delete();
                break;
        }
    }
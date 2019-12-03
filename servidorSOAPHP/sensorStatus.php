<?php

    require_once('arduinoStatusdb.php');

    if(!extension_loaded("soap")){
        dl("php_soap.dll");
    }

    ini_set("soap.wsdl_cache_enabled","0");
    $server = new SoapServer("sensorStatus.wsdl");

    function getSensorStatus($sensorRef) {
        $db = new SensorStatusDBHandler;
        $sql = "SELECT `status` FROM `devices` WHERE device = '$sensorRef'";
        $result = $db-> request($sql);
        if($result){
            $codex = $result->fetch_assoc();
            return $codex['status'];
        } else {
            return '';
        }
    }

    getSensorStatus("MC-38");

    $server->AddFunction("getSensorStatus");
    $server->handle();

?>
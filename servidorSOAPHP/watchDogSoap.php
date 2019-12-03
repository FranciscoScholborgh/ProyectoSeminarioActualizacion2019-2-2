<?php

    require_once('arduinoStatusdb.php');

    if(!extension_loaded("soap")){
        dl("php_soap.dll");
    }

    ini_set("soap.wsdl_cache_enabled","0");
    $server = new SoapServer("watchDog.wsdl");

    function updateStatus($sensorRef, $status) {
        $db = new SensorStatusDBHandler;
        $sql = "UPDATE `devices` SET `status` = '$status' WHERE `devices`.`device` = '$sensorRef'";
        $result = $db -> request($sql);
        if($result) {
            return true;
        } else {
            return false;
        }       
    }

    $server->AddFunction("updateStatus");
    $server->handle();

?>
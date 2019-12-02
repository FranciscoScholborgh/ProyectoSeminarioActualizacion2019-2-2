<?php


    if(!extension_loaded("soap")){
        dl("php_soap.dll");
    }

    ini_set("soap.wsdl_cache_enabled","0");
    $server = new SoapServer("watchDog.wsdl");

    function openCon() {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $db = "estadoarduino";
        $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

        return $conn;
    }      

    function closeCon($conn) {
        $conn -> close();
    }

    function updateStatus($sensorRef, $status) {
        $db = openCon();
        $sql = "UPDATE `devices` SET `status` = '$status' WHERE `devices`.`device` = '$sensorRef'";
        $result = $db->query($sql);
        closeCon($db);
        if($result) {
            return true;
        } else {
            return false;
        }
        
    }

    $server->AddFunction("updateStatus");
    $server->handle();
   
?>
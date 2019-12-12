<?php

    require_once('arduinoStatusdb.php');

    if(!extension_loaded("soap")){
        dl("php_soap.dll");
    }

    ini_set("soap.wsdl_cache_enabled","0");
    $server = new SoapServer("sensorStatus.wsdl");

    function getSensorStatus($sensorRef) {
        $rdbUrl = "https://seminarioact-b1b0a.firebaseio.com/devices.json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $rdbUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);

        $response = curl_exec($ch);

        $data = json_decode($response, true);

        $result = '';
        foreach($data as $key => $value) {
            if (strcmp($data[$key]["device"], $sensorRef) == 0) {
                $result = $data[$key]["status"];
                break;
            }
        }
        curl_close($ch);
        return $result;
    }

    echo getSensorStatus("MC-38");

    $server->AddFunction("getSensorStatus");
    $server->handle();

?>
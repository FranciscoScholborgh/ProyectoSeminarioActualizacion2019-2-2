<?php

    if(!extension_loaded("soap")){
        dl("php_soap.dll");
    }

    ini_set("soap.wsdl_cache_enabled","0");
    $server = new SoapServer("watchDog.wsdl");

    function updateStatus($sensorRef, $status) {
        $rdbUrl = "https://seminarioact-b1b0a.firebaseio.com/devices.json";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $rdbUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);

        $response = curl_exec($ch);
        $data = json_decode($response, true);

        $result = '';
        $sensorKey;
        foreach($data as $key => $value) {
            if (strcmp($data[$key]["device"], $sensorRef) == 0) {
                $sensorKey = $key;
                $result = true;
                break;
            }
        }
        curl_close($ch);

        if($result) {
			$curl = curl_init();
            $data = array($key => array("device"=> $sensorRef, "status" => $status) );
            $json = json_encode( $data, true );
            curl_setopt( $curl, CURLOPT_URL, $rdbUrl );
            curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PATCH" );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $json );
            $response = curl_exec($curl);
            curl_close($curl);
            return true;
        } else {
            return false;
        }
               
    }

    $server->AddFunction("updateStatus");
    $server->handle();

?>
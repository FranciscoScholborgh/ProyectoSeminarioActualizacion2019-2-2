<?php

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['device'])) {
            $sensorRef = $_GET['device'];
            $rdbUrl = "https://seminarioact-b1b0a.firebaseio.com/devices.json";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $rdbUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);

            $response = curl_exec($ch);

            $data = json_decode($response, true);

            $result = '';
            $sensorData;
            foreach($data as $key => $value) {
                if (strcmp($data[$key]["device"], $sensorRef) == 0) {
                    $sensorData = $data[$key];
                    $result = true;
                    break;
                }
            } 
            
            if($result) {
                header("HTTP/1.1 200 OK");
                echo json_encode($sensorData, true);
            } 
            curl_close($ch);
            exit();
        } 
    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $input = $_GET;
        $data = json_decode(file_get_contents("php://input"), True);
        print_r($data);
        $device = $data['device'];
        $status = $data['status'];
        $db = new SensorStatusDBHandler;
        $sql = "UPDATE `devices` SET `status` = '$status' WHERE `devices`.`device` = '$device'";
        $result = $db-> request($sql);
        header("HTTP/1.1 200 OK");
        exit();
    }
    
    //En caso de que ninguna de las opciones anteriores se haya ejecutado
    header("HTTP/1.1 400 Bad Request");

?>
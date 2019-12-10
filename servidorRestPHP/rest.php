<?php

    require_once('arduinoStatusdb.php');
    include "dbUtils.php";
    $dbConn =  connect($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['device'])) {
            $algo = $_GET['device'];
            $sql = $dbConn->prepare("SELECT * FROM devices where device='$algo'");
            $sql->bindValue(':device', $_GET['device']);
            $sql->execute();
            header("HTTP/1.1 200 OK");
            echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
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
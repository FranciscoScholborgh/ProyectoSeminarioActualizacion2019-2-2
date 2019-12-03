<?php

    class SensorStatusDBHandler {

        private $conn;

        private function openCon() {
            $dbhost = "localhost";
            $dbuser = "root";
            $dbpass = "";
            $db = "estadoarduino";
            $this -> conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
        }      
    
        private function closeCon() {
            $this -> conn -> close();
        }

        public function request($sql) {
            $this -> openCon();
            $result = $this -> conn -> query($sql);
            $this -> closeCon();
            return $result;
        }

    }

  
?>
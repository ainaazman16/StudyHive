<?php
    $servername = "localhost:3301";
    $username = "root";
    $password = "1234";
    $dbname = "studyhive";

    //create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //check connection
    if ($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }

    echo "Connected successfully";
?>
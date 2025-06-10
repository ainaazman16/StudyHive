<?php
    $servername = "localhost:3301";
    $username = "studyhive";
    $password = "010203";
    $dbname = "studyhive";

    //create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    //check connection
    if ($conn->connect_error){
        die("Connection failed: ". $conn->connect_error);
    }

    echo "Connected successfully";
?>
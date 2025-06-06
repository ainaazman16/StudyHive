<?php
require("connect.php");

//belum siap
$nm = $_POST['name'];
$eml = $_POST['email'];
$username = $_POST['username'];
$pass = $_POST['password'];

//Hash Password sebelum simpan
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

//Masukkan dalam database
$sql = "INSERT INTO user (name, email, username, password)
        VALUES ('$nm', '$eml', '$username', '$hashedPassword')";

if ($conn->query($sql) === TRUE){
    echo "New Record created successfully";
    echo "<meta http-equiv='refresh' content='3;URL=index.php'>";
} else{
    echo "Error:  ". $conn->error;
}
$conn->close();

?>
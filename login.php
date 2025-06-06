<?php
//initialize the session

session_start();
include('connect.php');

//Ambil input dari POST jikka belum diset
if (!isset($_SESSION['username']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
}

//Pastikan ada username dan password dalam session
if (isset($_SESSION['username'], $_SESSION['password'])) {
    $username = $_SESSION['username'];
    $input_password = $_SESSION['password'];

    //Cari pengguna berdasarkan username sahaja
    $sql = "SELECT * FROM member WHERE username='$username'";
    $result = $conn->query(query: $sql);

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        //Guna password_verify untuk semak kata laluan
        if (password_verify($input_password, $user['password'])) {
            include("home.php");
        } else {
            echo "Login Fail: Password salah";
            session_unset();
            echo "<meta http-equiv='refresh' content='3;URL=index.php'>";
        }
    } else {
        echo "Login Fail: Username tidak wujud";
        session_unset();
        echo "<meta http-equiv='refresh' content='3;URL=index.php'>";
    }
}

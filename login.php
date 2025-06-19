<?php

session_start();
include('connect.php');

//Ambil input dari POST jikka belum diset
if (!isset($_SESSION['user_Name']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['user_Name'] = $_POST['user_Name'];
    $_SESSION['password'] = $_POST['password'];

}

//Pastikan ada username dan password dalam session
if (isset($_SESSION['user_Name'], $_SESSION['password'])) {
    $username = $_SESSION['user_Name'];
    $input_password = $_SESSION['password'];

    //Cari pengguna berdasarkan username sahaja
    $sql = "SELECT * FROM user WHERE user_Name='$username'";
    $result = $conn->query(query: $sql);

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        //Guna password_verify untuk semak kata laluan
        if (password_verify($input_password, $user['password'])) {
            //  Store user ID in session
            $_SESSION['user_ID'] = $user['user_ID'];
            $_SESSION['user_Fname'] = $user['user_Fname']; // Optional if you want to use name elsewhere
            $_SESSION['email'] = $user['email']; // Optional
            $_SESSION['user_Name'] = $user['user_Name']; // Already used

            //  Redirect to homepage or include it
            header("Location: homePage.php");
            exit();
}

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

?>

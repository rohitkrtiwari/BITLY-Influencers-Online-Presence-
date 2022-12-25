<?php
include "_dbconnect.php";

$username = '';
$password = $email = $phone_number = $site_name = $site_lnk = '';

if (isset($_GET['token'])){ 
    $token = $_GET['token'];
    $sql = "SELECT * FROM tblusercredentialstemp where token = '$token'";
    $results = mysqli_query($conn, $sql);
    if (mysqli_num_rows($results) == 1) {
        $response = mysqli_fetch_assoc($results);
        $name = $response['name'];
        $password = $response['password'];
        $phone_number = $response['phone_number'];
        $email = $response['email'];
        $site_name = $response['shop_name'];
        $site_lnk = $response['shop_link'];
        $sql = "call udt_tblusercredentials('".$token."')";
        mysqli_query($conn, $sql);
        $default_profile_pic = 'images/default_profile.png';
        $sql = "call ins_UserRegistration('".$name."','".$email."', '".$password."', '".$phone_number."', '".$site_name."', '".$site_lnk."', '".$default_profile_pic."')";
        mysqli_query($conn, $sql);
        header('location: login.php');
    }else{
        echo "Activation key expired.";
    }
}
else {
    header("location: https://www.google.com");
}
?> 
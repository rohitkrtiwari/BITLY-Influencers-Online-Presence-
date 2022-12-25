<?php
function get_Time(){
    date_default_timezone_set('Asia/calcutta');
    $time = date('g:i:s');
    return $time;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $sitename = $_POST['sitename'];
    $siteaddress = $_POST['siteaddress'];


    // Connecting to Database
    $servername = "localhost";
    $usr = "root";
    $psw = "";
    $database = "users";


    // Create Connection
    $conn = mysqli_connect($servername, $usr, $psw, $database);
    // Die if connection was not successful
    if (!$conn) {
        die("Sorry we failed to connect: " . mysqli_connect_error());
    } else {
        // Submit these to database
        // SQL query to execute
        $emailValidationQuery = "select email from tblusercredentials where email = $email";
        $result = mysqli_query($conn, $emailValidationQuery);
        $emailValidated = 0;
        if ($emailValidated == 0) {
            $time = get_Time();
            $insertQuery = "insert into tblusercredentialstemp (username,email,password,phone_number, shop_name, shop_link, created) VALUES ('$username','$email','$password','$phone_number', '$sitename', '$siteaddress', '$time')";
            $result = mysqli_query($conn, $insertQuery);

            if ($result) {
                echo "added successfully";
            } else {
                echo $time;
                echo "404 error";
            }
        }else{
            echo "<script>alert('Email already exist')</script>";
        }
    }
}

<?php

session_start();

// initializing variables
$name = "";
$email    = "";
$errors = array();

include 'mail.php';

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'users');


// LOGIN USER
if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($email)) {
        array_push($errors, "email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = ($password);
        $query = "call sel_tblusercredentials('" . $email . "','" . $password . "')";
        $results = mysqli_query($db, $query);
        if ($row = $results->fetch_array()) {
            if ($email === $row[0]) {
                if ($password === $row[2]) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $row[0];

                    header("location: profile");
                }else{
                    $_SESSION['loggedin'] = false;
                    echo '<script>alert("Invalid password ");</script>';
                }
            } else {
                $_SESSION['loggedin'] = false;
                echo '<script>alert("Invalid email address");</script>';
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>Login</title>
</head>

<body onload="pageLoad()">
    <div class="main">
        <div class="box-image">
            <img src="images/signpmain.jpg" alt="">
        </div>

        <div class="form">
            <form method="POST" action="login.php">
                <h1>Welcome Back</h1>
                <h4>Login to your Bitly account here</h4>
                <lable class="lable">Email</lable>
                <input type="email" placeholder="johndoe@gmail.com" name='email' required>
                <lable class="lable">Password</lable>
                <input type="password" name='password' placeholder="Please enter your password" style="margin-bottom: 40px;" required>
                <button type="submit" class="btn-submit" id="submit" name="login_user">Login</button>
            </form>
        </div>

    </div>
</body>
<script>
    function unmasking(password) {
        var x = document.getElementById(password);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>


</html>
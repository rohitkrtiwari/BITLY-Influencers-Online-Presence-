<?php

function get_Time(){
    date_default_timezone_set('Asia/calcutta');
    $time = date('H:i:s');
    return $time;
}

session_start();


// initializing variables
$name = "";
$email    = "";
$errors = array(); 

try{
    include 'mail.php';
}catch (Exception $e){
    echo"<script>alert('Can't connect to the server right now...);</script>";
    header("location: index.php");
}
// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'users');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $name = mysqli_real_escape_string($db, $_POST['name']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $phone_number = mysqli_real_escape_string($db, $_POST['phone_number']);
  $password = mysqli_real_escape_string($db, $_POST['password']);
  $site_name = mysqli_real_escape_string($db, $_POST['shop_name']);
  $site_lnk = mysqli_real_escape_string($db, $_POST['shop_lnk']);


  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($name)) { array_push($errors, "name is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password)) { array_push($errors, "Password is required"); }
  if (empty($site_name)) { array_push($errors, "Site Name is required"); }
  if (empty($site_lnk)) { array_push($errors, "Site Link is required"); }
  
  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "CALL tbl_ValidateExistingUser('$email')";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['email'] == $email) {
      array_push($errors, "email already exists");
    }
  }

  if (count($errors) == 0) {
    $return = Email_Varification($email);
    if ($return[0] == true)
      $token = $return[1];
      $time = get_Time();
      $password = md5($password);
      $insertQuery = "CALL 	ins_tblusercredentialstemp('".$name."','".$email."','".$password."','".$phone_number."', '".$site_name."', '".$site_lnk."', '".$time."', '".$token."', '0');";
      $db = mysqli_connect('localhost', 'root', '', 'users');
      $result = mysqli_query($db, $insertQuery);
      $password = md5($password);
      if ($result){
        header('location: login.php');
      }else{
        echo '<script>alert("Can not make the registration request now, Try again...")</script>';
        echo mysqli_error($db);
    }
  }
  else{
    echo var_dump($errors);
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
    <title>Sign Up</title>
</head>

<body>
    <div class="main">

        <div class="box-image">
            <img src="images/signpmain.jpg" alt="">
        </div>

        <div class="form">
            <form method="POST" action="">
                <div id="fst-form">
                    <h1>Let's get started</h1>
                    <h4>Signin with Your Email Address</h4>
                    <lable class="lable">Name</lable>
                    <input type="text" name='name' placeholder="John Doe" required>
                    <lable class="lable">Email</lable>
                    <input type="email" id="email" name='email' placeholder="johndoe@gmail.com">
                    <lable class="lable">Phone Number</lable>
                    <input type="text" name='phone_number' placeholder="optional" style="margin-bottom: 40px;">
                    <a href="#" class="btn-submit" id="submit" onclick="ValidateEmail()">CONTINUE</a>
                </div>


                <div id="snd-form">
                    <h1>Setup your password</h1>
                    <h4>Choose a password to signin to Bitly</h4>
                    <lable class="lable">Password</lable>
                    <div class="password-box">
                        <input type="password" name='password' id="password" placeholder="Choose your password">
                        <a toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password unmask-icon" onclick="unmasking('password')"></a>
                    </div>
                    <label class="lable">A strong password should contain: </label>
                    <ul class="password-strength-indicator__list">
                        <li id="strength" class="password-strength-meter" style="width: 134px;">8+ Characters</li>
                        <li id="lower" class="password-strength-meter" style="width: 164px;">Lowercase Letters</li>
                        <li id="number" class="password-strength-meter"style="width: 105px;">Numbers</li>
                        <li id="upper" class="password-strength-meter">Uppercase Letters</li>
                    </ul>
                    <br>
                    <a href="#" type="submit" class="btn-submit" id="submit" onclick="ValidatePassword()">CONTINUE</a>
                </div>


                <div id="thrd-form">
                    <h1>Setup your site</h1>
                    <h4>Help us customise your Bitly experience accordingly</h4>
                    <lable class="lable">Your Site's Name</lable>
                    <input type="text" name='shop_name' placeholder="codewithharry" required>
                    <lable class="lable">Your site's address</lable>
                    <input type="text" name='shop_lnk' placeholder="codewithharry.com" style="margin-bottom: 40px;">
                    <button type="submit" class="btn-submit" name="reg_user" id="submit">SIGN UP</button>
                </div>
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

<script>
    var myInput = document.getElementById("password");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");


    // When the user starts to type something inside the password field
    myInput.onkeyup = function() {
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if (myInput.value.match(lowerCaseLetters)) {
            document.getElementById("lower").style.color = "rgb(80, 105, 59)";
        } else {
            document.getElementById("lower").style.color = "rgb(182, 174, 174)";
        }

        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if (myInput.value.match(upperCaseLetters)) {
            document.getElementById("upper").style.color = "rgb(80, 105, 59)";
        } else {
            document.getElementById("upper").style.color = "rgb(182, 174, 174)";
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if (myInput.value.match(numbers)) {
            document.getElementById("number").style.color = "rgb(80, 105, 59)";
        } else {
            document.getElementById("number").style.color = "rgb(182, 174, 174)";
        }

        // Validate length
        if (myInput.value.length >= 8) {
            document.getElementById("strength").style.color = "rgb(80, 105, 59)";
        } else {
            document.getElementById("strength").style.color = "rgb(182, 174, 174)";
        }
    }

    function ValidateEmail() {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("email").value)) {
            document.getElementById("fst-form").style.display = "none";
            document.getElementById("snd-form").style.display = "block";
        } else {
            alert("Enter Correct email id");
        }
    }


    function ValidatePassword() {
        var strength = document.getElementById('strength').style.color;
        var lower = document.getElementById('lower').style.color;
        var upper = document.getElementById('upper').style.color;
        var number = document.getElementById('number').style.color;
        if (lower === 'rgb(80, 105, 59)') {
            if (upper === 'rgb(80, 105, 59)') {
                if (number === 'rgb(80, 105, 59)') {
                    if (strength === 'rgb(80, 105, 59)') {
                        document.getElementById('snd-form').style.display = "none";
                        document.getElementById('thrd-form').style.display = "block";
                    } else {
                        alert("Password must contain 8 characters");
                    }
                } else {
                    alert("Password must contains a Number");
                }
            } else {
                alert("Password must contain an Uppercase Letter");
            }
        } else {
            alert("Password must contain a Lowercase Letter")
        }
    }
</script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="/__/firebase/7.16.0/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="/__/firebase/7.16.0/firebase-analytics.js"></script>

<!-- Initialize Firebase -->
<script src="/__/firebase/init.js"></script>

</html>


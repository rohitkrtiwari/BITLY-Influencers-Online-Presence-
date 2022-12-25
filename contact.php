<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/contact.css">
    <title>Bitly</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Contact Us</title>
</head>

<body>
    <nav class="navbar">
        <div class="grid">
            <a href="" class="navbar__logo"><img src="images/logo1.png" alt="logo">
                <h2>Bitly</h2>
            </a>
            <div class="navbar__list">
                <a href="index.php" id="home"> HOME</a>
                <a href="#services" id="services"> SERVICES</a>
                <a href="#about" id="about"> ABOUT</a>
                <a href="contact.php" id="contact"> CONTACT US</a>
                <a href="#" id="search"><i class="fa fa-search"></i></a>
                <input type="search" placeholder="Search your favouret here..." class="search-bar" id="search-bar">
                <a id="close-icon" href="#" style="display:none;">X</a>
            </div>
            <div class="u-pull-right">
                <a href="login.php" class="login">LOGIN</a>
                <a href="signup.php" class="signup">SIGN UP</a>
            </div>
        </div>
    </nav>

    <div class="main-grid">
        <div class="contact__heading">
            <h1 class="contact-heading">Contact Us</h1>
        </div>
        <div class="contact__form">
            <form method="post">
                <lable class="lable">Name</lable>
                <select id="cars" class="contact-form__select" name="cars">
                    <option value="volvo">Volvo</option>
                    <option value="saab">Saab</option>
                    <option value="fiat">Fiat</option>
                    <option value="audi">Audi</option>
                </select>
                <lable class="lable">Your Email</lable>
                <input type="text" class="contact-form__input" name='username' placeholder="John Doe" required>
                <lable class="lable">Your Message</lable>
                <textarea name="message" class="contact-form__textarea" id="#message" cols="30" rows="10"></textarea>
                <button type="submit" class="btn-submit" id="submit">SEND MESSAGE</button>
            </form>
        </div>
    </div>

</body>
<script>
    $(document).ready(function() {
        $("#search").click(function() {
            $("#home, #services, #about, #contact, #search").hide(100);
            $("#search-bar, #close-icon").show(100);
        });
        $("#close-icon").click(function() {
            $("#search-bar, #close-icon").hide();
            $("#home, #services, #about, #contact, #search").show(200);
        });
    });
</script>

</html>


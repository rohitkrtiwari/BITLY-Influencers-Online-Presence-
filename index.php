<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Bitly</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

    <div class="header">
        <nav class="navbar navbar-light">
            <div class="grid">
                <a href="" class="navbar__logo">
                    <img src="images/logo1.png" alt="logo">
                    <h2>Bitly</h2>
                </a>
                <div class="navbar__list">
                    <a href="#home" id="home"> HOME</a>
                    <a href="#services" id="services"> SERVICES</a>
                    <a href="#about" id="about"> ABOUT</a>
                    <a href="contact" id="contact"> CONTACT US</a>
                    <a href="#" id="search"><i class="fa fa-search"></i></a>
                    <input type="search" placeholder="Search your favoirite here..." class="search-bar" id="search-bar">
                    <a id="close-icon" href="#" style="display:none;">X</a>
                </div>
                <div class="u-pull-right">
                    <a href="login" class="login">LOGIN</a>
                    <a href="signup" class="signup">SIGN UP</a>
                </div>
            </div>

            <a href="#" class="burger" id="burger">
                <div class="lin"></div>
                <div class="lin"></div>
                <div class="lin"></div>
            </a>

            <div class="burger-nav" id="burger-nav">
                <a href="#home" class="burger-nav__list" id="home"> HOME</a>
                <a href="#services" class="burger-nav__list" id="services"> SERVICES</a>
                <a href="#about" class="burger-nav__list" id="about"> ABOUT</a>
                <a href="contact" class="burger-nav__list" id="contact"> CONTACT US</a>
                <div class="login__signup">
                    <a href="login" class="burger-nav__list burger-nav__login">LOGIN</a><br>
                    <a href="signup" class="burger-nav__list burger-nav__signup">SIGN UP</a>
                </div>
            </div>
        </nav>


        <div class="main_body">
            <div class="paragraph">
                <h1>Wanna know More About Your Favourite Youtube Star</h1>
                <h3>Search your digital star here</h3>
            </div>

            <div class="search_section">
                <input type="text" class="body_search_bar" name="" id="" placeholder="Search by name or channel link">
                <button class="body_search_btn">Search</button>
            </div>
        </div>

        <div class="responsive_body">
            <div class="res_search_section">
                <input type="text" class="res_body_search_bar" name="" id="" placeholder="Search by name or channel link">
                <button class="res_body_search_btn"><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </div>


    </div>
</body>
<script>
    $(document).ready(function() {
        $("#burger-nav").hide()
        $("#search").click(function() {
            $("#home, #services, #about, #contact, #search").hide(100);
            $("#search-bar, #close-icon").show(100);
        });
        $("#close-icon").click(function() {
            $("#search-bar, #close-icon").hide();
            $("#home, #services, #about, #contact, #search").show(200);
        });
        $("#burger").click(function() {
            $("#burger-nav").toggle(200);
        });
    });
</script>


</html>
<?php

if (isset($_GET['email'])) {
    $useremail = $_GET['email'];
    if (isset($_GET['title'])) {
        $post_title = $_GET['title'];
        $post_requested = true;
    } else {
        $post_requested = false;
    }
} else {
    echo $_GET['email'];
    die("404 error");
}
$db = mysqli_connect('localhost', 'root', '', 'users');
$getUserDataSql = "call sel_UserCredentials('" . $useremail . "')";
$UserDataSQLresult = mysqli_query($db, $getUserDataSql);
$UserDataSQLNum = mysqli_num_rows($UserDataSQLresult);
if ($UserDataSQLNum == 1) {
    if ($row = $UserDataSQLresult->fetch_array()) {
        $name = $row['name'];
        $ProfilePicture = $row['profile_pic'];
        $site_name = $row['site_name'];
        $site_link = $row['site_link'];
    }
} else {
    header('location: index.php');
}


require '_dbconnect.php';
$publishedSQL = "call sel_tblUserPostsPublished('" . $useremail . "')";
if (isset($conn)) {
    $publishedSQLresult = mysqli_query($conn, $publishedSQL);
    $publishedSQLCount = mysqli_num_rows($publishedSQLresult);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <title><?php echo $site_name; ?></title>
</head>

<body class="bg-photo">

    <div class="body">


        <!-- ********************* PROFILE_VIEW  ********************* -->
        <script src="js/user_profile.js"></script>
        <?php
        if ($post_requested == false) { ?>

            <div class="dim-overlay hidden" id="dim-overlay"></div>
            <div class="background"></div>

            <div class="nav">
                <div class="sidenav_btn" onclick="SideNav('show')">
                    <div class="btn_line"></div>
                    <div class="btn_line"></div>
                    <div class="btn_line"></div>
                </div>
                <div class="sidenav_search-bar">
                    <i class="fa fa-search mgr-10" id="search_ico" onclick="SearchBar()"></i>
                    <form action="" class="search_form" method="get">
                        <div class="search_box" id="search_box">
                            <input type="search" onkeyup="checkValue()" placeholder="search this blog" name="post_search" class="post_search" id="post_search">
                            <input type="submit" class="search_submit_btn" id="search_btn" value="SEARCH">
                        </div>
                    </form>
                </div>
            </div>

            <div class="sidenav" id="sidenav">
                <i class="fa fa-arrow-left back_arrow" onclick="SideNav('hide')"></i>
                <img src="<?php echo $ProfilePicture; ?>" class="profile_size" alt=""><br><br><br><br><br>
                <h3 class="active"> <?php echo $name; ?></h3>
            </div>

            <div class="main_body">
                <div class="header_body">
                    <h3 class="active Title_heading"><?php echo $site_name; ?></h3>
                    <h3 class="title_subHeading">My online refrence where I Dump all of my Codes</h3>
                </div>
                <div class="user_posts">
                    <?php
                    if ($publishedSQLCount == 0) {
                        echo '<div class="no_posts">';
                        echo '<h1 class="active">No Posts Found</h1>';
                        echo '</div>';
                    }
                    while ($draftRow = $publishedSQLresult->fetch_array()) {
                        echo '<div class="user_post">';
                        echo '<h3 class="post_heading"><a href="?email=' . $useremail . '&title=' . $draftRow['title'] . '" >' . $draftRow["title"] . '</a></h3>';
                        echo '<span class="post_date">-' . $draftRow["date"] . '</span>';
                        echo '<a href="' . $draftRow['image'] . '" target="_blank" class="post_image_link"><img class="post_image" src="' . $draftRow['image'] . '"></a>';
                        echo '<p class="post_link" ><span id="' . $draftRow['id'] . '">' . $draftRow['link'] . '</span><button id="' . $draftRow['id'] . '_copy" class="link_copy" onclick="copy(' . $draftRow['id'] . ')">copy</button></p>';
                        echo '<p class="discription">' . $draftRow['discription'] . '</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        <?php } ?>


        <!-- ********************* POST_VIEW ********************* -->

        <?php
        if ($post_requested == true) { ?>

            <div class="heading_bar">
                <a class="fa fa-arrow-left back_btn" href="?email=<?php echo $useremail; ?>"></a>
                <div class="post_header_body">
                    <h3 class="post_title_heading"><?php echo $site_name; ?></h3>
                    <h3 class=" post_title_subheading">My online refrence where I Dump all of my Codes</h3>
                </div>
            </div>

            <div class="user_posts">
                <?php
                while ($draftRow = $publishedSQLresult->fetch_array()) {
                    if ($draftRow['title'] == $post_title) {
                        echo '<div class="user_post">';
                        echo '<h3 class="post_heading"><a href="?email=' . $useremail . '&title=' . $draftRow['title'] . '" >' . $draftRow["title"] . '</a></h3>';
                        echo '<span class="post_date">-' . $draftRow["date"] . '</span>';
                        echo '<a href="' . $draftRow['image'] . '" target="_blank" class="post_image_link"><img class="post_image" src="' . $draftRow['image'] . '"></a>';
                        echo '<p class="post_link" ><span id="' . $draftRow['id'] . '">' . $draftRow['link'] . '</span><button id="' . $draftRow['id'] . '_copy" class="link_copy" onclick="copy(' . $draftRow['id'] . ')">copy</button></p>';
                        echo '<p class="discription">' . $draftRow['discription'] . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        <?php } ?>

    </div>

</body>

</html>
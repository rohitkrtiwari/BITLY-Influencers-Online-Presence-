<?php

function reload()
{
    unset($_GET);
    $page = "profile.php";
    $sec = "0";
    header("Refresh: $sec; url=$page");
}

function get_date()
{
    date_default_timezone_set('Asia/calcutta');
    $time = date('h:i:s');
    $date = date("d/m/Y");
    return array($time, $date);
}

session_start();
if ($_SESSION["loggedin"] != true) {
    header("location: login");
}
if (isset($_GET['logout'])) {
    session_reset();
    session_destroy();
    header("location: login");
}

if (isset($_SESSION['loggedin'])) {
    $email = $_SESSION['email'];
    $db = mysqli_connect('localhost', 'root', '', 'users');
    $getUserDataSql = "call sel_UserCredentials('" . $email . "')";
    $UserDataSQLresult = mysqli_query($db, $getUserDataSql);
    if ($row = $UserDataSQLresult->fetch_array()) {
        $name = $row['name'];
        $ProfilePicture = $row['profile_pic'];
        $site_name = $row['site_name'];
        $site_link = $row['site_link'];
    }
    require '_dbconnect.php';
    $publishedSQL = "call sel_tblUserPostsPublished('" . $email . "')";
    if (isset($conn)) {
        $publishedSQLresult = mysqli_query($conn, $publishedSQL);
        $PublishedCounts = mysqli_num_rows($publishedSQLresult);
    }
    $draftSQL = "call sel_tblUserPostsDraft('" . $email . "')";
    if (isset($conn)) {
        $conn = mysqli_connect('localhost', 'root', '', 'users');
        $draftSQLresult = mysqli_query($conn, $draftSQL);
        $DraftCounts = mysqli_num_rows($draftSQLresult);
    }
    $postsSQL = "call sel_tblUserPosts('" . $email . "')";
    if (isset($conn)) {
        $conn = mysqli_connect('localhost', 'root', '', 'users');
        $postsSQLresult = mysqli_query($conn, $postsSQL);
        $PostsCounts = mysqli_num_rows($postsSQLresult);
    }
}
if ($conn) {
    if (isset($_POST['publish_POST'])) {
        $db = mysqli_connect('localhost', 'root', '', 'users');
        $title = $_POST['title'];
        $datetime = get_date();
        $link = $_POST['link'];
        $location = '';
        $discription = $_POST['discription'];
        if ($_FILES['uploadNewImage']['name'] != '') {
            $filename = ($_FILES['uploadNewImage']['name']);
            $temp_name = $_FILES['uploadNewImage']['tmp_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext == 'gif' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                $mid_name = bin2hex(random_bytes(10));
                $location = "uploads/" . $email . "_" . $mid_name . "_" . $filename;
                move_uploaded_file($temp_name, $location);
            } else {
                echo "<script>alert('image format is wrong ');</script>";
                die();
            }
        }
        $sql = "call ins_UserPosts ('" . $email . "', '" . $title . "', '" . $link . "', '" . $discription . "', '" . $datetime[0] . "', '" . $datetime[1] . "', '1' , '" . $location . "')";
        $post_result = mysqli_query($db, $sql);
        if ($post_result) {
            echo "<script>alert('Posted Successfully')</script>";
            reload();
        } else {
            echo "failed";
            echo mysqli_error($conn);
        }
    }

    if (isset($_POST['draft_POST'])) {
        $db = mysqli_connect('localhost', 'root', '', 'users');
        $title = $_POST['title'];
        $datetime = get_date();
        $link = $_POST['link'];
        $location = '';
        $discription = $_POST['discription'];
        if ($_FILES['uploadNewImage']['name'] != '') {
            $filename = ($_FILES['uploadNewImage']['name']);
            $temp_name = $_FILES['uploadNewImage']['tmp_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext == 'gif' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                $mid_name = bin2hex(random_bytes(10));
                $location = "uploads/" . $email . "_" . $mid_name . "_" . $filename;
                move_uploaded_file($temp_name, $location);
            } else {
                echo "<script>alert('image format is wrong ');</script>";
                die();
            }
        }
        $sql = "call ins_UserPosts ('" . $email . "', '" . $title . "', '" . $link . "', '" . $discription . "', '" . $datetime[0] . "', '" . $datetime[1] . "', '0' , '" . $location . "')";
        $post_result = mysqli_query($db, $sql);
        if ($post_result) {
            echo "<script>alert('Saved to Drafts Successfully')</script>";
            reload();
        } else {
            echo "failed";
            echo mysqli_error($conn);
        }
    }
}

if (isset($_POST['deleteSelected'])) {
    $checkbox = $_POST['checked'];
    $db = mysqli_connect('localhost', 'root', '', 'users');
    foreach ($checkbox as $chk) {
        $sql = "call del_UserPosts ('" . $chk . "', '" . $email . "')";
        $deleted = mysqli_query($db, $sql);
        reload();
        // while ($PostsRow = $postsSQLresult->fetch_array()) {
        //     if ($chk == $PostsRow['id']){
        //         $deleteImage = $PostsRow['image'];
        //     }
        // }
        // unlink($deleteImage);
    }
    if ($deleted) {
        echo '<script>alert("Deleted succesfully");</script>';
    } else {
        echo '<script>alert("Operation Failed");</script>';
        reload();
    }
}

if (isset($_POST['publishSelected'])) {
    $checkbox = $_POST['checked'];
    $db = mysqli_connect('localhost', 'root', '', 'users');
    foreach ($checkbox as $chk) {
        $sql = "call udt_UserPostsPublish ('" . $chk . "', '" . $email . "')";
        $published = mysqli_query($db, $sql);
        reload();
    }
    if ($published) {
        echo '<script>alert("Published succesfully");</script>';
    } else {
        echo '<script>alert("Operation Failed");</script>';
        reload();
    }
}



if (isset($_POST['draftSelected'])) {
    $checkbox = $_POST['checked'];
    $db = mysqli_connect('localhost', 'root', '', 'users');
    foreach ($checkbox as $chk) {
        $sql = "call udt_UserPostsDraft ('" . $chk . "', '" . $email . "')";
        $drafted = mysqli_query($db, $sql);
        reload();
    }
    if ($drafted) {
        echo '<script>alert("Saved to Draft");</script>';
    } else {
        echo '<script>alert("Operation Failed");</script>';
        reload();
    }
}

if (isset($_GET['delete'])) {
    error_reporting(0);
    $deleteID = $_GET['delete'];
    $db = mysqli_connect('localhost', 'root', '', 'users');
    $sql = "call del_UserPosts ('" . $deleteID . "', '" . $email . "')";
    // while ($PostsRow = $postsSQLresult->fetch_array()) {
    //     if ($deleteID == $PostsRow['id']){
    //         $deleteImage = $PostsRow['image'];
    //     }
    // }
    // unlink($deleteImage);
    // $deleted = mysqli_query($db, $sql);
    reload();
    if ($deleted) {
        echo '<script>alert("Deleted succesfully");</script>';
    } else {
        echo '<script>alert("Operation Failed");</script>';
        reload();
    }
}

if (isset($_GET['edit'])) {
    $editID = $_GET['edit'];
    $_GET['edit'] = '';
    $db = mysqli_connect('localhost', 'root', '', 'users');
    $sql = "call sel_tblUserPostsbyId ('" . $editID . "', '" . $email . "')";
    $data = mysqli_query($db, $sql);
    if ($row = $data->fetch_array()) {
        $title = $row['title'];
        $imageName = $row['image'];
        $link = $row['link'];
        $discription = $row['discription'];
        $_SESSION['update'] = true;
    }
}

if (isset($_POST['update_POST'])) {
    error_reporting(0);
    $db = mysqli_connect('localhost', 'root', '', 'users');
    $title = $_POST['title'];
    $link = $_POST['link'];
    $datetime = get_date();
    $mid_name = bin2hex(random_bytes(10));
    $discription = $_POST['discription'];
    if ($_FILES['updateNewImage']['name'] != '') {
        $filename = ($_FILES['updateNewImage']['name']);
        $temp_name = ($_FILES['updateNewImage']['tmp_name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext == 'gif' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
            unlink($imageName);
            $location = "uploads/" . $email . "_" . $mid_name . "_" . $filename;
            move_uploaded_file($temp_name, $location);
        }
    }
    $sql = "call udt_UserPosts ('" . $editID . "', '" . $email . "', '" . $title . "', '" . $link . "', '" . $discription . "', '" . $datetime[0] . "', '" . $datetime[1] . "', '1', '" . $location . "')";
    $post_udt_result = mysqli_query($db, $sql);
    if ($post_udt_result) {
        echo "<script>alert('Updated Successfully')</script>";
        reload();
    } else {
        echo "failed";
        echo mysqli_error($conn);
    }
}


if (isset($_POST['update_DRAFT'])) {
    $db = mysqli_connect('localhost', 'root', '', 'users');
    $title = $_POST['title'];
    $link = $_POST['link'];
    $datetime = get_date();
    $discription = $_POST['discription'];
    if ($_FILES['updateNewImage']['name'] != '') {
        $filename = ($_FILES['updateNewImage']['name']);
        $temp_name = ($_FILES['updateNewImage']['tmp_name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext == 'gif' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
            $mid_name = bin2hex(random_bytes(10));
            $location = "uploads/" . $email . "_" . $mid_name . "_" . $filename;
            unlink($imageName);
            move_uploaded_file($temp_name, $location);
        }
    }
    $sql = "call udt_UserPosts ('" . $editID . "', '" . $email . "', '" . $title . "', '" . $link . "', '" . $discription . "', '" . $datetime[0] . "', '" . $datetime[1] . "', '0', '" . $location . "')";
    $post_udt_result = mysqli_query($db, $sql);
    if ($post_udt_result) {
        echo "<script>alert('Updated Successfully')</script>";
        reload();
    } else {
        echo "failed";
        echo mysqli_error($conn);
    }
}



if (isset($_POST['profile_UPDATE'])) {
    error_reporting(0);
    if ($_POST['udt_name'] == null && $_POST['udt_sitename'] == null && $_POST['udt_sitelink'] == null && $_POST['udt_email'] == null) {
    } else {
        if ($_POST['udt_name'] != '') {
            $udt_name = $_POST['udt_name'];
        } else {
            $udt_name = $name;
        }

        if ($_POST['udt_sitename'] != null) {
            $udt_site_name = $_POST['udt_sitename'];
        } else {
            $udt_site_name = $site_name;
        }

        if ($_POST['udt_sitelink'] != null) {
            $udt_site_link = $_POST['udt_sitelink'];
        } else {
            $udt_site_link = $site_link;
        }

        if ($_POST['udt_email'] != null) {
            $udt_email = $_POST['udt_email'];
        } else {
            $udt_email = $email;
        }

        $db = mysqli_connect('localhost', 'root', '', 'users');
        $sql = "call udt_UserDetails ('" . $udt_name . "', '" . $udt_email . "', '" . $udt_site_name . "', '" . $udt_site_link . "', '" . $email . "')";
        $profile_udt_result = mysqli_query($db, $sql);
        if ($profile_udt_result) {
            echo "<script>alert('Profile Updated Successfully')</script>";
            reload();
        } else {
            echo mysqli_error($db);
            echo "<script>alert('Profile Update error')</script>";
        }
    }
}

if (isset($_POST['profile_UPDATE'])) {
    error_reporting(0);
    if ($_FILES['udt_profile_photo']['name'] != '') {
        $filename = ($_FILES['udt_profile_photo']['name']);
        $temp_name = ($_FILES['udt_profile_photo']['tmp_name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext == 'gif' || $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
            $mid_name = bin2hex(random_bytes(10));
            $NewProfilelocation = "uploads/profile_photos/" . $email . "_" . $mid_name . "_" . $filename;
            // unlink($imageName);
            move_uploaded_file($temp_name, $NewProfilelocation);

            $db = mysqli_connect('localhost', 'root', '', 'users');
            $sql = "call udt_UserProfilePicture ('" . $NewProfilelocation . "', '" . $email . "')";
            $profile_udt_result = mysqli_query($db, $sql);
            if ($profile_udt_result) {
                unlink($ProfilePicture);
                echo "<script>alert('Profile Picture Updated Successfully')</script>";
                reload();
            } else {
                echo mysqli_error($db);
                echo "<script>alert('Profile Picture Update error')</script>";
            }
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/profile.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title><?php echo $name ?> | Home</title>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body onload="PageLoad()">

    <script>
        function reloadJS() {
            var curr_location = location.protocol + '//' + location.host + location.pathname;
            window.location.href = curr_location;
        }

        function PageLoad() {
            var update = '<?php echo $_SESSION['update']; ?>'
            var title = '<?php echo $title; ?>'
            if (update == true) {
                <?php $_SESSION['update'] = false; ?>
                document.getElementById("main_body").style.display = "none";
                document.getElementById("edit_form").style.display = "block ";
            }
        }
    </script>

    <!-- ************************ Navigation Bar ************************ -->

    <nav class="navbar">
        <span style="font-size:30px;cursor:pointer;color:white;margin: 8px 13px;" class="humburger_btn" onclick="openNav()">&#9776;</span>
        <div class="navbar-logo">
            <a href="" class="navbar__logo">Bitly</a>
        </div>
        <form id="search_post_form" style="width: 48%;">
            <div class="search-bar_div">
                <i class="fa fa-search search-icon" aria-hidden="true"></i>
                <input type="search" id="search_post_input" placeholder="Search bar" class="search-bar" require>
                <div id="search_output" style="display:none"></div>
            </div>
        </form>
        <div class="u-pull-right">
            <div class="username" onclick="showSettings()">
                <img class="profileIco" src="<?php echo $ProfilePicture; ?>">
                <h4><?php echo $name; ?></h4>
            </div>
            <div class="logout"><a href="profile.php?logout=true">LOGOUT</a></div>
        </div>
    </nav>

    <!-- ************************ Side Menu ************************ -->

    <div class="sidenav">
        <ul class="sidenav__menu">
            <li class=" sidenav_newPost">
                <a href="#" id="newPost" class="newPost">New Post</a>
            </li>
            <li class="sidenav__menuitem" id="posts">
                <div class="menuColor" onclick="showAll()">
                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    Posts
                </div>
                <ul class="sidenav_submenu">
                    <a class="menuColor" href="#" id="allposts" onclick="showAll()">All (<?php echo $PostsCounts; ?>)</a>
                    <a class="menuColor" href="#" id="published" onclick="showPublished()">Published (<?php echo $PublishedCounts; ?>)</a>
                    <a class="menuColor" href="#" id="draft" onclick="showDrafts()">Draft (<?php echo $DraftCounts; ?>)</a>
                </ul>
            </li>
            <li class="sidenav__menuitem menuColor">
                <a href="#" class="menuColor" id="stats"><i class="fa fa-bar-chart" aria-hidden="true"></i> Stats</a>
            </li>
            <li class="sidenav__menuitem menuColor">
                <a href="#" class="menuColor" id="comments"><i class="fa fa-comments-o" aria-hidden="true"></i> Comments</a>
            </li>
            <li class="sidenav__menuitem menuColor">
                <a href="#" onclick="showSettings()" class="menuColor" id="settings"><i class="fa fa-cogs" aria-hidden="true"></i> Settings</a>
            </li>
        </ul>
    </div>

    <!-- ************************ Side Menu Responsive ************************ -->

    <div id="mySidenav" class="Humburger_sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <ul class="sidenav__menu">
            <li class=" sidenav_newPost">
                <a href="#" id="newPostRes" class="newPost">New Post</a>
            </li>
            <li class="sidenav__menuitem" id="posts">
                <div class="menuColor" onclick="showAll()">
                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    Posts
                </div>
                <ul class="sidenav_submenu">
                    <a class="menuColor Humburger_sidenav_a" href="#" id="allposts" onclick="showAll()">All (<?php echo $PostsCounts; ?>)</a>
                    <a class="menuColor Humburger_sidenav_a" href="" id="published" onclick="showPublished()">Published (<?php echo $PublishedCounts; ?>)</a>
                    <a class="menuColor Humburger_sidenav_a" href="" id="draft" onclick="showDrafts()">Draft (<?php echo $DraftCounts; ?>)</a>
                </ul>
            </li>
            <li class="sidenav__menuitem menuColor">
                <i class="fa fa-bar-chart" aria-hidden="true"></i>
                Stats
            </li>
            <li class="sidenav__menuitem menuColor">
                <i class="fa fa-comments-o" aria-hidden="true"></i>
                Comments
            </li>
            <li class="sidenav__menuitem menuColor" onclick="showSettings()">
                <i class="fa fa-cogs" aria-hidden="true"></i>
                Settings
            </li>
        </ul>
    </div>


    <!-- ************************ Main Body ************************ -->


    <div class="main-body" id="main_body">

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nill" value="1" />
            <div class="posts_control-menu">
                <div class="posts_control-list">
                    <input class="posts_control" type="checkbox" name="" id="" onClick="toggle(this)">
                    <input class="publish posts_control" type="submit" name="publishSelected" onClick="return checkSelected('publish')" value="Publish">
                    <input class="draft posts_control" type="submit" name="draftSelected" onClick="return checkSelected('draft')" value="Revert to Draft">
                    <input class="delete posts_control" type="submit" name="deleteSelected" onClick="return checkSelected('delete')" value="Delete">
                </div>
                <hr>
            </div>

            <div id="all_posts">
                <?php
                error_reporting(0);
                while ($PostsRow = $postsSQLresult->fetch_array()) {
                    $id = $PostsRow['id'];
                    echo '<div class="user_posts">';
                    echo '<input class="posts_control checkPosts confirm" type="checkbox" name="checked[]" value="' . $id . '">';
                    echo '<a class="user_posts_a" target="_blank" href="' . $PostsRow['link'] . '"><li class="post_title">' . $PostsRow['title'] . '</li></a>';
                    echo '<div class="posts_control_bottom">';
                    echo '<br><a href="user.php?email='.$email.'" target="_blank" style=""  "id="posts_min_options">view </a>';
                    echo '<a href="?edit=' . $PostsRow['id'] . '" onclick="openEditForm()" id="edit">edit </a>';
                    echo '<a href="?delete=' . $PostsRow['id'] . '" onclick="return deleteConfimation()" "id="posts_min_options">delete</a>';
                    echo '</div>';
                    echo '<div class="posts_control_right">';
                    echo '<h1>' . $site_name . '</h1>';
                    echo '<div class="right_column"><br>' . $PostsRow['views'] . ' <i class="fa fa-eye" aria-hidden="true"></i></div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <div id="draft_posts">
                <?php
                error_reporting(0);
                while ($draftRow = $draftSQLresult->fetch_array()) {
                    $id = $draftRow['id'];
                    echo '<div class="user_posts">';
                    echo '<input class="posts_control checkPosts confirm" type="checkbox" name="checked[]" value="' . $id . '" >';
                    echo '<a class="user_posts_a" target="_blank" href="' . $draftRow['link'] . '"><li class="post_title">' . $draftRow['title'] . '</li></a>';
                    echo '<div class="posts_control_bottom">';
                    echo '<br><a href="user.php?email='.$email.'" target="_blank" style=""  "id="posts_min_options">view </a>';
                    echo '<a href="?edit=' . $draftRow['id'] . '" onclick="openEditForm()"  "id="posts_min_options">edit </a>';
                    echo '<a href="?delete=' . $draftRow['id'] . '" style=""  "id="posts_min_options">delete</a>';
                    echo '</div>';
                    echo '<div class="posts_control_right">';
                    echo '<h1>' . $site_name . '</h1>';
                    echo '<div class="right_column"><br>' . $draftRow['views'] . ' <i class="fa fa-eye" aria-hidden="true"></i></div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <div id="published_posts">
                <?php
                error_reporting(0);
                while ($PublishedRow = $publishedSQLresult->fetch_array()) {
                    $id = $PublishedRow['id'];
                    echo '<div class="user_posts">';
                    echo '<input class="posts_control checkPosts confirm" type="checkbox" name="checked[]" value="' . $id . '" class="" >';
                    echo '<a class="user_posts_a" target="_blank" href="' . $PublishedRow['link'] . '"><li>' . $PublishedRow['title'] . '</li></a>';
                    echo '<div class="posts_control_bottom">';
                    echo '<br><a href="user.php?email='.$email.'" target="_blank" style=""  "id="posts_min_options">view </a>';
                    echo '<a href="?edit=' . $PublishedRow['id'] . '" style=""  "id="posts_min_options">edit </a>';
                    echo '<a href="?delete=' . $PublishedRow['id'] . '" style=""  "id="posts_min_options">delete</a>';
                    echo '</div>';
                    echo '<div class="posts_control_right">';
                    echo '<h1>' . $site_name . '</h1>';
                    echo '<div class="right_column"><br>' . $PublishedRow['views'] . ' <i class="fa fa-eye" aria-hidden="true"></i></div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </form>

    </div>

    <!-- ************************ New Post Form *********************** -->

    <div class="newpost_form" id="newpost_form">
        <form action="profile.php" method="post" onclick="formValidate()" enctype="multipart/form-data">

            <div class="newpost_post_controls">
                <a class="fa fa-arrow-left fa-2x back_btn" onclick="closeNewPostForm('reload')" id="edit_back_btn" aria-hidden="true"></a>
                <div class="newpost_save_btn">
                    <button href="#" name="draft_POST" type="submit" class="newpost_draft">Save to Draft</button>
                    <button href="#" name="publish_POST" type="submit" class="newpost_publish">Publish</button>
                </div>
            </div>

            <div class="newpost_form_body">
                <lable class="newpost_form_lable ">Title</lable>
                <input type="text" name="title" id="newPost_title" class="newpost_form_input_md " required>
                <input type="file" id="NewImage" name="uploadNewImage" size="60" class="image" />
                <lable class="newpost_form_lable ">Link</lable><br>
                <input type="text" name="link" id="link" class="newpost_form_input_md needs-Validation ">
                <lable class="newpost_form_lable ">Discription</lable>
                <textarea class="newpost_post_discription" name="discription" id="" cols="80" rows="4"></textarea>
            </div>
        </form>
    </div>

    <!-- ************************ Edit Form ************************ -->

    <div class="edit newpost_form" id="edit_form">
        <form action="" method="post" onclick="formValidate()" enctype="multipart/form-data">
            <div class="newpost_post_controls" id="edit_form_control">
                <a class="fa fa-arrow-left fa-2x back_btn" href="#" id="back_btn" onclick="closeEditForm('reload')" aria-hidden="true"></a>
                <div class="newpost_save_btn">
                    <button href="#" name="update_DRAFT" type="submit" class="newpost_draft">Save to Draft</button>
                    <button href="#" name="update_POST" type="submit" class="newpost_publish">Update</button>
                </div>
            </div>

            <div class="newpost_form_body">
                <lable class="newpost_form_lable ">Title</lable>
                <input type="text" name="title" id="newPost_title" class="newpost_form_input_md " value="<?php echo $title; ?>" required>
                <div class="imageSection">
                    <a href="<?php echo $imageName; ?>" target="_blank"> <img src="<?php echo $imageName; ?>" alt="" class="imageUpload"></a>
                    <input type="file" name="updateNewImage" size="60" class="image" src="">
                </div>
                <lable class="newpost_form_lable ">Link</lable><br>
                <input type="text" name="link" id="link" class="newpost_form_input_md needs-Validation" value="<?php echo $link; ?>">
                <lable class="newpost_form_lable ">Discription</lable>
                <textarea class="newpost_post_discription" name="discription" id="" cols="80" rows="4"><?php echo $discription; ?></textarea>
            </div>
        </form>
    </div>


    <!-- Settings Section -->

    <div class="settings_Section" id='settings_Section'>

        <div class="settings_header-block">
            <h3>User Profile</h3>
        </div>

        <!-- ********************* Profile Edit Form ********************* -->
        <form action="" method="post" enctype="multipart/form-data">

            <div class="form_row">
                <div class="labdiv">
                    <label class="form_row_lable">Profile</label>
                </div>
                <div class="inputdiv profile_photo_section">
                    <img class="profile-picture" src="<?php echo $ProfilePicture; ?>">
                    <input type="file" name="udt_profile_photo" id="udt_profile_photo" class="photoUpload">
                </div>
            </div>


            <div class="form_row">
                <div class="labdiv">
                    <label class="form_row_lable">Name</label>
                </div>
                <div class="inputdiv">
                    <input class="form_row_input" type="text" name="udt_name" id="udt_name" placeholder="<?php echo $name; ?>" disabled>
                    <i class="fa fa-pencil-square-o edit-icon" onclick="edit('udt_name')" aria-hidden="true"></i>
                </div>
            </div>

            <div class="form_row">
                <div class="labdiv">
                    <label class="form_row_lable">email</label>
                </div>
                <div class="inputdiv">
                    <input class="form_row_input" type="text" value="" name="udt_email" id="udt_email" placeholder="<?php echo $email; ?>" disabled>
                    <i class="fa fa-pencil-square-o edit-icon" onclick="edit('udt_email')" aria-hidden="true"></i>
                </div>
            </div>

            <div class="form_row">
                <div class="labdiv">
                    <label class="form_row_lable">Website </label>
                </div>
                <div class="inputdiv">
                    <input class="form_row_input" type="text" value="" name="udt_sitename" id="udt_sitename" placeholder="<?php echo $site_name; ?>" disabled>
                    <i class="fa fa-pencil-square-o edit-icon" onclick="edit('udt_sitename')" aria-hidden="true"></i>
                </div>
            </div>

            <div class="form_row">
                <div class="labdiv">
                    <label class="form_row_lable">Web Address</label>
                </div>
                <div class="inputdiv">
                    <input class="form_row_input" type="text" value="" name="udt_sitelink" id="udt_sitelink" placeholder="<?php echo $site_link; ?>" disabled>
                    <i class="fa fa-pencil-square-o edit-icon" onclick="edit('udt_sitelink')" aria-hidden="true"></i>
                </div>
            </div>

            <div class="form_row">
                <input type="submit" onclick="return detectChanges()" name="profile_UPDATE" value="SAVE CHANGES" class="profile_udt_btn">
            </div>
        </form>

    </div>

</body>

<script language="JavaScript">
    function toggle(source) {
        checkboxes = document.getElementsByClassName('checkPosts');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function edit(id) {
        field = document.getElementById(id);
        if (field.disabled == true) {
            field.disabled = false;
        } else {
            field.disabled = true;
        }
    }

    function detectChanges() {
        fields = ['udt_name', 'udt_email', 'udt_sitename', 'udt_sitelink']
        for (i = 0; i < fields.length; i++) {
            input = document.getElementById(fields[i]);
            if (input.value != '') {
                document.getElementById('udt_name').disabled = false;
                document.getElementById('udt_email').disabled = false;
                document.getElementById('udt_sitename').disabled = false;
                document.getElementById('udt_sitelink').disabled = false;
                return true;
            }
        }
        if (document.getElementById("udt_profile_photo").value != '') {
            return true;
        }
        alert("No Changes Detected")
        return false;
    }

    $(document).ready(function() {

        $("#newpost_form").hide(100);
        $('#allposts').addClass("active");
        $("#newPost").click(function() {
            $("#main_body").hide(100);
            $('#allposts').removeClass("active");
            $('#published').removeClass("active");
            $('#draft').removeClass("active");
            $('#settings_Section').hide();
            $('#settings').removeClass("active");
            $("#newpost_form").show();
        });
        $("#newPostRes").click(function() {
            $("#main_body").hide(100);
            $('#allposts').removeClass("active");
            $('#published').removeClass("active");
            $('#settings_Section').hide();
            $('#settings').removeClass("active");
            $('#draft').removeClass("active");
            $("#newpost_form").show();
            document.getElementById("mySidenav").style.width = "0";
        });

        $(".user_posts").mouseenter(function() {
            $(this).addClass('mouseOver')
        });
        $(".user_posts").mouseleave(function() {
            $(this).removeClass('mouseOver');
        });

        $("#edit").click(function() {
            $("#edit_form").show();
            $("#main_body").hide();
        });
    });

    function handleMousePos(event) {
        var mouseClickWidth = event.clientX;
        if (mouseClickWidth >= 270) {
            document.getElementById("mySidenav").style.width = "0";

        }
    }

    document.addEventListener("click", handleMousePos);


    function closeEditForm(args) {
        document.getElementById("edit_form").style.display = "none";
        if (args == 'reload') {
            reloadJS();
        }
    }

    function MainBody(ope) {
        document.getElementById("main_body").style.display = ope;
    }

    function closeNewPostForm(args) {
        document.getElementById("newpost_form").style.display = "none";
        MainBody('block');
    }

    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }


    function showDrafts() {
        document.getElementById("draft").classList.add("active");
        document.getElementById("published").classList.remove("active");
        closeEditForm();
        document.getElementById("settings").classList.remove("active");
        document.getElementById("allposts").classList.remove("active");
        document.getElementById("newpost_form").style.display = "none";
        MainBody('block');
        document.getElementById("settings_Section").style.display = "none";
        document.getElementById("draft_posts").style.display = "block";
        document.getElementById("all_posts").style.display = "none";
        document.getElementById("published_posts").style.display = "none";
        document.getElementById("mySidenav").style.width = "0";
    }

    function showPublished() {
        document.getElementById("published").classList.add("active");
        document.getElementById("settings").classList.remove("active");
        document.getElementById("draft").classList.remove("active");
        document.getElementById("allposts").classList.remove("active");
        closeEditForm();
        document.getElementById("newpost_form").style.display = "none";
        MainBody('block');
        document.getElementById("published_posts").style.display = "block";
        document.getElementById("all_posts").style.display = "none";
        document.getElementById("draft_posts").style.display = "none";
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("settings_Section").style.display = "none";
        activateMenu("published", 'published_posts')
    }

    function showAll() {
        document.getElementById("allposts").classList.add("active");
        document.getElementById("published").classList.remove("active");
        document.getElementById("settings").classList.remove("active");
        document.getElementById("draft").classList.remove("active");
        document.getElementById("newpost_form").style.display = "none";
        MainBody('block');
        closeEditForm();
        document.getElementById("settings_Section").style.display = "none";
        document.getElementById("all_posts").style.display = "block";
        document.getElementById("draft_posts").style.display = "none";
        document.getElementById("published_posts").style.display = "none";
        document.getElementById("mySidenav").style.width = "0";
    }

    function showSettings() {
        document.getElementById("settings").classList.add("active");
        document.getElementById("allposts").classList.remove("active");
        document.getElementById("published").classList.remove("active");
        document.getElementById("draft").classList.remove("active");
        document.getElementById("newpost_form").style.display = "none";
        MainBody('none');
        closeEditForm();
        document.getElementById("settings_Section").style.display = "block";
        document.getElementById("draft_posts").style.display = "none";
        document.getElementById("published_posts").style.display = "none";
        document.getElementById("mySidenav").style.width = "0";
    }
    

    function deleteConfimation() {
        return confirm("Are you sure you want to delete this article ?")
    }

    function checkSelected(operation) {
        var bool = true;

        var checkedCount = $('input[name="checked[]"]:checked').length;

        if (checkedCount == 0) {
            alert("No Post Selected !");
            return false;
        } else if (operation == 'delete') {
            if (checkedCount > 1) {
                if (confirm("This will delete all the posts selected permanently.")) {
                    return confirm("Are you sure ?")
                } else {
                    return false;
                }
            } else {
                return deleteConfimation()
            }

        } else if (operation == 'draft') {
            if (checkedCount > 1) {
                return confirm("Do you want to unpublish all the selected posts and save it your drafts folder.")
            } else {
                return confirm("Do you want to unpubliosh this post and save it your blogs.")
            }
        } else if (operation == 'publish') {
            if (checkedCount > 1) {
                return confirm("This will publish all selected posts to your profile.")
            } else {
                return confirm("This will publish this post to your blog.")
            }
        }
    }
</script>

</html>
<!-- echo '<img style="" src="data:image/jpeg;base64,' . base64_encode($row['image']) . '"/>'; -->
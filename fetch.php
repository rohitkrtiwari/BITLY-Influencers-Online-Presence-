<?php

$connect = mysqli_connect("localhost", "root", "", "users");
if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $q = htmlentities($q);
    $q = mysqli_real_escape_string($connect, $q);
    $sql = "select * from tblusercredentials where email='$q' or email like '%$q' or email like '$q%' or email like '%$q%'";
    $res = mysqli_query($connect, $sql);
    if (mysqli_num_rows($res) > 0) {
?>
        <ul>
    <?php
        while ($x = mysqli_fetch_assoc($res)) {
            echo print_r($x);
        }
    }
}
    ?>
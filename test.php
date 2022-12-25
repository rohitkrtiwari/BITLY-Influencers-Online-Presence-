<!DOCTYPE html>
<html>
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
   $(document).ready(function() {
     $('[data-slide="0"]').addClass('demo');
   });
</script>
<style>
.demo {
    font-size: 150%;
    color: red;
}
</style>
</head>
<body>
<?php
$password = md5("Rohit@123");
echo $password;
?>

</body>
</html>
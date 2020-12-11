<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    <p>
    <?php 
    define('VA_SEQURE', true);
    require_once "config.php";

    $sql = "SELECT id, filename, created_at FROM psy_files WHERE username = '". htmlspecialchars($_SESSION["username"]) ."'";
    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<a href=\"./". htmlspecialchars($_SESSION["username"]) . "/" . $row['filename'] ."\" class=\"btn btn-link\" target=\"blank\"> id: ". $row['id'] ." created at:". $row['created_at'] ." <a/><br>";
        }
    } else {
        echo "0 results";
    }

    mysqli_close($link);
    ?>
    <br />
        <a href="note-editor.php" class="btn btn-success">Create new note</a>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>
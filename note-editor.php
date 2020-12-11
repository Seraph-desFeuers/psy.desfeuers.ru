<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

define('VA_SEQURE', true);
require_once "config.php";

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    $new_note = "";
    $new_note_err = "";

    // Validate new note
    if(empty(trim($_POST["new_note"]))){
        $new_note_err = "Note must be written";     
    } else{
        $new_note = trim($_POST["new_note"]);
    }
        
    // Check input errors before updating the database
    if(empty($new_note_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO psy_files (username, filename) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_filename);
            
            // Set parameters
            $param_username = htmlspecialchars($_SESSION["username"]);
            $param_filename = date("ljS\ofFYh:i:sA").".txt";
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                file_put_contents(htmlspecialchars($_SESSION["username"])."/".$param_filename, $new_note);
                header("location: welcome.php");
            } else{
                echo "Something went wrong. Please try again later. ".mysqli_error($link);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NewNote</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        New note
    </div>
    <p>
        <form action="note-editor.php" method="post">
            <textarea name="new_note" id="new_note" style="width: 90%">New note</textarea>
            <hr />
            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </p>
</body>
</html>
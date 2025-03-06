<?php
    if (isset($_COOKIE['adminname'])) {
        header("location: ../index.php");
        exit;
    }    

    include "../connection/connect.php";

    if (isset($_POST['signup'])) {
        $name = htmlspecialchars($_POST["adminname"]);
        $pass = htmlspecialchars($_POST["password"]);
        
        if (empty($name) == false || empty($pass) == false) {
            $insert=mysqli_query($conn, "INSERT INTO admin VALUES('','$name','$pass')");
            if ($insert) {
                header("location:login.php");
                exit;
            }
            else{
                echo "Fill all inputs";
            }
        }
        else{
            echo "<p class='msg'>All inputs must be filled!</p>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XYZ-LTD</title>
    <link rel="stylesheet" href="./forms.css">
</head>
<body>
    <header>
        <h2>XYZ_Ltd</h2>
    </header>
    <form action="" method="post">
        <h2>Signup form</h2>
        <label for="adminname">Admin Name:</label>
        <input type="text" pattern="[A-Za-z ]+" title="Enter only letters" name="adminname" id="adminname">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <input type="submit" name="signup" value="Sign up">
        <div class="formLinks">
            <a href="login.php">Go to login</a>
        </div>
    </form>
</body>
</html>
<?php
    if (isset($_COOKIE['adminname'])) {
        header("location: ../index.php");
        exit;
    }

    
    session_start();
    include "../connection/connect.php";

    if (isset($_POST['signup'])) {
        $name = htmlspecialchars($_POST["adminname"]);
        $pass = htmlspecialchars($_POST["password"]);

        if (!empty($name) && !empty($pass)) {


            $stmt = $conn->prepare("SELECT * FROM admin WHERE adminName = ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();

                if ($pass === $user['Password']) {
                    $_SESSION['id'] = $user['adminId'];

                    setcookie('adminname', $name, time() + (30 * 24 * 60 * 60), "/");


                    header("Location: ../index.php");
                    exit;
                } else {
                    echo "<p class='msg'>Invalid password for <span>${name}</span>!</p>";
                }
            } else {
                echo "<p class='msg'>Admin not found. Check the name again!</p>";
            }

            $stmt->close();
        } else {
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
        <h2>Login form</h2>
        <label for="adminname">Admin Name:</label>
        <input type="text" pattern="[A-Za-z ]+" placeholder="Enter your name" title="enter only letters" name="adminname" id="adminname">
        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Enter your password" id="password">
        <input type="submit" name="signup" value="Log in">
        <!-- <div class="formLinks">
            Don't have an account? <a href="signup.php">Go to signup</a>
        </div> -->
    </form>
</body>
</html>
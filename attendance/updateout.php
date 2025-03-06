<?php
    session_start();
    include '../connection/connect.php';

    if (!isset($_SESSION['id'])) {
        header("location:../register/login.php");
        exit();
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    }

    if (isset($_POST['signup'])) {

        $outtime = mysqli_real_escape_string($conn, $_POST['outtime']);
        
        if ($outtime > "17:00:00"){
            echo "<p class='msg'>Error: time must be less than 5PM</p>";
        }else{
            $query = "UPDATE attendance SET CheckOutTime = '$outtime' WHERE EmployeeId = '$id'";
            
            $result = mysqli_query($conn, $query);
            
            if ($result) {
                header("location:index.php");
                exit;
            } else {
                echo "<p class='msg'>Error: Could add check out time. Please try again.</p>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Attendance</title>
    <link rel="stylesheet" href="../forms/forms.css">
</head>
<body>
    <header>
        <h2>XYZ_Ltd  Make Attendance page</h2>
    </header>
    <form action="" method="post">
        <h2>Add Checkout time form</h2>
        </select>
        <label for="time1">Check out time:</label>
            <input type="time" required name="outtime" id="time1">

        <input type="submit" name="signup" value="save">
        <div class="formLinks">
            <span onclick="history.back()">Go back</span>
        </div>
    </form>
</body>
</html>
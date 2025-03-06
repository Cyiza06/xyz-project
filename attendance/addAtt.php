<?php

    session_start();
    include '../connection/connect.php';

    if (!isset($_SESSION['id'])) {
        header("location:../register/login.php");
        exit();
    }



    if (isset($_POST['signup'])) {

        $name = mysqli_real_escape_string($conn, $_POST['employee']);
        $date = date("Y-m-d");
        $intime = mysqli_real_escape_string($conn, $_POST['intime']);
        $outtime = mysqli_real_escape_string($conn, $_POST['outtime']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $check = mysqli_query($conn,"SELECT * FROM attendance WHERE EmployeeId ='$name' AND Date = '$date'");

        if (mysqli_num_rows($check) > 0) {
            $restrict = mysqli_query($conn,"SELECT FirstName,LastName FROM employee INNER JOIN attendance ON attendance.EmployeeId = '$name'");
            $fresi = mysqli_fetch_assoc($restrict);
            $fname = $fresi['FirstName'];
            $lname = $fresi['LastName'];

            echo "<p class='msg'>Attendance record for <b style=\"color:blue;padding:0px 5px \">$lname $fname</b> already exists.</p>";
        }
        else{
            if ($intime < "07:00:00"){
                echo "<p class='msg'>Error: time must be greater than 7AM</p>";
            }
            elseif (!empty($name) && !empty($intime) && !empty($status)) {
                
                $query = "INSERT INTO attendance VALUES ('','$name', '$date', '$intime', NULL, '$status')";
                
                $result = mysqli_query($conn, $query);
                
                if ($result) {
                    header("location:index.php");
                    exit;
                } else {
                    echo "<p class='msg'>Error: Could not make attendance. Please try again.</p>";
                }
            } else {
                echo "<p class='msg'>All fields are required!</p>";
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
        <h2>Make Attendance form</h2>
        <label for="employee">Employee Name:</label>
        <select required name="employee" id="employee">
            <option selected disabled>Select employee</option>
            <?php
                $select = mysqli_query($conn,"SELECT * FROM employee");

                if (mysqli_num_rows($select)) {
                    while ($row = mysqli_fetch_assoc($select)) {      
            ?>
            <option value="<?=$row['EmployeeId']?>"><?=$row['FirstName']."  ".$row['LastName'] ?></option>
            <?php
                    }
                }
            ?>
        </select>
        <label for="time">Check in and out time:</label>
        <div class="names">
            <input type="time" name="intime" required id="time">
            <input type="time" name="outtime" id="time">
        </div>


        <label for="status">Status:</label>
        <select name="status" id="employee">
            <option selected disabled>Select status</option>
            <option value="Present">Present</option>
            <option value="Late">Late</option>
            <option value="Absent">Absent</option>
        </select>

        <input type="submit" name="signup" value="Make attendance">
        <div class="formLinks">
            <span onclick="history.back()">Go back</span>
        </div>
    </form>
</body>
</html>
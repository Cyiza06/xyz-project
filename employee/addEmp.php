<?php
    session_start();
    include '../connection/connect.php';

    if (!isset($_SESSION['id'])) {
        header("location:../register/login.php");
        exit();
    }

    if (isset($_POST['signup'])) {
        $firstname = mysqli_real_escape_string($conn, $_POST['Firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $phonenumber = mysqli_real_escape_string($conn, $_POST['phonenumber']);
        
        if (!empty($firstname) && !empty($lastname) && !empty($dob) && !empty($gender) && !empty($department) && !empty($phonenumber)) {
            
            $query = "INSERT INTO employee (firstname, lastname, dob, gender, department, phonenumber) 
                    VALUES ('$firstname', '$lastname', '$dob', '$gender', '$department', '$phonenumber')";
            

            $result = mysqli_query($conn, $query);
            
            if ($result) {
                header("location:index.php");
                exit;
            } else {
                echo "<p class='msg'>Error: Could not add employee. Please try again.</p>";
            }
        } else {
            echo "<p class='msg'>All fields are required!</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add employee</title>
    <link rel="stylesheet" href="../forms/forms.css">
</head>
<body>
    <header>
        <h2>XYZ_Ltd  Add Employee page</h2>
    </header>
    <form action="" method="post">
        <h2>Add  Employee form</h2>
        <label for="adminname">Employee Name:</label>
        <div class="names">
            <input type="text" pattern="[A-Za-z ]+" title="enter only letters" name="Firstname" placeholder="firstname" id="">
            <input type="text" pattern="[A-Za-z ]+" title="enter only letters" name="lastname" required placeholder="lastname" id="">
        </div>
        <label for="dob">DOB:</label>
        <input type="date" name="dob" id="dob">

        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
            <option selected disabled>Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label for="department">Department:</label>
        <input type="text" name="department" placeholder="Department" required id="department">

        <label for="phonenumber">Phone Number:</label>
        <input type="text" name="phonenumber" placeholder="Phone number" required id="phonenumber">
        <input type="submit" name="signup" value="Save">
        <div class="formLinks">
            <span onclick="history.back()">Go back</span>
        </div>
    </form>
</body>
</html>
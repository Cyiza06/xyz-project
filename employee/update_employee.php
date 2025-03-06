<?php
    session_start();
    include '../connection/connect.php';

    if (!isset($_SESSION['id'])) {
        header("location:../register/login.php");
        exit();
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $select = mysqli_query($conn,"SELECT * FROM employee WHERE EmployeeId = '$id'");
        
        $employee = mysqli_fetch_assoc($select);
    }

    if (isset($_POST['update'])) {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $firstname = mysqli_real_escape_string($conn, $_POST['Firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $phonenumber = mysqli_real_escape_string($conn, $_POST['phonenumber']);
        

        if (!empty($firstname) && !empty($lastname) && !empty($dob) && !empty($gender) && !empty($department) && !empty($phonenumber)) {

            $query = "UPDATE employee 
                    SET firstname = '$firstname', lastname = '$lastname', dob = '$dob', gender = '$gender', department = '$department', phonenumber = '$phonenumber' 
                    WHERE EmployeeId = '$id'";


            $result = mysqli_query($conn, $query);

            if ($result) {
                header("location:index.php");
                exit;
            } else {
                echo "<p class='msg'>Error: Could not update employee. Please try again.</p>";
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
    <title>Update employee</title>
    <link rel="stylesheet" href="../forms/forms.css">
</head>
<body>
<header>
        <h2>XYZ_Ltd  Update Employee page</h2>
    </header>
    <form action="" method="post">
        <h2>Update Employee Form</h2>
        
        <input type="hidden" name="id" value="<?php echo $employee['EmployeeId']; ?>">

        <label for="adminname">Employee Name:</label>
        <div class="names">
            <input type="text" name="Firstname" value="<?php echo $employee['FirstName']; ?>" placeholder="firstname" id="">
            <input type="text" name="lastname" value="<?php echo $employee['LastName']; ?>" placeholder="lastname" id="">
        </div>

        <label for="dob">DOB:</label>
        <input type="date" name="dob" value="<?php echo $employee['DOB']; ?>" id="dob">

        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
            <option value="Male" <?php echo ($employee['Gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo ($employee['Gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
        </select>

        <label for="department">Department:</label>
        <input type="text" name="department" value="<?php echo $employee['Department']; ?>" placeholder="Department" id="department">

        <label for="phonenumber">Phone Number:</label>
        <input type="text" name="phonenumber" value="<?php echo $employee['PhoneNumber']; ?>" placeholder="Phone number" id="phonenumber">
        
        <input type="submit" name="update" value="Update">
        <span onclick="history.back()">Go Back</span>
    </form>

</body>
</html>

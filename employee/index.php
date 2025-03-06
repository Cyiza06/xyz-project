<?php
    session_start();
    include '../connection/connect.php';

    if (!isset($_SESSION['id'])) {
        header("location:../register/login.php");
        exit();
    }


    if (isset($_GET['delete_id'])) {
        $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
        

        $query = "DELETE FROM employee WHERE EmployeeId = '$id'";

        
        $result = mysqli_query($conn, $query);

        if ($result) {
           header("location:index.php");
           exit;
        } 
        else {
            echo "<p class='msg'>Error: Could not delete employee. Please try again.</p>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="stylesheet" href="../styles/active.css">
    <script src="../js/2.1 jquery.js.js"></script>
    <script>
        $(document).ready(function(){
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        });
    </script>
</head>
<body>
    <nav>
        <h1>XYZ-Ltd</h1>
        <div class="navh">
            <ul>
                <li><a href="../index.php" >Dashboard</a></li>
                <li class="active"><a href="">Employee</a></li>
                <li><a href="../attendance/">Attendance</a></li>
                <li><a href="../reports/">Reports</a></li>
            </ul>
        </div>
        <div class="navb">
        <a href="../logout.php" onclick="return confirm('Are you sure you want to log out')">Logout</a>
        </div>
    </nav>
    <div class="main">
        <h2>
            <?php
                if (isset($_COOKIE['adminname'])) {
                    echo "Welcome back, " . htmlspecialchars($_COOKIE['adminname']) . "!";
                }
            ?>
        </h2>
        <main>
            <div class="main-buttons">
                <form action="" method="get">
                    <div class="form">
                        <input type="text" placeholder="Search anything" name="search" id="search">
                    </div>
                </form>
                <a href="./addEmp.php" class="button-primary">
                    Add Employee
                </a>
            </div>
            <div class="main-content">
                <?php
                    $result = mysqli_query($conn, "SELECT * FROM employee");

                    if (mysqli_num_rows($result) > 0) {
                        echo "<table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th>DOB</th>
                                        <th>Gender</th>
                                        <th>Department</th>
                                        <th>Phone Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id='myTable'>";
                                $count = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>" . $count . "</td>
                                            <td>" . $row['FirstName'] . "</td>
                                            <td>" . $row['LastName'] . "</td>
                                            <td>" . $row['DOB'] . "</td>
                                            <td>" . $row['Gender'] . "</td>
                                            <td>" . $row['Department'] . "</td>
                                            <td>" . $row['PhoneNumber'] . "</td>
                                            <td>
                                                <a href='update_employee.php?id=" . $row['EmployeeId'] . "'>Update</a> |
                                                <a href='?delete_id=" . $row['EmployeeId'] . "' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>Delete</a>
                                            </td>
                                        </tr>
                                    ";
                                    $count++;
                                }

                                echo "</tbody>
                            </table>";
                    } else {
                        echo "<p>No employee records found.</p>";
                    }
                ?>
            </div>
        </main>
        <div class="footer">
            <p>&copy; <?php echo date('Y')?> XYZ-Ltd</p> 
        </div>
    </div>
</body>
</html>
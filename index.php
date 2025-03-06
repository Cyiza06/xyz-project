<?php
    session_start();
    include './connection/connect.php';

    if (!isset($_SESSION['id'])) {
        header("location:./register/login.php");
        exit();
    }
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XYZ-LTD</title>
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/active.css">
</head>
<body>
    <nav>
        <h1>XYZ-Ltd</h1>
        <div class="navh">
            <ul>
                <li class="active"><a href="" >Dashboard</a></li>
                <li><a href="./employee/">Employee</a></li>
                <li><a href="./attendance/">Attendance</a></li>
                <li><a href="./reports/">Reports</a></li>
            </ul>
        </div>
        <div class="navb">
            <a href="logout.php" onclick="return confirm('Are you sure you want to log out')">Logout</a>
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
            <h2>Action buttons</h2>
            <div class="main-buttons">
                <a href="./employee/addEmp.php" class="button-primary">
                    Add Employee
                </a>
                <a href="./attendance/addAtt.php" class="button-primary">
                    Make Attendance
                </a>
                <a href="./reports/index.php" class="button-primary">
                    Check reports
                </a>
            </div>
            <div class="main-content">
                <div class="cards">
                    <div class="card">
                        <p>Total Employees</p>
                        <h1>
                            <?php
                                $sql = "SELECT * FROM employee";
                                $result = $conn->query($sql);
                                echo $result->num_rows;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <p>Absent Employees Today</p>
                        <h1>
                            <?php
                                $sql = "SELECT * FROM attendance WHERE Status = 'Absent' AND Date='" . date('Y-m-d') . "'";
                                $result = $conn->query($sql);
                                echo $result->num_rows;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <p>Total Present Employees</p>
                        <h1>
                            <?php
                                $sql = "SELECT * FROM attendance WHERE Status = 'Present' AND Date='" . date('Y-m-d') . "'";
                                $result = $conn->query($sql);
                                echo $result->num_rows;
                            ?>
                        </h1>
                    </div>
                    <div class="card">
                        <p>Late employees Today</p>
                        <h1>
                            <?php
                                $sql = "SELECT * FROM attendance WHERE Status = 'Late' AND Date ='" . date('Y-m-d') . "'";
                                $result = $conn->query($sql);
                                echo $result->num_rows;
                            ?>
                        </h1>
                    </div>    
                </div>

                <div class="short-summary">
                    <h2>Short Summary for week</h2>
                    <ul>
                        <li>
                            <?php
                               $late = mysqli_query($conn,
                               "SELECT e.EmployeeId, e.FirstName,e.LastName,COUNT(a.status) AS late_count
                                FROM employee e
                                JOIN attendance a ON e.EmployeeId = a.EmployeeId
                                WHERE a.Status = 'Late'
                                GROUP BY e.EmployeeId, e.FirstName,e.LastName
                                ORDER BY late_count DESC
                                LIMIT 1;
                                "
                                );
                                if (mysqli_num_rows($late)) {
                                    $row = mysqli_fetch_array($late);
                                    echo "Most late employee is ".'<b>' . $row['FirstName'] . " " . $row['LastName']. '</b>' . " with "."<b>" . $row['late_count']."</b>" . " late attendances";
                                } else {
                                    Echo "No employee with high Late attendance";
                                }
                                
                            ?>
                        </li>
                        <li>
                            <?php
                               $late = mysqli_query($conn,
                               "SELECT e.EmployeeId, e.FirstName,e.LastName,COUNT(a.status) AS late_count
                                FROM employee e
                                JOIN attendance a ON e.EmployeeId = a.EmployeeId
                                WHERE a.Status = 'Present'
                                GROUP BY e.EmployeeId, e.FirstName,e.LastName
                                ORDER BY late_count DESC
                                LIMIT 1;
                                "
                                );

                                if (mysqli_num_rows($late)) {
                                    $row = mysqli_fetch_array($late);
                                    echo "Most present employee is ".'<b>' . $row['FirstName'] . " " . $row['LastName']. '</b>' . " with "."<b>" . $row['late_count']."</b>" . " Present attendances";
                                } else {
                                    Echo "No employee with high Present attendance rate";
                                }
                            ?>
                        </li>
                        <li>
                            <?php
                               $late = mysqli_query($conn,
                               "SELECT e.EmployeeId, e.FirstName,e.LastName,COUNT(a.status) AS late_count
                                FROM employee e
                                JOIN attendance a ON e.EmployeeId = a.EmployeeId
                                WHERE a.Status = 'Absent'
                                GROUP BY e.EmployeeId, e.FirstName,e.LastName
                                ORDER BY late_count DESC
                                LIMIT 1;
                                "
                                );

                                if (mysqli_num_rows($late)) {
                                    $row = mysqli_fetch_array($late);
                                    echo "Most present employee is ".'<b>' . $row['FirstName'] . " " . $row['LastName']. '</b>' . " with "."<b>" . $row['late_count']."</b>" . " Present attendances";
                                } else {
                                    Echo "No employee with high Absent attendance late";
                                }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
        <div class="footer">
            <p>&copy; <?php echo date('Y')?> XYZ-Ltd</p> 
        </div>
    </div>
</body>
</html>
<?php
    session_start();
    include '../connection/connect.php';

    if (!isset($_SESSION['id'])) {
        header("location:../register/login.php");
        exit();
    }
    $isSearched = false;
    if (isset($_GET['submit'])) {
        # code...
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';
        $employee_name = $_GET['employee_name'] ?? '';
        $status = $_GET['status'] ?? '';
    
        if (!$start_date || !$end_date) {
            die("Please select both start and end dates.");
        }
    
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
    
        $sql = "SELECT e.EmployeeId, e.FirstName, e.LastName, a.Date, a.Status 
            FROM employee e
            JOIN attendance a ON e.EmployeeId = a.EmployeeId
            WHERE a.Date BETWEEN '$start_date' AND '$end_date'";
    
        if (!empty($employee_name)) {
            $sql .= " AND e.FirstName LIKE '%$employee_name%' AND e.LastName LIKE '%$employee_name%'";
        }
        if (!empty($status)) {
            $sql .= " AND a.Status = '$status'";
        }
    
        $sql .= " ORDER BY a.Date ASC LIMIT $limit OFFSET $offset";
    
        $result = $conn->query($sql);

        if ($result && mysqli_num_rows($result)>0) {
            $isSearched = true;
        }else{
            $message = "No results found";
        }

        $count_sql = "SELECT COUNT(*) AS total FROM employee e 
                  JOIN attendance a ON e.EmployeeId = a.EmployeeId
                  WHERE a.Date BETWEEN '$start_date' AND '$end_date'";
    
        $count_result = $conn->query($count_sql);
        $total_records = $count_result->fetch_assoc()['total'];
        $total_pages = ceil($total_records / $limit);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Reports</title>
    <link rel="stylesheet" href="./index.css">
    <link rel="stylesheet" href="../styles/active.css">
    <script>
    function printResults() {
        var printContent = document.getElementById("printArea").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = "<html><head><title>Print Report</title></head><body>" + printContent + "</body></html>";
        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }
</script>

</head>
<body>
    <nav>
        <h1>XYZ-Ltd</h1>
        <div class="navh">
            <ul>
                <li><a href="../index.php" >Dashboard</a></li>
                <li><a href="../employee/">Employee</a></li>
                <li><a href="../attendance/">Attendance</a></li>
                <li class="active"><a href="">Reports</a></li>
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
            <div class="main-content">
                <h2>Generate report by Date ,Name and Status</h2>
                <form action="" method="GET">
                    <div class="inputs">
                        <fieldset>
                            <legend>Date Range</legend>
                            <input type="date" id="start_date" name="start_date" required>
                            <input type="date" id="end_date" name="end_date" required>
                        </fieldset>
                        <select id="employee" name="employee_name">
                            <option disabled selected>Select Employee</option>
                            <?php
                                $selected = mysqli_query($conn, "SELECT * FROM employee ORDER BY LastName ASC");
                                while ($row = $selected->fetch_assoc()) {
                                    echo "<option value='{$row['FirstName']} {$row['LastName']}'>{$row['FirstName']} {$row['LastName']}</option>";
                                }
                            ?>
                        </select>
                        <select id="status" name="status">
                            <option disabled selected>Choose Status</option>
                            <option value="">All</option>
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>
                    <button type="submit" name="submit">Generate report</button>
                </form>
                <?php
                    if ($isSearched) {
                        echo "<h3 style=\" text-align:center\">Attendance Report</h3><p style=\" text-align:center\" >Showing results from $start_date to $end_date</p>";
                    }else{
                        if (isset($message)) {
                            echo "<p style=\" text-align:center\">$message</p>";
                        }else{
                            echo "<h3 style=\" text-align:center\">Search something to get report</h3>";
                        }
                    }
                ?>
                <?php if($isSearched):?>
                    <div id="printArea">
                        <table border="1">
                            <tr>
                                <th>&numero;</th>
                                <th>FirstName</th>
                                <th>LastName</th>
                                <th>Attendance Date</th>
                                <th>Status</th>
                            </tr>

                            <?php
                            $count = 1;
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$count}</td>
                                            <td>{$row['FirstName']}</td>
                                            <td>{$row['LastName']}</td>
                                            <td>{$row['Date']}</td>
                                            <td>{$row['Status']}</td>
                                        </tr>";
                                    $count++;
                                }
                            } else {
                                echo "<tr><td colspan='5'>No records found</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                    <br>
                    <?php if ($total_pages > 1): ?>
                        <div>
                            <?php if ($page > 1): ?>
                                <a href="?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&employee_name=<?= $employee_name ?>&status=<?= $status ?>&page=<?= $page - 1 ?>">Previous</a>
                            <?php endif; ?>

                            Page <?= $page ?> of <?= $total_pages ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?start_date=<?= $start_date ?>&end_date=<?= $end_date ?>&employee_name=<?= $employee_name ?>&status=<?= $status ?>&page=<?= $page + 1 ?>">Next</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <button class="button" onclick="printResults()">Print</button>
                <?php endif;?>
            </div>
        </main>
        <div class="footer">
            <p>&copy; <?php echo date('Y')?> XYZ-Ltd</p> 
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
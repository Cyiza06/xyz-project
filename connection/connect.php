<?php
    $conn = mysqli_connect("localhost","root","","xyz_ltd");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
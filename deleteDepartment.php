<?php
include "connection.php";

if (isset($_GET["departmentID"])) {
    $departmentID = $_GET["departmentID"];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the department name before deleting
    $sql = "SELECT name FROM dep WHERE departmentID = $departmentID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $departmentName = $row["name"];

        // Delete the department
        $sql = "DELETE FROM dep WHERE departmentID = $departmentID";

        if ($conn->query($sql) === TRUE) {
            echo "success:" . $departmentName;
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }

    $conn->close();
} else {
    echo "error";
}
?>

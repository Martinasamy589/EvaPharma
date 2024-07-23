<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeName = $_POST['employeeName'];
    $columnName = $_POST['columnName'];

    // Escape column name to prevent SQL injection
    $columnName = mysqli_real_escape_string($conn, $columnName);

    // Example SQL query to drop the column
    $sql_delete = "ALTER TABLE employee DROP COLUMN `$columnName`";

    if ($conn->query($sql_delete) === TRUE) {
        echo "success";
    } else {
        echo "Error deleting question: " . $conn->error;
    }
}

$conn->close();
?>

<?php
include "connection.php";

if (isset($_GET["departmentID"])) {
    $departmentID = $_GET["departmentID"];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM dep WHERE departmentID = $departmentID";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "DepartmentID not provided";
}
?>

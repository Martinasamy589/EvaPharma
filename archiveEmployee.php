<?php
include "connection.php";

if (isset($_GET['employeeID'])) {
    $employeeID = intval($_GET['employeeID']);

    $sql = "SELECT * FROM employee WHERE id = $employeeID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $department = $row['department'];

        $sql_archive = "INSERT INTO archived_employee (name, department) VALUES ('$name', '$department')";
        if ($conn->query($sql_archive) === TRUE) {
            $sql_delete = "DELETE FROM employee WHERE id = $employeeID";
            $conn->query($sql_delete);
            echo "success";
        } else {
            echo "Failed to archive employee.";
        }
    } else {
        echo "Employee not found.";
    }
} else {
    echo "No employee ID provided.";
}

$conn->close();
?>

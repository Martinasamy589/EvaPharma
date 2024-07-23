<?php
include "connection.php";

function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departmentID = $_POST['departmentID'];
    $employeeName = sanitize_input($conn, $_POST['employeeName']);

    $sql_insert = "INSERT INTO employee (name, department) VALUES ('$employeeName', (SELECT name FROM dep WHERE departmentID = $departmentID))";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Employee added successfully.";
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>

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

    if (empty($employeeName)) {
        echo 'error:Employee name cannot be empty!';
        exit();
    }

    $sql_insert = "INSERT INTO employee (name, department) VALUES ('$employeeName', (SELECT name FROM dep WHERE departmentID = $departmentID))";

    if ($conn->query($sql_insert) === TRUE) {
        echo "success:$employeeName";
    } else {
        echo "error:Error adding employee: " . $conn->error;
    }
} else {
    echo "error:Invalid request.";
}

$conn->close();
?>

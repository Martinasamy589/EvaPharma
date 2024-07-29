<?php
include "connection.php";

function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

if (isset($_GET['employeeID'])) {
    $employeeID = sanitize_input($conn, $_GET['employeeID']);

    $sql_delete = "DELETE FROM employee WHERE id = $employeeID";

    if ($conn->query($sql_delete) === TRUE) {
        echo "success:Employee deleted successfully!";
    } else {
        echo "error:Error deleting employee: " . $conn->error;
    }
} else {
    echo "error:Employee ID not provided.";
}

$conn->close(); 
?>

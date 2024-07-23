<?php
// Include database connection
include "connection.php";

// Function to sanitize input
function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Check if POST data exists
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $departmentID = $_POST['departmentID'];
    $employeeName = sanitize_input($conn, $_POST['employeeName']);

    // Insert employee into database
    $sql_insert = "INSERT INTO employee (name, department) VALUES ('$employeeName', (SELECT name FROM dep WHERE departmentID = $departmentID))";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Employee added successfully.";
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close database connection
$conn->close();
?>

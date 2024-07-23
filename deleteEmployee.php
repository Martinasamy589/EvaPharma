<?php
// Include database connection
include "connection.php";

// Function to sanitize input
function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Check if employeeID is set in the URL
if (isset($_GET['employeeID'])) {
    $employeeID = sanitize_input($conn, $_GET['employeeID']);

    // Delete employee from database
    $sql_delete = "DELETE FROM employee WHERE id = $employeeID";

    if ($conn->query($sql_delete) === TRUE) {
        // Redirect back to the employees list page with success message
        $conn->close();
        header("Location: index.php?success=2");
        exit();
    } else {
        echo "Error deleting employee: " . $conn->error;
    }
} else {
    echo "Employee ID not provided.";
}

// Close database connection
$conn->close(); 
?>

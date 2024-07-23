<?php
include "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departmentName = $_POST['addDep'];

    // Sanitize input to prevent SQL injection
    $departmentName = mysqli_real_escape_string($conn, $departmentName);

    // Insert department into database
    $sql = "INSERT INTO dep (name) VALUES ('$departmentName')";

    if ($conn->query($sql) === TRUE) {
        // Department added successfully, redirect to index.php with success message
        header("Location: index.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

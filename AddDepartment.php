<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departmentName = trim($_POST['addDep']);

    // Validate that department name is not empty
    if (empty($departmentName)) {
        echo 'error: Department name cannot be empty.';
        $conn->close();
        exit();
    }

    $check_query = "SELECT * FROM dep WHERE name = '$departmentName'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        echo 'duplicate';
    } else {
        $sql = "INSERT INTO dep (name) VALUES ('$departmentName')";

        if ($conn->query($sql) === TRUE) {
            echo 'success:' . $departmentName; // Include the department name in the response
        } else {
            echo "error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

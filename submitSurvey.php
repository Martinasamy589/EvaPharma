<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeName = $_POST['employeeName'];

    $sql = "UPDATE employee SET ";
    $updates = [];
    foreach ($_POST as $key => $value) {
        if ($key != 'employeeName') {
            if (is_array($value)) {
                $value = implode(',', $value); // Store multiple selections as a comma-separated string
            }
            $updates[] = "$key='$value'";
        }
    }
    $sql .= implode(", ", $updates);
    $sql .= " WHERE name='$employeeName'";

    if ($conn->query($sql) === TRUE) {
        echo "success:Survey updated successfully.";
    } else {
        echo "Error updating survey: " . $conn->error;
    }
}

$conn->close();
?>

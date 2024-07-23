<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeName = $_POST['employeeName'];

    foreach ($_POST as $key => $values) {
        if ($key != 'employeeName') {
            $value = implode(",", $values);

            $value = mysqli_real_escape_string($conn, $value);

            $sql = "UPDATE employee SET $key = '$value' WHERE name = '$employeeName'";
            if ($conn->query($sql) !== TRUE) {
                echo "Error updating record: " . $conn->error;
            }
        }
    }

    echo "Survey submitted successfully for employee: " . $employeeName;
}

$conn->close();
?>

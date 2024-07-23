<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departmentName = $_POST['addDep'];

    $check_query = "SELECT * FROM dep WHERE name = '$departmentName'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        echo 'duplicate';
    } else {
        $sql = "INSERT INTO dep (name) VALUES ('$departmentName')";

        if ($conn->query($sql) === TRUE) {
            echo 'success';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

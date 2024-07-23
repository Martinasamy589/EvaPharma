<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $checkName = $_POST['checkName'];
    $checkType = $_POST['checkType'];
    $checkOptions = $_POST['checkOptions'];


    $checkName = mysqli_real_escape_string($conn, $checkName);
    $checkType = mysqli_real_escape_string($conn, $checkType);

    $optionsArray = explode(',', $checkOptions);
    $enumOptions = array_map('trim', $optionsArray); 

    $enumOptionsString = "'" . implode("','", $enumOptions) . "'";

    $sql = "ALTER TABLE employee ADD $checkName ENUM($enumOptionsString) ";

    if ($conn->query($sql) === TRUE) {
        echo "New check column '$checkName' added successfully!";
    } else {
        echo "Error adding check column: " . $conn->error;
    }
}

$conn->close();
?>

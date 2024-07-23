<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeName = $_POST['employeeName'];
    $columnName = $_POST['columnName'];

    $employeeName = mysqli_real_escape_string($conn, $employeeName);
    $columnName = mysqli_real_escape_string($conn, $columnName);

    $sql_column_info = "SHOW COLUMNS FROM employee WHERE Field = '$columnName'";
    $result_column_info = $conn->query($sql_column_info);

    if ($result_column_info->num_rows > 0) {
        $row_column_info = $result_column_info->fetch_assoc();
        $columnType = $row_column_info['Type'];

        $newColumnName = $columnName . '_deleted';
        $sql_add_column = "ALTER TABLE employee ADD COLUMN `$newColumnName` $columnType";
        if ($conn->query($sql_add_column) === TRUE) {
            $sql_update_data = "UPDATE employee SET `$newColumnName` = NULL WHERE name = '$employeeName'";
            if ($conn->query($sql_update_data) === TRUE) {
                $sql_drop_column = "ALTER TABLE employee DROP COLUMN `$columnName`";
                if ($conn->query($sql_drop_column) === TRUE) {
                    echo "success";
                } else {
                    echo "Error dropping column: " . $conn->error;
                }
            } else {
                echo "Error updating data: " . $conn->error;
            }
        } else {
            echo "Error adding new column: " . $conn->error;
        }
    } else {
        echo "Column '$columnName' not found in employee table.";
    }
}

$conn->close();
?>

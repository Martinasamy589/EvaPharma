<?php
include "connection.php"; // Ensure this file contains your database connection code.

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeName = isset($_POST['employeeName']) ? $_POST['employeeName'] : '';

    if (empty($employeeName)) {
        echo "Error: Employee name is missing.";
        exit;
    }

    // Log POST data for debugging
    error_log("POST Data: " . print_r($_POST, true));

    // Retrieve the existing columns from the employee table
    $columns_result = $conn->query("SHOW COLUMNS FROM employee");
    if (!$columns_result) {
        error_log("Error retrieving columns: " . $conn->error);
        echo "Error retrieving columns.";
        exit;
    }

    $existing_columns = [];
    while ($row = $columns_result->fetch_assoc()) {
        $existing_columns[] = $row['Field'];
    }

    // Log existing columns for debugging
    error_log("Existing Columns: " . print_r($existing_columns, true));

    // Initialize an array to hold column-value pairs for the UPDATE query
    $updates = [];
    foreach ($_POST as $key => $value) {
        if ($key !== 'employeeName') {
            if (in_array($key, $existing_columns)) {
                if (is_array($value)) {
                    $value = implode(',', $value); // Convert array values to comma-separated string
                }
                $value = $conn->real_escape_string($value);
                $key = $conn->real_escape_string($key);
                $updates[] = "`$key`='$value'";

                // Log each update for debugging
                error_log("Update Pair: `$key`='$value'");
            } else {
                error_log("Column '$key' does not exist in the employee table.");
            }
        }
    }

    if (count($updates) > 0) {
        $sql = "UPDATE employee SET " . implode(", ", $updates) . " WHERE name='$employeeName'";
        
        // Log the SQL query for debugging
        error_log("SQL Query: $sql");

        if ($conn->query($sql) === TRUE) {
            echo "Success: Survey updated successfully.";
        } else {
            // Log the error details
            error_log("Error updating survey: " . $conn->error);
            echo "Error updating survey: " . $conn->error;
        }
    } else {
        echo "No data to update.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>

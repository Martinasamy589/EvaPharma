<?php
include "connection.php";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if employeeName is set and not empty
    if (isset($_POST['employeeName']) && !empty($_POST['employeeName'])) {
        $employeeName = $_POST['employeeName'];

        // Loop through POST data to construct SQL UPDATE statements
        foreach ($_POST as $key => $values) {
            if ($key != 'employeeName') {
                // Prepare values for SQL update (implode for arrays)
                if (is_array($values)) {
                    $value = implode(",", $values);
                } else {
                    $value = $values;
                }
                $value = mysqli_real_escape_string($conn, $value);

                // Construct SQL update query
                $sql = "UPDATE employee SET $key = '$value' WHERE name = '$employeeName'";

                // Execute SQL query
                if ($conn->query($sql) !== TRUE) {
                    // Handle errors if query fails
                    die("Error updating record: " . $conn->error);
                }
            }
        }

        echo "Survey submitted successfully for employee: " . htmlspecialchars($employeeName);
    } else {
        // Handle case where employeeName is not received
        die("Employee name not provided.");
    }
} else {
    // Handle case where no POST data is received
    die("No POST data received.");
}

$conn->close();
?>

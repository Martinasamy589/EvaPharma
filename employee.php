<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
        /* تكبير حجم الرابط */
        .navbar-nav a {
            font-size: 18px;
        }

        /* تخصيص لون شريط التنقل */
        .navbar {
            background-color: #0a58ca; /* لون كحلي أغمق */
            color: white; /* لون النص */
        }

        /* تخصيص استايل للأقسام */
        .container {
            margin-top: 50px;
        }

        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 20px;
        }

        .list-group-item a {
            font-size: 20px;
        }

        .list-group-item a:hover {
            text-decoration: none;
        }

        .btn-danger {
            font-size: 14px;
        }

        .btn-danger:hover {
            text-decoration: none;
        }

        /* تكبير حجم الزرار في النموذج */
        .btn-primary {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- شريط التنقل Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manage Departments</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reqStatus.php">Employees</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php
        // PHP code for fetching and displaying employees
        // Include database connection
        include "connection.php";

        // Function to sanitize input
        function sanitize_input($conn, $data) {
            $data = trim($data);
            $data = mysqli_real_escape_string($conn, $data);
            return $data;
        }

        // Check if departmentID is set
        if (isset($_GET['departmentID'])) {
            $departmentID = $_GET['departmentID'];

            // Retrieve department name
            $sql_dep = "SELECT name FROM dep WHERE departmentID = $departmentID";
            $result_dep = $conn->query($sql_dep);

            if ($result_dep->num_rows > 0) {
                $row_dep = $result_dep->fetch_assoc();
                $department_name = $row_dep['name'];
                echo "<h4>Department: " . $department_name . "</h4>";

                // Display employees for the selected department
                $sql_emp = "SELECT id, name FROM employee WHERE department = '$department_name'";
                $result_emp = $conn->query($sql_emp);

                if ($result_emp->num_rows > 0) {
                    echo "<ul class='list-group'>";
                    while ($row_emp = $result_emp->fetch_assoc()) {
                        // Display each employee name as a hyperlink with delete link
                        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                        echo '<a href="check.php?employeeName=' . urlencode($row_emp['name']) . '">' . $row_emp['name'] . '</a>';
                        echo '<span>';
                        echo '<a href="#" class="btn btn-sm btn-danger" onclick="deleteEmployee(' . $row_emp['id'] . ');">Delete</a>';
                        echo '</span>';
                        echo '</li>';
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No employees found for this department.</p>";
                }
            } else {
                echo "<p>Department not found for ID: $departmentID</p>";
            }
        } else {
            echo "<p>No department selected.</p>";
        }

        // Close database connection
        $conn->close();
        ?>

        <!-- Form to add a new employee -->
        <h4 class="mt-5">Add Employee:</h4>
        <form id="addEmployeeForm" action="addEmployee.php" method="post">
            <input type="hidden" name="departmentID" value="<?php echo $departmentID; ?>">
            <div class="mb-3">
                <label for="employeeName" class="form-label">Employee Name:</label>
                <input type="text" id="employeeName" name="employeeName" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Employee</button>
        </form>
    </div>

    <!-- JavaScript for AJAX requests and other functions -->
    <script>
        // JavaScript function to delete employee
        function deleteEmployee(employeeID) {
            if (confirm('Are you sure you want to delete this employee?')) {
                // Send AJAX request to deleteEmployee.php
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'deleteEmployee.php?employeeID=' + employeeID, true);

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert('Employee deleted successfully.');
                        location.reload(); // Reload the page after successful deletion
                    } else {
                        alert('Failed to delete employee.');
                    }
                };

                xhr.send();
            }
        }

        // Submit form using AJAX for add employee
        document.getElementById('addEmployeeForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            var form = this;
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Employee added successfully.');
                    form.reset(); // Reset form after successful addition
                    location.reload(); // Reload the page after successful addition
                } else {
                    alert('Failed to add employee.');
                }
            };

            xhr.send(formData);
        });
    </script>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>

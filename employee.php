<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
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
        .btn-danger, .btn-secondary {
            font-size: 14px;
        }
        .btn-primary {
            font-size: 18px;
        }
        .navbar-nav a {
            font-size: 18px;
        }
        .navbar {
            background-color: black; 
            color: white; 
        }
        .navbar-brand img {
            max-height: 50px; 
            margin-right: 10px; 
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images.png" alt="Logo"> 
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="employee.php">Employees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="archivedEmployees.php">Archived Employees</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <?php
    include "connection.php";

    function sanitize_input($conn, $data) {
        $data = trim($data);
        $data = mysqli_real_escape_string($conn, $data);
        return $data;
    }

    if (isset($_GET['departmentID'])) {
        $departmentID = $_GET['departmentID'];

        $sql_dep = "SELECT name FROM dep WHERE departmentID = $departmentID";
        $result_dep = $conn->query($sql_dep);

        if ($result_dep->num_rows > 0) {
            $row_dep = $result_dep->fetch_assoc();
            $department_name = $row_dep['name'];
            echo "<h4>Department: " . $department_name . "</h4>";

            $sql_emp = "SELECT id, name FROM employee WHERE department = '$department_name'";
            $result_emp = $conn->query($sql_emp);

            if ($result_emp->num_rows > 0) {
                echo "<ul class='list-group'>";
                while ($row_emp = $result_emp->fetch_assoc()) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                    echo '<a href="check.php?employeeName=' . urlencode($row_emp['name']) . '">' . $row_emp['name'] . '</a>';
                    echo '<span>';
                    echo '<a href="#" class="btn btn-sm btn-danger" onclick="deleteEmployee(' . $row_emp['id'] . ');">Delete</a>';
                    echo '<a href="#" class="btn btn-sm btn-secondary ms-2" onclick="archiveEmployee(' . $row_emp['id'] . ');">Archive</a>';
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
        <button type="submit" class="btn btn-primary" style="background-color:#e5c405;">Add Employee</button>
    </form>
</div>

<!-- JavaScript for AJAX requests and other functions -->
<script>
    function deleteEmployee(employeeID) {
        if (confirm('Are you sure you want to delete this employee?')) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'deleteEmployee.php?employeeID=' + employeeID, true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Employee deleted successfully.');
                    location.reload(); 
                } else {
                    alert('Failed to delete employee.');
                }
            };

            xhr.send();
        }
    }

    function archiveEmployee(employeeID) {
        if (confirm('Are you sure you want to archive this employee?')) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'archiveEmployee.php?employeeID=' + employeeID, true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert('Employee archived successfully.');
                    location.reload(); 
                } else {
                    alert('Failed to archive employee.');
                }
            };

            xhr.send();
        }
    }

    document.getElementById('addEmployeeForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        var form = this;
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Employee added successfully.');
                form.reset(); 
                location.reload(); 
            } else {
                alert('Failed to add employee.');
            }
        };

        xhr.send(formData);
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>

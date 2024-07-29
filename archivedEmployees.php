<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Employees</title>
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
    <h3>Archived Employees by Department</h3>
    <?php
    include "connection.php";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get distinct departments from archived_employee
    $sql_deps = "SELECT DISTINCT department FROM archived_employee";
    $result_deps = $conn->query($sql_deps);

    if ($result_deps->num_rows > 0) {
        echo "<ul class='list-group'>";
        while ($row_dep = $result_deps->fetch_assoc()) {
            $department = $row_dep['department'];
            echo '<li class="list-group-item">';
            echo '<a href="archivedEmployeesByDepartment.php?department=' . urlencode($department) . '">' . htmlspecialchars($department) . '</a>';
            echo '</li>';
        }
        echo "</ul>";
    } else {
        echo "<p>No archived employees found.</p>";
    }

    $conn->close();
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archived Employees by Department</title>
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
    <h3>Archived Employees in Department</h3>
    <?php
    include "connection.php";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_GET['department'])) {
        $department = $_GET['department'];

        // Get archived employees for the department
        $sql = "SELECT id, name FROM archived_employee WHERE department = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $department);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h4>Department: " . htmlspecialchars($department) . "</h4>";
            echo "<ul class='list-group'>";
            while ($row = $result->fetch_assoc()) {
                echo '<li class="list-group-item">';
                echo htmlspecialchars($row['name']);
                echo '</li>';
            }
            echo "</ul>";
        } else {
            echo "<p>No archived employees found for this department.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>No department specified.</p>";
    }

    $conn->close();
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>

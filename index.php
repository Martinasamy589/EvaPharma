<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Departments</title>
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
        <h3>Add Department</h3>
        <form action="AddDepartment.php" method="post">
            <div class="mb-3">
                <label for="addDep" class="form-label">Add Department:</label>
                <input type="text" id="addDep" name="addDep" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Department</button>
        </form>

        <?php
        // Check for success message
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo '<p class="mt-3 text-success">Department added successfully!</p>';
        }
        ?>

        <h3 class="mt-5">Departments List</h3>
        <ul class="list-group">
            <?php
            include "connection.php";

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch departments
            $sql = "SELECT departmentID, name FROM dep";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                    echo '<a href="employee.php?departmentID=' . $row["departmentID"] . '" style="font-size: 20px;">' . $row["name"] . '</a>';
                    echo '<span>';
                    echo '<a href="deleteDepartment.php?departmentID=' . $row["departmentID"] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this department?\')">Delete</a>';
                    echo '</span>';
                    echo '</li>';
                }
            } else {
                echo '<li class="list-group-item">No departments found</li>';
            }

            $conn->close();
            ?>
        </ul>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>

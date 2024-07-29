<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Departments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
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
        <h3>Add Department</h3>
        <form id="addDepForm" action="AddDepartment.php" method="post">
            <div class="mb-3">
                <label for="addDep" class="form-label">Add Department:</label>
                <input type="text" id="addDep" name="addDep" class="form-control" required>
            </div>
            <button type="button" id="addDepBtn" class="btn btn-primary" style="background-color:#e5c405;">Add Department</button>
        </form>
        <div id="message"></div>

        <h3 class="mt-5">Departments List</h3>
        <ul id="departmentsList" class="list-group">
            <?php
            include "connection.php";

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT departmentID, name FROM dep";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                    echo '<a href="employee.php?departmentID=' . $row["departmentID"] . '" style="font-size: 20px;">' . $row["name"] . '</a>';
                    echo '<span>';
                    echo '<button class="btn btn-sm btn-danger delete-btn" data-department-id="' . $row["departmentID"] . '">Delete</button>';
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            function addDepartment() {
                var departmentName = $('#addDep').val().trim();

                if (departmentName === '') {
                    $('#message').html('<p class="mt-3 text-danger">Department name cannot be empty!</p>');
                    return;
                }

                $.ajax({
                    url: $('#addDepForm').attr('action'),
                    type: 'POST',
                    data: $('#addDepForm').serialize(),
                    success: function(response) {
                        if (response.trim() == 'duplicate') {
                            $('#message').html('<p class="mt-3 text-danger">Department with the same name already exists!</p>');
                        } else if (response.trim().startsWith('success:')) {
                            const departmentName = response.split(':')[1];
                            $('#message').html('<p class="mt-3 text-success">Department "' + departmentName + '" added successfully!</p>');
                            $('#addDep').val('');
                            $('#departmentsList').load(location.href + ' #departmentsList > *');
                        } else if (response.trim().startsWith('error:')) {
                            const errorMessage = response.split(':')[1];
                            $('#message').html('<p class="mt-3 text-danger">' + errorMessage + '</p>');
                        } else {
                            $('#message').html('<p class="mt-3 text-danger">Failed to add department. Please try again.</p>');
                        }
                    }
                });
            }

            $('#addDepBtn').click(function() {
                addDepartment();
            });

            $('#addDep').keypress(function(e) {
                if (e.which == 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
                    addDepartment();
                }
            });

            $(document).on('click', '.delete-btn', function() {
                var departmentID = $(this).data('department-id');
                if (confirm('Are you sure you want to delete this department?')) {
                    $.ajax({
                        url: 'deleteDepartment.php',
                        type: 'GET',
                        data: { departmentID: departmentID },
                        success: function(response) {
                            if (response.trim().startsWith('success:')) {
                                const departmentName = response.split(':')[1];
                                $('#message').html('<p class="mt-3 text-success">Department "' + departmentName + '" deleted successfully!</p>');
                                $('#departmentsList').load(location.href + ' #departmentsList > *');
                            } else if (response.trim().startsWith('error')) {
                                $('#message').html('<p class="mt-3 text-danger">Failed to delete department. Please try again.</p>');
                            } else {
                                $('#message').html('<p class="mt-3 text-danger">Failed to delete department. Please try again.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            $('#message').html('<p class="mt-3 text-danger">An error occurred while deleting the department.</p>');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>

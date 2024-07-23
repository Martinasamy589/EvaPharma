<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Survey</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
        .survey-question {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .survey-question label {
            font-weight: bold;
        }
        .survey-question .checkboxes {
            margin-top: 10px;
        }
        .survey-question .delete-btn {
            margin-top: 10px;
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
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h3>Add Check</h3>
    <form id="addCheckForm">
        <div class="mb-3">
            <label for="checkName">Check Name:</label>
            <input type="text" id="checkName" name="checkName" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="checkType">Check Type:</label>
            <select id="checkType" name="checkType" class="form-control" required>
                <option value="enum">Enum</option>
                <!-- Add more options if needed -->
            </select>
        </div>
        <div class="mb-3">
            <label for="checkOptions">Check Options (comma-separated):</label>
            <textarea id="checkOptions" name="checkOptions" rows="4" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="background-color:#e5c405;">Add Check</button>
    </form>

    <hr>

    <h3>Employee Survey</h3>
    <form id="employeeSurveyForm">
        <?php
        include "connection.php";

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_GET['employeeName'])) {
            $employeeName = $_GET['employeeName'];
            echo '<input type="hidden" name="employeeName" value="' . htmlspecialchars($employeeName) . '">';

            $sql_survey = "SELECT * FROM employee WHERE name = '$employeeName'";
            $result_survey = $conn->query($sql_survey);

            if ($result_survey->num_rows > 0) {
                $row_survey = $result_survey->fetch_assoc();

                $sql_columns = "SHOW COLUMNS FROM employee";
                $result_columns = $conn->query($sql_columns);

                if ($result_columns->num_rows > 0) {
                    while ($row_column = $result_columns->fetch_assoc()) {
                        $columnName = $row_column['Field'];
                        $columnType = $row_column['Type'];

                        if (strpos($columnType, 'enum') !== false) {
                            preg_match("/^enum\(\'(.*)\'\)$/", $columnType, $matches);
                            $enumValues = explode("','", $matches[1]);

                            echo '<div class="survey-question">';
                            echo '<label for="' . $columnName . '">' . ucfirst($columnName) . ':</label><br>';
                            echo '<div class="checkboxes">';
                            foreach ($enumValues as $value) {
                                $checked = in_array($value, explode(",", $row_survey[$columnName])) ? 'checked' : '';
                                echo '<input type="checkbox" id="' . $columnName . '_' . $value . '" name="' . $columnName . '[]" value="' . $value . '" ' . $checked . '>';
                                echo '<label for="' . $columnName . '_' . $value . '">' . $value . '</label>';
                            }
                            echo '</div>';
                            echo '<button type="button" class="btn btn-sm btn-danger delete-btn" data-employee-name="' . htmlspecialchars($employeeName) . '" data-column-name="' . $columnName . '">Delete Question</button>';
                            echo '</div>';
                            echo '<br>';
                        }
                    }
                } else {
                    echo "No enum columns found in the table.";
                }
            } else {
                echo "No survey data found for this employee.";
            }
        } else {
            echo "No employee selected.";
        }

        $conn->close();
        ?>
        <button type="submit" class="btn btn-primary" style="background-color:#e5c405;">Submit Survey</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
<script>
    document.getElementById('addCheckForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'addCheckColumn.php', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                alert(response);
            } else {
                alert('Error adding check.');
            }
            document.getElementById('addCheckForm').reset();
        };

        xhr.send(formData);
    });

    document.getElementById('employeeSurveyForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'submitSurvey.php', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                alert(response);
            } else {
                alert('Error submitting survey.');
            }
            document.getElementById('employeeSurveyForm').reset();
        };

        xhr.send(formData);
    });

    $(document).on('click', '.delete-btn', function() {
        var employeeName = $(this).data('employee-name');
        var columnName = $(this).data('column-name');

        if (confirm('Are you sure you want to delete this question?')) {
            $.ajax({
                url: 'deleteQuestion.php',
                type: 'POST',
                data: {
                    employeeName: employeeName,
                    columnName: columnName
                },
                success: function(response) {
                    if (response.trim() === 'success') {
                        alert('Question deleted successfully.');
                        location.reload(); // Reload the page after successful deletion
                    } else {
                        alert('Failed to delete question.');
                    }
                },
                error: function() {
                    alert('Error occurred while deleting question.');
                }
            });
        }
    });
</script>
</body>
</html>

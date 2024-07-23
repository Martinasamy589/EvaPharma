<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Survey</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <style>
        /* Custom CSS styles */
        .survey-checkboxes {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Employee Survey</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
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
            <button type="submit" class="btn btn-primary">Add Check</button>
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

                                echo '<div class="survey-checkboxes">';
                                echo '<label for="' . $columnName . '">' . ucfirst($columnName) . ':</label><br>';
                                foreach ($enumValues as $value) {
                                    $checked = in_array($value, explode(",", $row_survey[$columnName])) ? 'checked' : '';
                                    echo '<input type="checkbox" id="' . $columnName . '_' . $value . '" name="' . $columnName . '[]" value="' . $value . '" ' . $checked . '>';
                                    echo '<label for="' . $columnName . '_' . $value . '">' . $value . '</label><br>';
                                }
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
            <button type="submit" class="btn btn-primary">Submit Survey</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
    <script>
        // Submit form using AJAX for adding Check
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
                // Reset the form after submission
                document.getElementById('addCheckForm').reset();
            };

            xhr.send(formData);
        });

        // Submit survey form using AJAX
        document.getElementById('employeeSurveyForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'addCheck.php', true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = xhr.responseText;
                    alert(response);
                } else {
                    alert('Error submitting survey.');
                }
                // Reset the form after submission
                document.getElementById('employeeSurveyForm').reset();
            };

            xhr.send(formData);
        });
    </script>
</body>
</html>

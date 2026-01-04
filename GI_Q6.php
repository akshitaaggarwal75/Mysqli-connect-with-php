<html>
<head>
<title>SQL and PHP</title>
</head>
<body>
<h1>Guideline Q6 MySQL and PHP</h1>

<!-- FORM SECTION -->
<form method="post">
    <label>Roll No:</label>
    <input type="text" name="roll" required><br><br>

    <label>Name:</label>
    <input type="text" name="name" required><br><br>

    <label>Course:</label>
    <input type="text" name="course" required><br><br>

    <input type="submit" name="add" value="Add Data">
    <input type="submit" name="delete" value="Delete Data">
    <input type="submit" name="display" value="Display Data">
</form>
<hr>

<?php
// Step 1: Connect to MySQL
$conn = new mysqli("localhost", "root", "");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$qr1 = "Create database sample";
$conn->query($qr1);


$conn->select_db("sample");


$qr3 = "Create table student (
    roll VARCHAR(10) PRIMARY KEY,
    name VARCHAR(25),
    course VARCHAR(15)
)";
$conn->query($qr3);

// Step 5: Create second table for JOIN example
$conn->query("Create table course(
    course_name VARCHAR(15) PRIMARY KEY,
    duration INT
)");

$conn->query("INSERT  INTO course VALUES
('BA', 3),
('Maths', 3),
('BCom', 3),
('BCA', 4)");

//  ADD DATA BUTTON 
if (isset($_POST['add'])) {
    $roll = $_POST['roll'];
    $name = $_POST['name'];
    $course = $_POST['course'];

    if (!empty($roll) && !empty($name) && !empty($course)) {
        $qr4 = "INSERT INTO student (roll, name, course) VALUES ('$roll', '$name', '$course')";
        if ($conn->query($qr4)) {
            echo "<p style='color:green;'>The record is added in the database!</p>";
        } else {
            echo "<p style='color:red;'>Error inserting data: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill all fields before adding data!</p>";
    }
}

// DELETE DATA BUTTON
if (isset($_POST['delete'])) {
    $roll = $_POST['roll'];
    if (!empty($roll)) {
        $qr6 = "DELETE FROM student WHERE roll='$roll'";
        if ($conn->query($qr6)) {
            echo "<p style='color:orange;'> A record with Roll No. $roll is deleted from the database!</p>";
        } else {
            echo "<p style='color:red;'>Error deleting data: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Please enter Roll No. to delete record!</p>";
    }
}

// DISPLAY DATA BUTTON
if (isset($_POST['display'])) {
    $qr5 = "SELECT * FROM student ORDER BY name ASC";
    $res5 = $conn->query($qr5);

    if ($res5->num_rows > 0) {
        echo "<h3>Student Records (Ordered by Name)</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>
              <tr><th>Roll</th><th>Name</th><th>Course</th></tr>";
        while ($r = $res5->fetch_assoc()) {
            echo "<tr>
                    <td>".$r["roll"]."</td>
                    <td>".$r["name"]."</td>
                    <td>".$r["course"]."</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found!</p>";
    }
}

//  JOIN + GROUP BY 
echo "<hr><h3>JOIN + GROUP BY </h3>";

// Correct table and column names
$joinQuery = "SELECT s.course, COUNT(s.roll) AS total_students, c.duration
              FROM student s
              JOIN course c
              ON s.course = c.course_name
              GROUP BY s.course, c.duration
              ORDER BY s.course ASC";

$joinResult = $conn->query($joinQuery);

if ($joinResult && $joinResult->num_rows > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>
          <tr><th>Course</th><th>Duration (Years)</th><th>Total Students</th></tr>";
    while ($row = $joinResult->fetch_assoc()) {
        echo "<tr>
                <td>".$row["course"]."</td>
                <td>".$row["duration"]."</td>
                <td>".$row["total_students"]."</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No joined data to display.</p>";
}

$conn->close();
?>
</body>
</html>
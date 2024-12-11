<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$servername = "localhost"; // Use "127.0.0.1" or your database server's IP address
$username = "umhcngkikzypt"; // Replace with your MySQL username
$password = "cs20webprogramming"; // Replace with your MySQL password
$dbname = "dbud655zvrqm3b"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure all required fields are set
if (isset($_POST['species'], $_POST['breed'], $_POST['age'], $_POST['sex'], $_POST['size'], $_POST['location'])) {
    // Retrieve data from the form
    $species = $conn->real_escape_string($_POST['species']);
    $breed = $conn->real_escape_string($_POST['breed']);
    $age = $conn->real_escape_string($_POST['age']);
    $sex = $conn->real_escape_string($_POST['sex']);
    $size = $conn->real_escape_string($_POST['size']);
    $location = $conn->real_escape_string($_POST['location']);

    // Insert data into the table
    $sql = "INSERT INTO user_submissions (species, breed, age, sex, size, location)
            VALUES ('$species', '$breed', '$age', '$sex', '$size', '$location')";

    // Debugging: Output the SQL query for verification
    echo "SQL Query: $sql<br>";

    if ($conn->query($sql) === TRUE) {
        echo "Data submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Error: Missing required form data.";
}

// Close connection
$conn->close();
?>

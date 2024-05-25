<?php
// Start session
session_start();

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the username (email) and password from the form data
    $username = $_POST['email'];
    $password = $_POST['password'];

    // Establish connection to the database
    $servername = "localhost";
    $username_db = "root";
    $password_db = ""; 
    $dbname = "user_db";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to fetch user's password from the database
    $sql = "SELECT password FROM adopters WHERE email='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, check password
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];

        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variable
            $_SESSION["username"] = $username;
            // Set success message
            $_SESSION["login_success"] = "Login successful.";
            // Redirect user to index.html after successful login
            header("Location: index.html");
            exit();
        } else {
            // Password is incorrect
            echo "Invalid email or password";
        }
    } else {
        // User not found
        echo "User not found";
    }

    // Close connection
    $conn->close();
}
?>


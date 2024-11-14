<?php
$servername = "localhost:3306";
$username = "root";
$password = "Tashreeka94_";
$dbname = "realestate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$imagePath = null;

if (isset($_FILES["imagePath"]) && $_FILES["imagePath"]["error"] == UPLOAD_ERR_OK) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["imagePath"]["name"]);

    if (move_uploaded_file($_FILES["imagePath"]["tmp_name"], $targetFile)) {
        $imagePath = $targetFile;
    } else {
        echo "Sorry, there was an error uploading your image file.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $userType = $_POST["userType"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $checkEmailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $existingUserResult = $checkEmailStmt->get_result();

    if ($existingUserResult->num_rows > 0) {
        echo "<script>
                alert('Error: This email is already registered. Please choose another email.');
                window.location.href = 'adduser.html';
              </script>";
        $checkEmailStmt->close();
        $conn->close();
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email, phone, userType, password, imagePath, registrationTime) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");

    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phone, $userType, $password, $imagePath);

    if ($stmt->execute()) {

        echo "<script>
                alert('User added successfully.');
                window.location.href = 'adduser.html';
              </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}


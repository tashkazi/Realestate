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

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
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
    $preferredProperty = ($userType === 'realtor' || $userType === 'admin') ? null : $_POST["preferredProperty"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    $checkEmailStmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $existingUserResult = $checkEmailStmt->get_result();

    if ($existingUserResult->num_rows > 0) {
        echo "<script>
                alert('Error: This email is already registered. Please choose another email.');
                window.location.href = 'Registration.html';
              </script>";
        $checkEmailStmt->close();
        $conn->close();
        exit();
    }

    $stmtUser = $conn->prepare("INSERT INTO users (firstName, lastName, email, phone, userType, preferredProperty, password, imagePath) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmtUser->bind_param("ssssssss", $firstName, $lastName, $email, $phone, $userType, $preferredProperty, $password, $imagePath);

    if ($stmtUser->execute()) {
        $userId = $stmtUser->insert_id;

        switch ($userType) {
            case 'customer':
                if ($preferredProperty !== null) {
                    $stmtPreferredProperty = $conn->prepare("INSERT INTO preferred_property (userId, propertyType) VALUES (?, ?)");
                    $stmtPreferredProperty->bind_param("is", $userId, $preferredProperty);
                    $stmtPreferredProperty->execute();
                }

                $stmtCustomer = $conn->prepare("INSERT INTO customer (user_id, custom_id) VALUES (?, ?)");
                $customId = 'ctk' . str_pad($userId, 3, '0', STR_PAD_LEFT);
                $stmtCustomer->bind_param("is", $userId, $customId);
                $stmtCustomer->execute();
                break;

            case 'realtor':
                $stmtRealtor = $conn->prepare("INSERT INTO realtor (user_id, custom_id) VALUES (?, ?)");
                $customId = 'rtk' . str_pad($userId, 3, '0', STR_PAD_LEFT);
                $stmtRealtor->bind_param("is", $userId, $customId);
                $stmtRealtor->execute();
                break;

            case 'admin':
                $stmtAdmin = $conn->prepare("INSERT INTO admin (user_id, custom_id) VALUES (?, ?)");
                $customId = 'atk' . str_pad($userId, 3, '0', STR_PAD_LEFT);
                $stmtAdmin->bind_param("is", $userId, $customId);
                $stmtAdmin->execute();
                break;
        }

        header("Location: Login.html?registration=success");
        exit();
    } else {
        echo "Error: " . $stmtUser->error;
    }

    $stmtUser->close();
    $checkEmailStmt->close();
    $conn->close();
}


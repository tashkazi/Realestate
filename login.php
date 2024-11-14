<?php

session_start();

$servername = "localhost:3306";
$username = "root";
$password = "Tashreeka94_";
$dbname = "realestate";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$passwordAttempt = $_POST['password'];

$stmtCheckUser = $conn->prepare("SELECT id, password, userType FROM users WHERE email = ?");
$stmtCheckUser->bind_param("s", $email);
$stmtCheckUser->execute();
$resultCheckUser = $stmtCheckUser->get_result();

if ($resultCheckUser->num_rows === 0) {

    echo "<script>alert('Email does not exist. Please try again.'); window.location.href='Login.html';</script>";
    exit();
}

$stmtCheckUser->close();

$stmt = $conn->prepare("SELECT id, password, userType FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (password_verify($passwordAttempt, $row['password'])) {
        $userId = $row['id'];
        $loginTimestamp = date("Y-m-d H:i:s");
        $stmtInsert = $conn->prepare("INSERT INTO login_records (userId, loginTimeStamp) VALUES (?, ?)");
        $stmtInsert->bind_param("ss", $userId, $loginTimestamp);
        $stmtInsert->execute();

        if ($stmtInsert->affected_rows > 0) {
            echo "Login timestamp recorded successfully.<br>";
        } else {
            echo "Error recording login timestamp.<br>";
        }

        $stmtInsert->close();

        $_SESSION['id'] = $userId;
        $_SESSION['email'] = $email;
        $_SESSION['userType'] = $row['userType'];

        echo "Login Successful! User Type: " . $_SESSION['userType'] . "<br>";

        if ($_SESSION['userType'] === 'realtor') {
            header("Location: realtorhomepage.html");
            exit();
        } elseif ($_SESSION['userType'] === 'admin') {
            header("Location: adminhomepage.html");
            exit();
        } else {
            header("Location: customerhomepage.html");
            exit();
        }
    } else {
        echo "<script>alert('Incorrect email or password. Please try again.'); window.location.href='Login.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Incorrect email or password. Please try again.'); window.location.href='Login.html';</script>";
    exit();
}

$stmt->close();
$conn->close();


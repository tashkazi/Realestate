<?php

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $servername = "localhost:3306";
    $username = "root";
    $password = "Tashreeka94_";
    $dbname = "realestate";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE ID = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        echo "User deleted successfully!";
    } else {

        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'ID parameter not set';
}


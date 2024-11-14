<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = $_POST['year'] ?? null;
    $month = $_POST['month'] ?? null;

    $servername = "localhost:3306";
    $username = "root";
    $password = "Tashreeka94_";
    $dbname = "realestate";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $isValidYear = is_numeric($year) && $year >= 1;

    if (!$isValidYear) {
        echo "Invalid year!";
        exit;
    }

    $isYearlyReport = isset($_POST['reportType']) && $_POST['reportType'] == 'yearly';

    $dateCondition = $isYearlyReport ? "YEAR(`salestable`.`SaleDate`) = ?" : "YEAR(`salestable`.`SaleDate`) = ? AND MONTH(`salestable`.`SaleDate`) = ?";

    if (isset($_POST['salesReport'])) {
        $sqlRealtors = "SELECT
            `users`.`id` AS RealtorID,
            `users`.`firstName`,
            `users`.`lastName`,
            COALESCE(COUNT(`salestable`.`SaleID`), 0) AS `TotalSales`
        FROM
            `realestate`.`users`
        LEFT JOIN
            `realestate`.`salestable` ON `users`.`id` = `salestable`.`RealtorID`
            AND ($dateCondition)
        WHERE
            `users`.`userType` = 'realtor'
        GROUP BY
            `users`.`id`, `users`.`firstName`, `users`.`lastName`
        ORDER BY
            `TotalSales` DESC";

        $stmt = $conn->prepare($sqlRealtors);

        if (!$stmt) {
            die("Error in prepare statement: " . $conn->error);
        }

        if ($isYearlyReport) {
            $stmt->bind_param("i", $year);
        } else {
            $stmt->bind_param("ii", $year, $month);
        }

        $stmt->execute();

        echo "<table border='1'>
            <tr>
                <th>Realtor ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Total Sales</th>
            </tr>";

        $resultRealtors = $stmt->get_result();

        while ($row = $resultRealtors->fetch_assoc()) {
            echo "<tr>
                <td>{$row['RealtorID']}</td>
                <td>{$row['firstName']}</td>
                <td>{$row['lastName']}</td>
                <td>{$row['TotalSales']}</td>
            </tr>";
        }

        echo "</table>";

        $stmt->close();
    } elseif (isset($_POST['propertiesReport'])) {
        // Most Preferred Properties Report
        $sqlPreferredProperties = "SELECT
            `preferred_property`.`propertyType`,
            COUNT(`preferred_property`.`propertyType`) AS `TotalPreferences`
        FROM
            `realestate`.`preferred_property`
        WHERE
            " . ($isYearlyReport ? "YEAR(`preferred_property`.`createdAt`) = ?" : "YEAR(`preferred_property`.`createdAt`) = ? AND MONTH(`preferred_property`.`createdAt`) = ?") . "
        GROUP BY
            `preferred_property`.`propertyType`
        ORDER BY
            `TotalPreferences` DESC";

        $stmt = $conn->prepare($sqlPreferredProperties);

        if (!$stmt) {
            die("Error in prepare statement: " . $conn->error);
        }

        if ($isYearlyReport) {
            $stmt->bind_param("i", $year);
        } else {
            $stmt->bind_param("ii", $year, $month);
        }

        $stmt->execute();
        $resultPreferredProperties = $stmt->get_result();

        echo "<h2>Most Preferred Properties Summary</h2>";
        echo "<table border='1'>
            <tr>
                <th>Property Type</th>
                <th>Total Preferences</th>
            </tr>";

        while ($row = $resultPreferredProperties->fetch_assoc()) {
            echo "<tr>
                <td>{$row['propertyType']}</td>
                <td>{$row['TotalPreferences']}</td>
            </tr>";
        }

        echo "</table>";

        $stmt->close();

    } elseif (isset($_POST['customersReport'])) {

        $sqlTopCustomers = "SELECT
            `login_records`.`userId`,
            COUNT(`login_records`.`userId`) AS `LoginCount`,
            `users`.`firstName`,
            `users`.`lastName`
        FROM
            `realestate`.`login_records`
        LEFT JOIN
            `realestate`.`users` ON `login_records`.`userId` = `users`.`id`
        LEFT JOIN
            `realestate`.`customer` ON `users`.`id` = `customer`.`user_id`
        WHERE
            `users`.`userType` = 'customer'
            AND (" . ($isYearlyReport ? "YEAR(`login_records`.`loginTimeStamp`) = ?" : "YEAR(`login_records`.`loginTimeStamp`) = ? AND MONTH(`login_records`.`loginTimeStamp`) = ?") . ")
        GROUP BY
            `login_records`.`userId`
        ORDER BY
            `LoginCount` DESC
        LIMIT 3";

        $stmt = $conn->prepare($sqlTopCustomers);

        if (!$stmt) {
            die("Error in prepare statement: " . $conn->error);
        }

        if ($isYearlyReport) {
            $stmt->bind_param("i", $year);
        } else {
            $stmt->bind_param("ii", $year, $month);
        }

        $stmt->execute();
        $resultTopCustomers = $stmt->get_result();

        echo "<h2>Top 3 Customers Who Visit the Website Most Frequently</h2>";
        echo "<table border='1'>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Login Count</th>
            </tr>";

        while ($row = $resultTopCustomers->fetch_assoc()) {
            echo "<tr>
                <td>{$row['userId']}</td>
                <td>{$row['firstName']}</td>
                <td>{$row['lastName']}</td>
                <td>{$row['LoginCount']}</td>
            </tr>";
        }

        echo "</table>";

        $stmt->close();
    }

    $conn->close();
}


<?php
function executeStatement($statement, $params = array()): array {
    $conn = getConnection();
    $stmt = $conn -> prepare($statement);
    if ($params) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    $stmt -> execute();
    $stmt -> store_result();
    return array($stmt, $conn);
}

function getConnection() {
    $db_host = $_ENV["DB_HOST"];
    $db_user = $_ENV["DB_USER"];
    $db_pass = $_ENV["DB_PASS"];
    $db_name = $_ENV["DB_NAME"];
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn -> connect_error) {
        die("Connection failed: " . $conn -> connect_error);
    }

    return $conn;
}

?>
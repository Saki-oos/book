<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

// แสดง error ระหว่างพัฒนา
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connectdb.php';

try {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY book_id DESC");
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array for consistency

    echo json_encode([
        "status" => "success",
        "data" => $books
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
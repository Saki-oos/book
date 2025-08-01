<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'connectdb.php';

$input = json_decode(file_get_contents("php://input"), true);
$book_id = $input['book_id'] ?? null;
$title = trim($input['title'] ?? '');
$author = trim($input['author'] ?? '');
$published_date = trim($input['published_date'] ?? null);
$isbn = trim($input['isbn'] ?? null);
$pages = (int)($input['pages'] ?? 0);
$price = (float)($input['price'] ?? 0.0);
$in_stock = (int)($input['in_stock'] ?? 1);

if ($book_id && $title && $author) {
    try {
        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, published_date = ?, isbn = ?, pages = ?, price = ?, in_stock = ? WHERE book_id = ?");
        $stmt->execute([$title, $author, $published_date, $isbn, $pages, $price, $in_stock, $book_id]);

        if ($stmt->rowCount()) {
            echo json_encode([
                "status" => "success",
                "message" => "Book updated successfully."
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "status" => "error",
                "message" => "ไม่พบข้อมูลที่ต้องการอัปเดต"
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "กรุณากรอกข้อมูลให้ครบถ้วน"
    ]);
}
?>
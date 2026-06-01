<?php
include 'db.php';

$id = $_POST['id'];

// Retrieve file name for this book (if any) so we can remove the uploaded file
$getSql = "SELECT file_name FROM books WHERE id = ?";
$getStmt = $conn->prepare($getSql);
$getStmt->bind_param("i", $id);
$getStmt->execute();
$res = $getStmt->get_result();
$row = $res->fetch_assoc();

if ($row && !empty($row['file_name'])) {
    $filePath = __DIR__ . '/uploads/' . $row['file_name'];
    if (file_exists($filePath)) {
        @unlink($filePath);
    }
}

$sql = "DELETE FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Deleted";
} else {
    echo "Error";
}
?>
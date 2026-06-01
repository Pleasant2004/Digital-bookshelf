<?php
include 'db.php';

$id = $_POST['id'];

$getBook = "SELECT is_read FROM books WHERE id = ?";
$stmt = $conn->prepare($getBook);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

$newStatus = $book['is_read'] == 1 ? 0 : 1;

$update = "UPDATE books SET is_read = ? WHERE id = ?";
$stmt2 = $conn->prepare($update);
$stmt2->bind_param("ii", $newStatus, $id);

if ($stmt2->execute()) {
    echo "Updated";
} else {
    echo "Error";
}
?>
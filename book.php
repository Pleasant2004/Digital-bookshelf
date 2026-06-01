<?php

include 'db.php';

$id = $_GET['id'];

$sql =
"SELECT * FROM books WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$book = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> -->
</head>
<body>

<div class="app">
    <div style="margin:18px 0">
        <a href="index.php" style="text-decoration:none;color:#2563eb">← Back to library</a>
    </div>

    <div class="details-container">
        <h2 style="margin-bottom:8px"><?php echo htmlspecialchars($book['title']); ?></h2>
        <div style="color:#475569;margin-bottom:12px">By <?php echo htmlspecialchars($book['author']); ?></div>

        <p style="color:#334155;line-height:1.6;margin-bottom:18px"><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>

        <?php if(!empty($book['file_name'])){
            $ext = strtolower(pathinfo($book['file_name'], PATHINFO_EXTENSION));
        ?>

            <iframe class="details-embed" src="preview.php?name=<?php echo urlencode($book['file_name']); ?>" frameborder="0"></iframe>

        <?php } ?>

        <div style="margin-top:14px">
            <strong>Status:</strong>
            <span style="margin-left:8px"><?php echo $book['is_read'] == 1 ? 'Read' : 'Unread'; ?></span>
        </div>
    </div>

</div>

</body>
</html>
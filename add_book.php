<?php

include 'db.php';

// FORM VALUES
$title = $_POST['title'];

$author = $_POST['author'];

$description = $_POST['description'];

$genre = $_POST['genre'];

// FILE
$fileName = "";

if(isset($_FILES['bookFile'])){

    $file = $_FILES['bookFile'];

    $fileName =
        time() . "_" . $file['name'];

    $tmpName =
        $file['tmp_name'];

    $uploadPath =
        "uploads/" . $fileName;

    move_uploaded_file(
        $tmpName,
        $uploadPath
    );
}

// INSERT QUERY
$sql = "
INSERT INTO books
(title, author, description, genre, file_name)

VALUES (?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssss",
    $title,
    $author,
    $description,
    $genre,
    $fileName
);

if($stmt->execute()){

    echo "Book Added";

}else{

    echo "Error";
}

?>
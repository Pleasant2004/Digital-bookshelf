<?php
// Secure file sender: file.php?name=uploaded_filename.ext
// Serves files from the uploads/ directory with inline Content-Disposition

// Simple whitelist: only serve files that exist in uploads
if (!isset($_GET['name'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    echo 'Missing file name';
    exit;
}

$name = $_GET['name'];
// Prevent path traversal
$name = basename($name);
$path = __DIR__ . '/uploads/' . $name;

if (!file_exists($path) || !is_file($path)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo 'File not found';
    exit;
}

$download = isset($_GET['download']) && ($_GET['download'] === '1' || strtolower($_GET['download']) === 'true');

// Determine MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $path);
finfo_close($finfo);
if (!$mime) $mime = 'application/octet-stream';

// Send headers. Use attachment disposition when ?download=1 provided.
header('Content-Type: ' . $mime);
if ($download) {
    header('Content-Disposition: attachment; filename="' . basename($path) . '"');
} else {
    header('Content-Disposition: inline; filename="' . basename($path) . '"');
}
header('Content-Length: ' . filesize($path));
// Optional: prevent caching issues
header('Cache-Control: public, max-age=86400');

// Output file
readfile($path);
exit;
?>
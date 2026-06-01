<?php
// preview.php?name=filename.ext
// Renders an HTML preview for common file types. Falls back to download link.

if (!isset($_GET['name'])) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
    echo 'Missing file name';
    exit;
}

$name = basename($_GET['name']);
$path = __DIR__ . '/uploads/' . $name;

if (!file_exists($path) || !is_file($path)) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo 'File not found';
    exit;
}

$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

function h($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Preview - <?php echo h($name); ?></title>
  <style>body{font-family:Inter,system-ui,Arial,sans-serif;margin:18px;color:#0f172a} .toolbar{margin-bottom:12px} .details-embed{width:100%;height:80vh;border:1px solid #e6eefc;border-radius:8px} pre{white-space:pre-wrap;background:#fbfdff;padding:16px;border-radius:8px;border:1px solid #eef2ff}</style>
</head>
<body>

<div class="toolbar">
  <a href="index.php">← Back</a>
  &nbsp;·&nbsp;
  <a href="file.php?name=<?php echo urlencode($name); ?>" target="_blank">Open raw</a>
</div>

<h3><?php echo h($name); ?></h3>

<?php
// Images
if (in_array($ext, ['png','jpg','jpeg','gif','webp','svg'])){
    echo '<div><img src="file.php?name='.urlencode($name).'" style="max-width:100%;height:auto;border-radius:8px;border:1px solid #eef2ff"></div>';
    exit;
}

// PDF
if ($ext === 'pdf'){
    echo '<embed class="details-embed" src="file.php?name='.urlencode($name).'" type="application/pdf">';
    exit;
}

// Plain text-like
if (in_array($ext, ['txt','md','csv','log','json','xml','html'])){
    $content = file_get_contents($path);
    echo '<pre>'.h($content).'</pre>';
    exit;
}

// DOCX: attempt simple extraction
if ($ext === 'docx'){
    if (class_exists('ZipArchive')){
        $zip = new ZipArchive();
        if ($zip->open($path) === TRUE){
            $xml = $zip->getFromName('word/document.xml');
            $zip->close();
            if ($xml !== false){
                // extract text from <w:t> elements
                $matches = [];
                preg_match_all('/<w:t[^>]*>(.*?)<\/w:t>/si', $xml, $matches);
                $text = '';
                if (!empty($matches[1])){
                    $text = implode("\n", array_map(function($s){ return strip_tags($s); }, $matches[1]));
                }
                if (trim($text) !== ''){
                    echo '<pre>'.h($text).'</pre>';
                    exit;
                }
            }
        }
    }
    // fallback
    echo '<p>Could not render DOCX preview. You can <a href="file.php?name='.urlencode($name).'">download the file</a> or host it publicly for external viewers.</p>';
    exit;
}

// Other types: show message and download link
echo '<p>Preview not available for this file type. You can <a href="file.php?name='.urlencode($name).'">download / open the file</a>.</p>';

?>

</body>
</html>

<?php
// run_sql.php
// Usage (web): http://localhost/digital-bookshelf/run_sql.php?run=1
// Usage (cli): php run_sql.php --run

include 'db.php';

$run = false;

if (php_sapi_name() === 'cli') {
    foreach ($argv as $arg) {
        if ($arg === '--run' || $arg === 'run=1') {
            $run = true;
            break;
        }
    }
} else {
    if (isset($_GET['run']) && $_GET['run'] == '1') {
        $run = true;
    }
}

if (!$run) {
    $msg = "To execute the SQL, call this script with '?run=1' in a browser or run via CLI with '--run'.\n";
    echo nl2br(htmlentities($msg));
    exit;
}

$sqlFile = __DIR__ . '/database.sql';

if (!file_exists($sqlFile)) {
    echo "database.sql not found at: $sqlFile";
    exit;
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    echo "Failed to read database.sql";
    exit;
}

// Execute multiple queries
if ($conn->multi_query($sql)) {
    do {
        if ($res = $conn->store_result()) {
            $res->free();
        }
    } while ($conn->more_results() && $conn->next_result());

    echo "SQL executed successfully.";
} else {
    echo "Error executing SQL: " . $conn->error;
}

$conn->close();

?>
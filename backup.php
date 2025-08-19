<?php
include('subdash.php');

// Database credentials
$host = "127.0.0.1:3307";   // Change if necessary
$username = "root";         // Your DB username
$password = "";             // Your DB password
$database = "sms";          // Your DB name

// Set filename for the backup
$backupFile = 'backup_' . date("Y-m-d_H-i-s") . '.sql';

// Function to export database
function backupDatabase($host, $username, $password, $database, $backupFile)
{
    // Connect to DB
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch all tables
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    if (!$result) {
        die("Error fetching tables: " . $conn->error);
    }
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }

    $sqlScript = "";
    foreach ($tables as $table) {
        // Get CREATE TABLE statement
        $query = $conn->query("SHOW CREATE TABLE `$table`");
        if (!$query) {
            die("Error fetching CREATE TABLE for $table: " . $conn->error);
        }
        $row = $query->fetch_assoc();
        $sqlScript .= "\n\n" . $row['Create Table'] . ";\n\n";

        // Get table data
        $result = $conn->query("SELECT * FROM `$table`");
        if (!$result) {
            die("Error fetching data from $table: " . $conn->error);
        }

        while ($row = $result->fetch_assoc()) {
            $sqlScript .= "INSERT INTO `$table` VALUES(";
            foreach ($row as $value) {
                $value = $conn->real_escape_string($value);
                $sqlScript .= "'" . $value . "', ";
            }
            $sqlScript = rtrim($sqlScript, ", ");
            $sqlScript .= ");\n";
        }
        $sqlScript .= "\n\n";
    }

    // Save to file
    if (file_put_contents($backupFile, $sqlScript) === false) {
        die("Error writing backup file!");
    }

    return $backupFile;
}

// Generate backup file
$backupFilePath = backupDatabase($host, $username, $password, $database, $backupFile);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Backup</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); text-align: center; }
        .btn-download {
            display: inline-block;
            background-color: #4e73df; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;
        }
        .btn-download:hover { background-color: #375ac0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Database Backup</h2>
        <p>Click the button below to download the latest backup.</p>
        <a class="btn-download" href="<?= htmlspecialchars($backupFilePath) ?>" download>Download Backup</a>
    </div>
</body>
</html>

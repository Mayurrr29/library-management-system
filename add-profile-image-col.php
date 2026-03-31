<?php
include('includes/config.php');
try {
    $dbh->exec("ALTER TABLE tblstudents ADD COLUMN IF NOT EXISTS ProfileImage VARCHAR(255) DEFAULT NULL");
    echo "✅ ProfileImage column added (or already exists).";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

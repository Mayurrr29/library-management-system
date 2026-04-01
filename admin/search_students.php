<?php
require_once("includes/config.php");
header('Content-Type: application/json');

if (!empty($_POST['q'])) {
    $q = '%' . trim($_POST['q']) . '%';
    $sql = "SELECT StudentId, FullName, EmailId, MobileNumber, Status 
            FROM tblstudents 
            WHERE (StudentId LIKE :q OR FullName LIKE :q2 OR EmailId LIKE :q3)
            AND Status = 1
            LIMIT 10";
    $query = $dbh->prepare($sql);
    $query->bindParam(':q',  $q, PDO::PARAM_STR);
    $query->bindParam(':q2', $q, PDO::PARAM_STR);
    $query->bindParam(':q3', $q, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
} else {
    echo json_encode([]);
}
?>

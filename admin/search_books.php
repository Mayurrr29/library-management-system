<?php
require_once("includes/config.php");
header('Content-Type: application/json');

if (!empty($_POST['q'])) {
    $q   = '%' . trim($_POST['q']) . '%';
    $qex = trim($_POST['q']);

    $sql = "SELECT tblbooks.id, tblbooks.BookName, tblbooks.ISBNNumber, tblbooks.bookQty,
                   tblauthors.AuthorName,
                   COUNT(CASE WHEN tblissuedbookdetails.RetrunStatus IS NULL AND tblissuedbookdetails.id IS NOT NULL THEN 1 END) AS issuedCount
            FROM tblbooks
            LEFT JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId
            LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
                   AND (tblissuedbookdetails.RetrunStatus IS NULL OR tblissuedbookdetails.RetrunStatus = '')
            WHERE (tblbooks.ISBNNumber = :exact OR tblbooks.BookName LIKE :q OR tblbooks.ISBNNumber LIKE :q2)
            GROUP BY tblbooks.id
            LIMIT 10";

    $query = $dbh->prepare($sql);
    $query->bindParam(':exact', $qex, PDO::PARAM_STR);
    $query->bindParam(':q',  $q, PDO::PARAM_STR);
    $query->bindParam(':q2', $q, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    // Add 'available' field
    foreach ($results as &$row) {
        $row['available'] = max(0, (int)$row['bookQty'] - (int)$row['issuedCount']);
    }
    unset($row);

    echo json_encode($results);
} else {
    echo json_encode([]);
}
?>

<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cat    = isset($_GET['cat'])    ? (int)$_GET['cat']    : 0;

$params = [];
$conditions = [];

if ($search !== '') {
    $conditions[] = "(tblbooks.BookName LIKE :search OR tblauthors.AuthorName LIKE :search OR tblbooks.ISBNNumber LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}
if ($cat > 0) {
    $conditions[] = "tblbooks.CatId = :cat";
    $params[':cat'] = $cat;
}

$where_clause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName,
               tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id as bookid,
               tblbooks.bookImage, tblbooks.bookQty,
               COUNT(tblissuedbookdetails.id) AS issuedBooks,
               SUM(CASE WHEN tblissuedbookdetails.ReturnDate IS NOT NULL AND tblissuedbookdetails.ReturnDate != '' THEN 1 ELSE 0 END) AS returnedbook
        FROM tblbooks
        LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
        LEFT JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId
        LEFT JOIN tblcategory ON tblcategory.id = tblbooks.CatId
        $where_clause
        GROUP BY tblbooks.id
        ORDER BY tblbooks.BookName ASC";

$query = $dbh->prepare($sql);
foreach ($params as $key => $val) {
    $query->bindValue($key, $val, PDO::PARAM_STR);
}
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
$total_results = count($results);

// Category Name for count string
$catName = '';
if ($cat > 0) {
    $cat_sql = "SELECT CategoryName FROM tblcategory WHERE id = :cat LIMIT 1";
    $cat_q = $dbh->prepare($cat_sql);
    $cat_q->bindParam(':cat', $cat, PDO::PARAM_INT);
    $cat_q->execute();
    $catRow = $cat_q->fetch(PDO::FETCH_OBJ);
    if ($catRow) {
        $catName = $catRow->CategoryName;
    }
}

$html = '';

if ($total_results > 0) {
    foreach ($results as $result) {
        $available = ($result->issuedBooks == 0)
            ? $result->bookQty
            : max(0, $result->bookQty - ($result->issuedBooks - $result->returnedbook));

        $img = 'admin/bookimg/' . htmlentities($result->bookImage);
        if (empty($result->bookImage) || !file_exists($img)) {
            $img = 'https://via.placeholder.com/300x450/e8e8e8/9ca3af?text=' . urlencode($result->BookName);
        }

        $availHtml = '';
        if ($available > 0) {
            $availHtml = '<span class="avail-badge avail-yes">' . $available . ' avail.</span>';
        } else {
            $availHtml = '<span class="avail-badge avail-no">Unavailable</span>';
        }

        $catLabel = htmlentities($result->CategoryName ?: 'Uncategorized');
        $titleLabel = htmlentities($result->BookName);
        $authorLabel = htmlentities($result->AuthorName ?: 'Unknown Author');
        $isbnLabel = htmlentities($result->ISBNNumber);
        $bookId = htmlentities($result->bookid);

        $html .= <<<HTML
        <a href="book-detail.php?bookid={$bookId}" class="book-card-item" style="color: inherit; text-decoration: none;">
            <div class="book-img-wrap">
                <img src="{$img}" alt="{$titleLabel}">
            </div>
            <div class="book-info">
                <div class="book-category-tag">{$catLabel}</div>
                <div class="book-title">{$titleLabel}</div>
                <div class="book-author">{$authorLabel}</div>
                <div class="book-footer">
                    <div class="book-isbn">{$isbnLabel}</div>
                    {$availHtml}
                </div>
            </div>
        </a>
HTML;
    }
} else {
    $html = <<<HTML
    <div class="empty-state">
        <h3>No books found</h3>
        <p>No books match your search. Try different keywords or browse all categories.</p>
        <a href="listed-books.php">Browse all books</a>
    </div>
HTML;
}

echo json_encode([
    'status' => 'success',
    'html' => $html,
    'count' => $total_results,
    'catName' => htmlentities($catName)
]);
?>

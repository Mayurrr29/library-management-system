<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $cat    = isset($_GET['cat'])    ? (int)$_GET['cat']    : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LibraryMS — Browse Books</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background: #f7f8f8; margin: 0; }

    /* ── Page header ── */
    .lb-page-header {
        background: #fff;
        border-bottom: 1px solid #e8e8e8;
        padding: 28px 48px;
    }

    .lb-page-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 3px;
        letter-spacing: -0.4px;
    }

    .lb-page-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* ── Filter bar ── */
    .lb-filter-bar {
        background: #fff;
        border-bottom: 1px solid #e8e8e8;
        padding: 14px 48px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .lb-search-wrap {
        display: flex;
        align-items: center;
        background: #f7f8f8;
        border: 1px solid #e4e4e4;
        border-radius: 7px;
        overflow: hidden;
        flex: 1;
        min-width: 260px;
        max-width: 460px;
    }

    .lb-search-wrap i {
        padding: 0 12px;
        color: #9ca3af;
        font-size: 13px;
    }

    .lb-search-input {
        border: none;
        outline: none;
        background: transparent;
        font-family: 'Inter', sans-serif;
        font-size: 13.5px;
        color: #111827;
        padding: 10px 0;
        width: 100%;
    }

    .lb-search-input::placeholder { color: #d1d5db; }

    .lb-filter-select {
        background: #f7f8f8;
        border: 1px solid #e4e4e4;
        border-radius: 7px;
        padding: 10px 14px;
        font-family: 'Inter', sans-serif;
        font-size: 13.5px;
        font-weight: 500;
        color: #374151;
        outline: none;
        cursor: pointer;
    }

    .lb-search-btn {
        background: #18534f;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 7px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .lb-search-btn:hover { background: #0f3d3a; }

    .lb-clear-btn {
        background: transparent;
        border: 1px solid #e4e4e4;
        border-radius: 7px;
        padding: 10px 14px;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s;
        display: inline-block;
    }

    .lb-clear-btn:hover {
        background: #f3f4f6;
        color: #374151;
        text-decoration: none;
    }

    /* ── Results meta ── */
    .lb-results-meta {
        padding: 16px 48px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .lb-results-count {
        font-size: 13px;
        color: #4b5563;
    }

    .lb-results-count strong {
        color: #111827;
        font-weight: 700;
    }

    /* ── Grid ── */
    .lb-grid-wrap {
        padding: 20px 48px 56px;
    }

    .lb-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 20px;
    }

    .book-card-item {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        overflow: hidden;
        transition: border-color 0.18s, box-shadow 0.18s, transform 0.18s;
        display: flex;
        flex-direction: column;
    }

    .book-card-item:hover {
        border-color: #18534f;
        box-shadow: 0 6px 20px rgba(24, 83, 79, 0.1);
        transform: translateY(-3px);
    }

    .book-img-wrap {
        width: 100%;
        height: 260px;
        background: #f3f4f6;
        overflow: hidden;
    }

    .book-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s;
    }

    .book-card-item:hover .book-img-wrap img {
        transform: scale(1.03);
    }

    .book-info {
        padding: 14px 16px 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .book-category-tag {
        font-size: 10.5px;
        font-weight: 700;
        color: #18534f;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 5px;
    }

    .book-title {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        line-height: 1.4;
        margin-bottom: 3px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-author {
        font-size: 12.5px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .book-footer {
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .book-isbn {
        font-size: 11px;
        color: #6b7280;
        font-family: monospace;
    }

    .avail-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .avail-yes { background: #f0fdf4; color: #15803d; }
    .avail-no  { background: #fef2f2; color: #b91c1c; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 72px 20px;
        grid-column: 1 / -1;
    }

    .empty-state h3 {
        font-size: 17px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 13.5px;
        color: #9ca3af;
        margin-bottom: 20px;
    }

    .empty-state a {
        display: inline-flex;
        align-items: center;
        background: #18534f;
        color: #fff;
        padding: 10px 22px;
        border-radius: 7px;
        font-weight: 600;
        font-size: 13.5px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .empty-state a:hover { background: #0f3d3a; text-decoration: none; color: #fff; }
    </style>
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="content-wrapper" style="background:#f7f8f8 !important; padding:0 !important;">

    <!-- Page header -->
    <div class="lb-page-header">
        <h1>Browse Books</h1>
        <p>
            <?php if ($search || $cat): ?>
                Showing results<?php if ($search): ?> for "<strong style="color:#111827"><?php echo htmlentities($search); ?></strong>"<?php endif; ?>
            <?php else: ?>
                Explore the complete library catalogue
            <?php endif; ?>
        </p>
    </div>

    <!-- Filter Bar -->
    <div class="lb-filter-bar">
        <form action="listed-books.php" method="GET" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; flex:1;">
            <div class="lb-search-wrap">
                <i class="fa fa-search"></i>
                <input type="text" name="search" value="<?php echo htmlentities($search); ?>" class="lb-search-input" placeholder="Search title, author, ISBN…" autocomplete="off">
            </div>
            <select name="cat" class="lb-filter-select">
                <option value="">All Categories</option>
                <?php
                $cat_sql = "SELECT id, CategoryName FROM tblcategory ORDER BY CategoryName";
                $cat_q = $dbh->prepare($cat_sql);
                $cat_q->execute();
                $all_cats = $cat_q->fetchAll(PDO::FETCH_OBJ);
                foreach ($all_cats as $c) {
                    $sel = ($cat == $c->id) ? 'selected' : '';
                    echo '<option value="' . $c->id . '" ' . $sel . '>' . htmlentities($c->CategoryName) . '</option>';
                }
                ?>
            </select>
            <button type="submit" class="lb-search-btn">Search</button>
            <?php if ($search || $cat): ?>
                <a href="listed-books.php" class="lb-clear-btn">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php
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
    ?>

    <div class="lb-results-meta">
        <div class="lb-results-count">
            <strong><?php echo $total_results; ?></strong>
            book<?php echo $total_results != 1 ? 's' : ''; ?> found
            <?php if ($cat): ?>
                <?php $filtered_cats = array_filter($all_cats, fn($c) => $c->id == $cat); $found_cat = reset($filtered_cats); ?>
                <?php if ($found_cat): ?> in <strong><?php echo htmlentities($found_cat->CategoryName); ?></strong><?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="lb-grid-wrap">
        <div class="lb-grid">
            <?php if ($total_results > 0):
                foreach ($results as $result):
                    $available = ($result->issuedBooks == 0)
                        ? $result->bookQty
                        : max(0, $result->bookQty - ($result->issuedBooks - $result->returnedbook));

                    $img = 'admin/bookimg/' . htmlentities($result->bookImage);
                    if (empty($result->bookImage) || !file_exists($img)) {
                        $img = 'https://via.placeholder.com/300x450/e8e8e8/9ca3af?text=' . urlencode($result->BookName);
                    }
            ?>
            <div class="book-card-item">
                <div class="book-img-wrap">
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlentities($result->BookName); ?>">
                </div>
                <div class="book-info">
                    <div class="book-category-tag"><?php echo htmlentities($result->CategoryName ?: 'Uncategorized'); ?></div>
                    <div class="book-title"><?php echo htmlentities($result->BookName); ?></div>
                    <div class="book-author"><?php echo htmlentities($result->AuthorName ?: 'Unknown Author'); ?></div>
                    <div class="book-footer">
                        <div class="book-isbn"><?php echo htmlentities($result->ISBNNumber); ?></div>
                        <?php if ($available > 0): ?>
                            <span class="avail-badge avail-yes"><?php echo $available; ?> avail.</span>
                        <?php else: ?>
                            <span class="avail-badge avail-no">Unavailable</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach;
            else: ?>
            <div class="empty-state">
                <h3>No books found</h3>
                <p>No books match your search. Try different keywords or browse all categories.</p>
                <a href="listed-books.php">Browse all books</a>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>

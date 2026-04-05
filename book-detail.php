<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['request_borrow'])) {
        $studentId = $_SESSION['stdid'];
        $bookId = intval($_POST['bookid']);

        // Check if there's already a pending request
        $chk_sql = "SELECT id FROM tblborrowrequests WHERE StudentId = :studentId AND BookId = :bookId AND Status = 0";
        $chk_query = $dbh->prepare($chk_sql);
        $chk_query->bindParam(':studentId', $studentId, PDO::PARAM_STR);
        $chk_query->bindParam(':bookId', $bookId, PDO::PARAM_STR);
        $chk_query->execute();
        
        if ($chk_query->rowCount() == 0) {
            $sql = "INSERT INTO tblborrowrequests (StudentId, BookId, Status) VALUES (:studentId, :bookId, 0)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentId', $studentId, PDO::PARAM_STR);
            $query->bindParam(':bookId', $bookId, PDO::PARAM_STR);
            $query->execute();
            $_SESSION['msg'] = "Borrow request submitted successfully. Waiting for Admin approval.";
        } else {
            $_SESSION['error'] = "You already have a pending request for this book.";
        }
        header("location:book-detail.php?bookid=" . $bookId);
        exit();
    }

    $bookid = intval($_GET['bookid']);
    $sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName,
                   tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id as bookid,
                   tblbooks.bookImage, tblbooks.bookQty,
                   COUNT(tblissuedbookdetails.id) AS issuedBooks,
                   SUM(CASE WHEN tblissuedbookdetails.ReturnDate IS NOT NULL AND tblissuedbookdetails.ReturnDate != '' THEN 1 ELSE 0 END) AS returnedbook
            FROM tblbooks
            LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
            LEFT JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId
            LEFT JOIN tblcategory ON tblcategory.id = tblbooks.CatId
            WHERE tblbooks.id = :bookid
            GROUP BY tblbooks.id";

    $query = $dbh->prepare($sql);
    $query->bindParam(':bookid', $bookid, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        // Book not found, optionally redirect or show error
        header('location:listed-books.php');
        exit;
    }

    $available = ($result->issuedBooks == 0)
        ? $result->bookQty
        : max(0, $result->bookQty - ($result->issuedBooks - $result->returnedbook));

    $img = 'admin/bookimg/' . htmlentities($result->bookImage);
    if (empty($result->bookImage) || !file_exists($img)) {
        $img = 'https://via.placeholder.com/400x600/e8e8e8/9ca3af?text=' . urlencode($result->BookName);
    }

    // Check if current user has requested this book
    $studentId = $_SESSION['stdid'];
    $req_sql = "SELECT Status FROM tblborrowrequests WHERE StudentId = :studentId AND BookId = :bookId ORDER BY id DESC LIMIT 1";
    $req_query = $dbh->prepare($req_sql);
    $req_query->bindParam(':studentId', $studentId, PDO::PARAM_STR);
    $req_query->bindParam(':bookId', $bookid, PDO::PARAM_STR);
    $req_query->execute();
    $req_status = false;
    if ($req_query->rowCount() > 0) {
        $req_result = $req_query->fetch(PDO::FETCH_OBJ);
        $req_status = $req_result->Status; // 0=Pending, 1=Approved, 2=Rejected
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title>LibraryMS — <?php echo htmlentities($result->BookName); ?></title>
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet" />
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

            body {
                font-family: 'Inter', sans-serif;
                background: #f7f8f8;
                margin: 0;
            }

            .bd-page-header {
                background: #fff;
                border-bottom: 1px solid #e8e8e8;
                padding: 28px 48px;
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .bd-back-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: #f3f4f6;
                color: #4b5563;
                text-decoration: none;
                transition: all 0.2s;
            }

            .bd-back-btn:hover {
                background: #e5e7eb;
                color: #111827;
                text-decoration: none;
            }

            .bd-page-title {
                font-size: 20px;
                font-weight: 700;
                color: #111827;
                margin: 0;
                letter-spacing: -0.4px;
            }

            .bd-container {
                padding: 40px 48px;
                max-width: 1200px;
                margin: 0 auto;
            }

            .bd-card {
                background: #fff;
                border: 1px solid #e8e8e8;
                border-radius: 12px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }

            @media(min-width: 768px) {
                .bd-card {
                    flex-direction: row;
                }
            }

            .bd-image-section {
                background: #f9fafb;
                padding: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-right: 1px solid #e8e8e8;
                flex: 0 0 380px;
            }

            .bd-book-cover {
                width: 100%;
                max-width: 250px;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                display: block;
            }

            .bd-info-section {
                padding: 40px;
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .bd-category-tag {
                display: inline-block;
                background: #f0fdf4;
                color: #15803d;
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 16px;
            }

            .bd-book-title {
                font-size: 32px;
                font-weight: 700;
                color: #111827;
                line-height: 1.2;
                margin-bottom: 8px;
            }

            .bd-author-name {
                font-size: 18px;
                color: #4b5563;
                font-weight: 500;
                margin-bottom: 24px;
            }

            .bd-author-name i {
                color: #9ca3af;
                margin-right: 6px;
            }

            .bd-divider {
                height: 1px;
                background: #e5e7eb;
                margin: 24px 0;
            }

            .bd-meta-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 24px;
            }

            .bd-meta-item {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .bd-meta-label {
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #6b7280;
                font-weight: 600;
            }

            .bd-meta-val {
                font-size: 15px;
                font-weight: 600;
                color: #111827;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .bd-meta-val.price {
                color: #18534f;
            }

            .avail-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 14px;
                font-weight: 600;
            }

            .avail-yes {
                color: #15803d;
            }

            .avail-no {
                color: #b91c1c;
            }

            .avail-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
            }

            .avail-yes .avail-dot {
                background: #16a34a;
            }

            .avail-no .avail-dot {
                background: #dc2626;
            }

            .bd-actions {
                margin-top: 40px;
                display: flex;
                gap: 16px;
            }

            .bd-btn-primary {
                background: #18534f;
                color: #fff;
                padding: 12px 28px;
                border-radius: 8px;
                font-size: 14.5px;
                font-weight: 600;
                text-decoration: none;
                border: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: background 0.2s;
                cursor: pointer;
            }

            .bd-btn-primary:hover {
                background: #0f3d3a;
                color: #fff;
                text-decoration: none;
            }

            .bd-btn-outline {
                background: transparent;
                color: #4b5563;
                padding: 12px 28px;
                border-radius: 8px;
                font-size: 14.5px;
                font-weight: 600;
                text-decoration: none;
                border: 1px solid #d1d5db;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s;
                cursor: pointer;
            }

            .bd-btn-outline:hover {
                background: #f3f4f6;
                color: #111827;
                text-decoration: none;
            }
            .bd-btn-pending {
                background: #fef3c7;
                color: #92400e;
                padding: 12px 28px;
                border-radius: 8px;
                font-size: 14.5px;
                font-weight: 600;
                text-decoration: none;
                border: 1px solid #fde68a;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                cursor: not-allowed;
            }

            .alert-container {
                margin-top: 20px;
            }
            .bd-alert {
                padding: 12px 16px;
                border-radius: 8px;
                font-size: 13.5px;
                margin-bottom: 20px;
            }
            .bd-alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
            .bd-alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <div class="content-wrapper" style="background:#f7f8f8 !important; padding:0 !important;">

            <!-- Page header -->
            <div class="bd-page-header">
                <a href="javascript:history.back()" class="bd-back-btn"><i class="fa fa-arrow-left"></i></a>
                <h1 class="bd-page-title">Book Details</h1>
            </div>

            <div class="bd-container">
                <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != ""): ?>
                    <div class="bd-alert bd-alert-success"><i class="fa fa-check-circle"></i> <?php echo htmlentities($_SESSION['msg']); ?></div>
                    <?php $_SESSION['msg'] = ""; ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['error']) && $_SESSION['error'] != ""): ?>
                    <div class="bd-alert bd-alert-error"><i class="fa fa-exclamation-circle"></i> <?php echo htmlentities($_SESSION['error']); ?></div>
                    <?php $_SESSION['error'] = ""; ?>
                <?php endif; ?>

                <!-- Main Content -->
                <div class="bd-card">
                    <div class="bd-image-section">
                        <img src="<?php echo $img; ?>" alt="<?php echo htmlentities($result->BookName); ?>"
                            class="bd-book-cover">
                    </div>
                    <div class="bd-info-section">
                        <div>
                            <span
                                class="bd-category-tag"><?php echo htmlentities($result->CategoryName ?: 'Uncategorized'); ?></span>
                            <h2 class="bd-book-title"><?php echo htmlentities($result->BookName); ?></h2>
                            <div class="bd-author-name"><i class="fa fa-user"></i>
                                <?php echo htmlentities($result->AuthorName ?: 'Unknown Author'); ?></div>
                        </div>

                        <div class="bd-divider"></div>

                        <div class="bd-meta-grid">
                            <div class="bd-meta-item">
                                <span class="bd-meta-label">ISBN Number</span>
                                <span class="bd-meta-val"
                                    style="font-family: monospace; font-size: 16px;"><?php echo htmlentities($result->ISBNNumber); ?></span>
                            </div>
                            <div class="bd-meta-item">
                                <span class="bd-meta-label">Total Quantity</span>
                                <span class="bd-meta-val"><?php echo htmlentities($result->bookQty); ?> books</span>
                            </div>
                            <div class="bd-meta-item">
                                <span class="bd-meta-label">Book Price</span>
                                <span class="bd-meta-val price"><?php echo htmlentities($result->BookPrice); ?></span>
                            </div>
                            <div class="bd-meta-item">
                                <span class="bd-meta-label">Availability Status</span>
                                <?php if ($available > 0): ?>
                                    <span class="avail-status avail-yes"><span class="avail-dot"></span>
                                        <?php echo $available; ?> Available</span>
                                <?php else: ?>
                                    <span class="avail-status avail-no"><span class="avail-dot"></span> Currently
                                        Unavailable</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="bd-actions">
                            <?php if ($req_status === '0' || $req_status === 0): ?>
                                <span class="bd-btn-pending"><i class="fa fa-clock-o"></i> Request Pending</span>
                            <?php elseif ($available > 0): ?>
                                <form method="post" style="margin:0;">
                                    <input type="hidden" name="bookid" value="<?php echo htmlentities($result->bookid); ?>">
                                    <button type="submit" name="request_borrow" class="bd-btn-primary"><i class="fa fa-book"></i> Request to Borrow</button>
                                </form>
                            <?php else: ?>
                                <button class="bd-btn-primary" style="opacity: 0.6; cursor: not-allowed;" disabled><i class="fa fa-book"></i> Request to Borrow</button>
                            <?php endif; ?>
                            
                            <a href="listed-books.php" class="bd-btn-outline">Browse More Books</a>
                        </div>
                    </div>
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
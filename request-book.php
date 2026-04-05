<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['request_book'])) {
        $studentId = $_SESSION['stdid'];
        $bookTitle = trim($_POST['booktitle']);
        $authorName = trim($_POST['authorname']);

        $sql = "INSERT INTO tblbookrequests (StudentId, BookTitle, AuthorName, Status) VALUES (:studentId, :bookTitle, :authorName, 0)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentId', $studentId, PDO::PARAM_STR);
        $query->bindParam(':bookTitle', $bookTitle, PDO::PARAM_STR);
        $query->bindParam(':authorName', $authorName, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $_SESSION['msg'] = "Book request submitted successfully.";
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
        }
        header('location:request-book.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LibraryMS — Request a Book</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background: #f7f8f8; margin: 0; }
    
    .rb-page-header {
        background: #fff;
        border-bottom: 1px solid #e8e8e8;
        padding: 28px 48px;
    }

    .rb-page-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 3px;
        letter-spacing: -0.4px;
    }

    .rb-page-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    .rb-container {
        padding: 40px 48px;
        max-width: 1000px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    @media(min-width: 900px) {
        .rb-container {
            grid-template-columns: 350px 1fr;
        }
    }

    .rb-sidebar {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        padding: 24px;
        height: fit-content;
    }

    .rb-sidebar-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 12px;
    }

    .rb-sidebar-text {
        font-size: 13.5px;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .rb-sidebar-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .rb-sidebar-list li {
        display: flex;
        gap: 10px;
        font-size: 13px;
        color: #374151;
        margin-bottom: 12px;
        align-items: flex-start;
    }

    .rb-sidebar-list li i {
        color: #18534f;
        margin-top: 3px;
    }

    .rb-main-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 12px;
        overflow: hidden;
    }

    .rb-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e8e8e8;
        background: #f9fafb;
    }

    .rb-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .rb-card-body {
        padding: 24px;
    }

    .rb-form-group {
        margin-bottom: 20px;
    }

    .rb-form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .rb-form-control {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 10px 14px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #111827;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .rb-form-control:focus {
        border-color: #18534f;
        outline: none;
        box-shadow: 0 0 0 3px rgba(24, 83, 79, 0.1);
    }

    .rb-btn-submit {
        background: #18534f;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .rb-btn-submit:hover {
        background: #0f3d3a;
    }

    .rb-alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        margin-bottom: 20px;
    }

    .rb-alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .rb-alert-error { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* Existing Requests Section */
    .req-list-section {
        margin-top: 40px;
    }
    .req-list-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
    }

    .req-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .req-table th {
        background: #f9fafb;
        font-size: 12px;
        text-transform: uppercase;
        color: #6b7280;
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        border-bottom: 1px solid #e8e8e8;
    }

    .req-table td {
        padding: 14px 16px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
    }

    .req-table tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-0 { background: #fef3c7; color: #92400e; } /* Pending */
    .status-1 { background: #dcfce3; color: #166534; } /* Approved */
    .status-2 { background: #fee2e2; color: #991b1b; } /* Rejected */

    </style>
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="content-wrapper" style="background:#f7f8f8 !important; padding:0 !important;">
    <div class="rb-page-header">
        <h1>Request a Book</h1>
        <p>Can't find a book in our catalogue? Let us know what you need.</p>
    </div>

    <div class="rb-container">
        <div class="rb-sidebar">
            <h3 class="rb-sidebar-title">How it works</h3>
            <p class="rb-sidebar-text">We are constantly expanding our collection based on student recommendations. If a book is relevant to your course or studies, request it here.</p>
            <ul class="rb-sidebar-list">
                <li><i class="fa fa-check-circle"></i> Provide the exact title and author.</li>
                <li><i class="fa fa-check-circle"></i> Library admin will review your request.</li>
                <li><i class="fa fa-check-circle"></i> You can check the approval status on this page.</li>
            </ul>
        </div>

        <div class="rb-main-card-wrap">
            <div class="rb-main-card">
                <div class="rb-card-header">
                    <h2 class="rb-card-title">Book Request Form</h2>
                </div>
                <div class="rb-card-body">
                    <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != ""): ?>
                        <div class="rb-alert rb-alert-success"><i class="fa fa-check-circle"></i> <?php echo htmlentities($_SESSION['msg']); ?></div>
                        <?php $_SESSION['msg'] = ""; ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error']) && $_SESSION['error'] != ""): ?>
                        <div class="rb-alert rb-alert-error"><i class="fa fa-exclamation-circle"></i> <?php echo htmlentities($_SESSION['error']); ?></div>
                        <?php $_SESSION['error'] = ""; ?>
                    <?php endif; ?>

                    <form name="requestBook" method="post">
                        <div class="rb-form-group">
                            <label class="rb-form-label">Book Title</label>
                            <input type="text" name="booktitle" class="rb-form-control" autocomplete="off" required placeholder="Enter the exact title of the book" />
                        </div>
                        <div class="rb-form-group">
                            <label class="rb-form-label">Author Name</label>
                            <input type="text" name="authorname" class="rb-form-control" autocomplete="off" required placeholder="Enter the author's name" />
                        </div>
                        <button type="submit" name="request_book" class="rb-btn-submit"><i class="fa fa-paper-plane"></i> Submit Request</button>
                    </form>
                </div>
            </div>

            <div class="req-list-section">
                <h3 class="req-list-title">My Recent Requests</h3>
                <?php
                $sid = $_SESSION['stdid'];
                $req_sql = "SELECT * FROM tblbookrequests WHERE StudentId = :sid ORDER BY id DESC LIMIT 10";
                $req_q = $dbh->prepare($req_sql);
                $req_q->bindParam(':sid', $sid, PDO::PARAM_STR);
                $req_q->execute();
                $requests = $req_q->fetchAll(PDO::FETCH_OBJ);

                if ($req_q->rowCount() > 0) {
                ?>
                <table class="req-table">
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($requests as $req): ?>
                        <tr>
                            <td><strong><?php echo htmlentities($req->BookTitle); ?></strong></td>
                            <td><?php echo htmlentities($req->AuthorName); ?></td>
                            <td><?php echo date('d M Y', strtotime($req->RequestDate)); ?></td>
                            <td>
                                <?php if ($req->Status == 0): ?>
                                    <span class="status-badge status-0">Pending</span>
                                <?php elseif ($req->Status == 1): ?>
                                    <span class="status-badge status-1">Approved</span>
                                <?php else: ?>
                                    <span class="status-badge status-2">Rejected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php } else { ?>
                    <p style="color: #6b7280; font-size: 14px;">You haven't submitted any book requests yet.</p>
                <?php } ?>
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

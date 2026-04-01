<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{ 

if(isset($_POST['return']))
{
    $rid=intval($_GET['rid']);
    $fine=$_POST['fine'];
    $rstatus=1;
    $bookid=$_POST['bookid'];

    // Execute both updates separately for PDO compatibility
    $sql1="UPDATE tblissuedbookdetails SET fine=:fine, RetrunStatus=:rstatus, ReturnDate=NOW() WHERE id=:rid";
    $query1 = $dbh->prepare($sql1);
    $query1->bindParam(':rid',$rid,PDO::PARAM_STR);
    $query1->bindParam(':fine',$fine,PDO::PARAM_STR);
    $rstatus_val = 1;
    $query1->bindParam(':rstatus',$rstatus_val,PDO::PARAM_INT);
    $query1->execute();

    $sql2="UPDATE tblbooks SET isIssued=0 WHERE id=:bookid";
    $query2 = $dbh->prepare($sql2);
    $query2->bindParam(':bookid',$bookid,PDO::PARAM_STR);
    $query2->execute();

    $_SESSION['msg']="Book Returned successfully";
    header('location:manage-issued-books.php');
    exit;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Issued Book Details</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>

    <style>
        .dash-container {
            padding: 30px;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            min-height: calc(100vh - 150px);
        }
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .dash-title h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }
        .dash-title p {
            margin: 8px 0 0 0;
            color: #3b82f6;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .back-btn {
            background: #fff;
            border: 1px solid #cbd5e1;
            padding: 10px 20px;
            border-radius: 8px;
            color: #475569;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .back-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
            text-decoration: none;
        }

        /* Detail Cards */
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }
        .detail-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }
        .detail-card__title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .detail-card__title i {
            color: #3b82f6;
        }
        .detail-row {
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }
        .detail-row:last-child { margin-bottom: 0; }
        .detail-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
        }
        .detail-value.muted { color: #64748b; font-weight: 500; }

        /* Book card with image */
        .book-detail-card {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .book-detail-card img {
            width: 90px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .book-detail-card__info {
            flex: 1;
        }

        /* Fine / Return Action Card */
        .action-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }
        .action-card__title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 20px 0;
            padding-bottom: 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Fine alert badge */
        .fine-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .fine-alert.danger {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .fine-alert.success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .fine-alert.warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .fine-alert i { font-size: 18px; }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group label span { color: #dc2626; margin-left: 3px; }
        .form-control {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 12px 15px;
            height: auto;
            font-size: 15px;
            color: #0f172a;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            transition: all 0.2s;
            width: 100%;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .btn-return {
            background: #16a34a;
            color: #fff;
            padding: 13px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(22,163,74,0.2);
            transition: all 0.2s;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .btn-return:hover {
            background: #15803d;
            transform: translateY(-1px);
            box-shadow: 0 6px 8px -1px rgba(22,163,74,0.3);
        }

        .returned-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 15px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .status-pending { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .status-returned { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

        @media(max-width: 768px) {
            .detail-grid { grid-template-columns: 1fr; }
            .dash-container { padding: 15px; }
            .book-detail-card { flex-direction: column; }
        }
    </style>
</head>
<body>
    <!--MENU SECTION START-->
<?php include('includes/header.php');?>
<!--MENU SECTION END-->

<div class="dash-container">
    <!-- Header -->
    <div class="dash-header">
        <div class="dash-title">
            <h2>Issued Book Details</h2>
            <p>View & Manage Book Return</p>
        </div>
        <a href="manage-issued-books.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back to List</a>
    </div>

<?php 
$rid=intval($_GET['rid']);
$sql = "SELECT tblstudents.StudentId, tblstudents.FullName, tblstudents.EmailId, tblstudents.MobileNumber,
               tblbooks.BookName, tblbooks.ISBNNumber, tblbooks.id as bid, tblbooks.bookImage,
               tblissuedbookdetails.IssuesDate, tblissuedbookdetails.DueDate,
               tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id as rid,
               tblissuedbookdetails.fine, tblissuedbookdetails.RetrunStatus
        FROM tblissuedbookdetails
        JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId
        JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId
        WHERE tblissuedbookdetails.id=:rid";
$query = $dbh->prepare($sql);
$query->bindParam(':rid',$rid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
foreach($results as $result)
{
    // --- Auto-fine calculation ---
    $auto_fine = 0;
    $days_overdue = 0;
    $due_date_display = '—';
    $is_returned = ($result->RetrunStatus == 1);

    if (!empty($result->DueDate)) {
        $due_date_display = date('d M Y', strtotime($result->DueDate));
        if (!$is_returned) {
            $today = new DateTime();
            $due   = new DateTime($result->DueDate);
            if ($today > $due) {
                $diff = $today->diff($due);
                $days_overdue = $diff->days;
                $auto_fine = $days_overdue * 50;
            }
        }
    } elseif (!$is_returned) {
        // Fallback: use 14 days from issue date as due
        if (!empty($result->IssuesDate)) {
            $due_fallback = new DateTime($result->IssuesDate);
            $due_fallback->modify('+14 days');
            $today = new DateTime();
            if ($today > $due_fallback) {
                $diff = $today->diff($due_fallback);
                $days_overdue = $diff->days;
                $auto_fine = $days_overdue * 50;
            }
        }
    }

    // If already has a stored fine, use that for display
    $stored_fine = (!empty($result->fine) && $result->fine > 0) ? $result->fine : 0;
    $prefill_fine = $is_returned ? $stored_fine : ($auto_fine > 0 ? $auto_fine : $stored_fine);

    $issue_date_fmt = !empty($result->IssuesDate) ? date('d M Y, H:i', strtotime($result->IssuesDate)) : '—';
    $return_date_fmt = !empty($result->ReturnDate) ? date('d M Y, H:i', strtotime($result->ReturnDate)) : null;
?>

    <form role="form" method="post" id="returnForm">
        <input type="hidden" name="bookid" value="<?php echo htmlentities($result->bid); ?>">

        <div class="detail-grid">
            <!-- Student Info Card -->
            <div class="detail-card">
                <div class="detail-card__title">
                    <i class="fa fa-user"></i> Student Information
                </div>
                <div class="detail-row">
                    <span class="detail-label">Student ID</span>
                    <span class="detail-value"><?php echo htmlentities($result->StudentId); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Full Name</span>
                    <span class="detail-value"><?php echo htmlentities($result->FullName); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email Address</span>
                    <span class="detail-value muted"><?php echo htmlentities($result->EmailId); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mobile Number</span>
                    <span class="detail-value muted"><?php echo htmlentities($result->MobileNumber); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <?php if ($is_returned): ?>
                            <span class="status-badge status-returned"><i class="fa fa-check-circle"></i> Returned</span>
                        <?php else: ?>
                            <span class="status-badge status-pending"><i class="fa fa-clock-o"></i> Pending Return</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>

            <!-- Book Info Card -->
            <div class="detail-card">
                <div class="detail-card__title">
                    <i class="fa fa-book"></i> Book Information
                </div>
                <div class="book-detail-card">
                    <img src="bookimg/<?php echo htmlentities($result->bookImage); ?>" alt="<?php echo htmlentities($result->BookName); ?>">
                    <div class="book-detail-card__info">
                        <div class="detail-row">
                            <span class="detail-label">Book Name</span>
                            <span class="detail-value"><?php echo htmlentities($result->BookName); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">ISBN Number</span>
                            <span class="detail-value muted" style="font-family:monospace;"><?php echo htmlentities($result->ISBNNumber); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Issued Date</span>
                            <span class="detail-value muted"><?php echo $issue_date_fmt; ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Due Date</span>
                            <span class="detail-value" style="color: <?php echo ($days_overdue > 0 ? '#dc2626' : '#16a34a'); ?>;">
                                <?php echo $due_date_display; ?>
                            </span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Return Date</span>
                            <span class="detail-value <?php echo $return_date_fmt ? '' : 'muted'; ?>">
                                <?php echo $return_date_fmt ? $return_date_fmt : 'Not Returned Yet'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Card -->
        <div class="action-card">
            <h3 class="action-card__title"><i class="fa fa-undo" style="color:#3b82f6; margin-right:8px;"></i>Return & Fine Management</h3>

            <?php if (!$is_returned): ?>
                <?php if ($days_overdue > 0): ?>
                    <div class="fine-alert danger">
                        <i class="fa fa-exclamation-circle"></i>
                        <div>
                            <strong><?php echo $days_overdue; ?> day<?php echo $days_overdue > 1 ? 's' : ''; ?> overdue</strong> — 
                            Auto-calculated fine: <strong>₹<?php echo number_format($auto_fine, 2); ?></strong> (₹50 per day)
                        </div>
                    </div>
                <?php else: ?>
                    <div class="fine-alert success">
                        <i class="fa fa-check-circle"></i>
                        <div>Book is within due date. <strong>No automatic fine.</strong></div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="form-group">
                <label>Fine Amount (₹ INR)<span>*</span></label>
                <?php if ($is_returned): ?>
                    <div class="returned-badge">
                        <i class="fa fa-check-circle"></i>
                        Book Returned — Fine Collected: ₹<?php echo number_format($stored_fine, 2); ?>
                    </div>
                <?php else: ?>
                    <input class="form-control" type="number" name="fine" id="fine" 
                           value="<?php echo htmlentities($prefill_fine); ?>" 
                           min="0" step="0.01"
                           placeholder="Enter fine amount in ₹" required />
                    <small style="color:#64748b; font-size:12px; margin-top:5px; display:block;">
                        <i class="fa fa-info-circle"></i> Auto-filled based on overdue days (₹50/day). You can edit this amount.
                    </small>
                <?php endif; ?>
            </div>

            <?php if (!$is_returned): ?>
                <button type="submit" name="return" id="submitBtn" class="btn-return" 
                        onclick="return confirm('Confirm book return and collect fine of ₹' + document.getElementById('fine').value + '?');">
                    <i class="fa fa-check-circle"></i> Confirm Book Return
                </button>
            <?php else: ?>
                <div style="text-align:center; margin-top:10px;">
                    <a href="manage-issued-books.php" class="back-btn" style="justify-content:center;">
                        <i class="fa fa-arrow-left"></i> Back to Issued Books List
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </form>

<?php }} ?>

</div>

<?php include('includes/footer.php');?>
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>

</body>
</html>
<?php } ?>

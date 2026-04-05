<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LibraryMS — My Borrow Requests</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f7f8f8; margin: 0; }
        
        .page-header-wrap {
            background: #fff;
            border-bottom: 1px solid #e8e8e8;
            padding: 28px 48px;
        }

        .page-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 4px;
            letter-spacing: -0.4px;
        }

        .page-desc {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }

        .main-container {
            padding: 40px 48px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .data-card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 12px;
            overflow: hidden;
        }

        .data-card-body {
            padding: 24px;
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

    <div class="content-wrapper" style="background:#f7f8f8 !important; padding:0 !important; margin-top: 60px !important;">
        <div class="page-header-wrap">
            <h1 class="page-title">My Borrow Requests</h1>
            <p class="page-desc">Track all the books you've requested to borrow.</p>
        </div>

        <div class="main-container">
            <div class="data-card">
                <div class="data-card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book Name</th>
                                    <th>ISBN Number</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sid = $_SESSION['stdid'];
                                $sql = "SELECT tblbooks.BookName, tblbooks.ISBNNumber, tblborrowrequests.RequestDate, tblborrowrequests.Status FROM tblborrowrequests JOIN tblbooks ON tblborrowrequests.BookId=tblbooks.id WHERE tblborrowrequests.StudentId=:sid ORDER BY tblborrowrequests.id DESC";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $result) { ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td><?php echo htmlentities($result->BookName); ?></td>
                                            <td><?php echo htmlentities($result->ISBNNumber); ?></td>
                                            <td><?php echo htmlentities($result->RequestDate); ?></td>
                                            <td>
                                                <?php if($result->Status==0) { ?>
                                                    <span class="status-badge status-0">Pending</span>
                                                <?php } else if($result->Status==1) { ?>
                                                    <span class="status-badge status-1">Approved & Issued</span>
                                                <?php } else { ?>
                                                    <span class="status-badge status-2">Rejected</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php $cnt=$cnt+1; } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
</body>
</html>
<?php } ?>

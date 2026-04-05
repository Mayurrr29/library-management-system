<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
} else {
    // Approve Request
    if (isset($_GET['approveid'])) {
        $id = intval($_GET['approveid']);
        $sql = "UPDATE tblbookrequests SET Status=1 WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $_SESSION['msg'] = "Book request approved successfully";
        header('location:manage-requests.php');
        exit();
    }
    
    // Reject Request
    if (isset($_GET['rejectid'])) {
        $id = intval($_GET['rejectid']);
        $sql = "UPDATE tblbookrequests SET Status=2 WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $_SESSION['msg'] = "Book request rejected";
        header('location:manage-requests.php');
        exit();
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Manage Book Requests | Admin</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    
    <style>
        .page-header-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #2b4162;
            padding-bottom: 10px;
            display: inline-block;
        }

        .panel-custom {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .panel-custom .panel-heading {
            background: #2b4162;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            border-bottom: none;
        }

        .table-custom {
            margin-bottom: 0;
        }

        .table-custom th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            border-bottom: 2px solid #e9ecef !important;
            padding: 12px 15px !important;
        }

        .table-custom td {
            vertical-align: middle !important;
            color: #555;
            font-size: 14px;
            padding: 12px 15px !important;
            border-top: 1px solid #f1f3f5 !important;
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

        .btn-action {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 4px;
            margin-right: 5px;
            color: #fff;
            text-decoration: none;
            display: inline-block;
        }
        .btn-action.approve { background: #16a34a; }
        .btn-action.approve:hover { background: #15803d; color: #fff; text-decoration: none; }
        .btn-action.reject { background: #dc2626; }
        .btn-action.reject:hover { background: #b91c1c; color: #fff; text-decoration: none; }
        
        .alert-custom {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <!-- CONTENT-WRAPPER SECTION START-->
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-header-title">Manage Book Requests</h4>
                </div>
            </div>
            
            <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                <div class="alert alert-success alert-custom">
                    <i class="fa fa-check-circle"></i>
                    <?php echo htmlentities($_SESSION['msg']); ?>
                    <?php $_SESSION['msg']=""; ?>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            Book Requests Listing
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-custom table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student ID</th>
                                            <th>Book Title</th>
                                            <th>Author Name</th>
                                            <th>Date of Request</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT tblbookrequests.id, tblbookrequests.StudentId, tblbookrequests.BookTitle, tblbookrequests.AuthorName, tblbookrequests.Status, tblbookrequests.RequestDate, tblstudents.FullName FROM tblbookrequests LEFT JOIN tblstudents ON tblstudents.StudentId = tblbookrequests.StudentId ORDER BY tblbookrequests.id DESC";
                                        $query = $dbh -> prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0)
                                        {
                                            foreach($results as $result)
                                            { ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt);?></td>
                                            <td><?php echo htmlentities($result->StudentId);?> (<?php echo htmlentities($result->FullName);?>)</td>
                                            <td><strong><?php echo htmlentities($result->BookTitle);?></strong></td>
                                            <td><?php echo htmlentities($result->AuthorName);?></td>
                                            <td><?php echo htmlentities($result->RequestDate);?></td>
                                            <td>
                                                <?php if($result->Status == 0): ?>
                                                    <span class="status-badge status-0">Pending</span>
                                                <?php elseif($result->Status == 1): ?>
                                                    <span class="status-badge status-1">Approved</span>
                                                <?php else: ?>
                                                    <span class="status-badge status-2">Rejected</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($result->Status == 0): ?>
                                                    <a href="manage-requests.php?approveid=<?php echo htmlentities($result->id);?>" class="btn-action approve" onclick="return confirm('Are you sure you want to approve this request?');"><i class="fa fa-check"></i> Approve</a>
                                                    <a href="manage-requests.php?rejectid=<?php echo htmlentities($result->id);?>" class="btn-action reject" onclick="return confirm('Are you sure you want to reject this request?');"><i class="fa fa-times"></i> Reject</a>
                                                <?php else: ?>
                                                    <span style="color:#999; font-size:12px;">Processed</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php $cnt=$cnt+1;}} else { ?>
                                            <tr>
                                                <td colspan="7" class="text-center" style="padding: 30px!important; color:#888;">No book requests found.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- CORE JQUERY VISUAL -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
<?php } ?>

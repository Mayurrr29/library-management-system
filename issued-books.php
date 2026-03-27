<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else { 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | My Library</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
        .page-header-custom {
            background-color: #e5e0d8;
            padding: 40px 50px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            margin-bottom: 40px;
        }
        .page-header-custom h2 {
            font-weight: 800;
            color: #1a1a1a;
            margin: 0;
            font-size: 32px;
        }
        .container-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .table-custom {
            width: 100%;
        }
        .table-custom th {
            font-weight: 600;
            color: #888;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .table-custom td {
            padding: 20px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f9f9f9;
            color: #444;
            font-weight: 500;
            font-size: 14px;
        }
        .table-custom tr:hover td {
            background-color: #fafbfc;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .status-not-returned {
            background-color: #fff0f0;
            color: #e53935;
        }
        .status-returned {
            background-color: #f0fdf4;
            color: #16a34a;
        }
        .book-title-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .book-icon {
            width: 40px;
            height: 40px;
            background: #f4f6f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 18px;
        }
    </style>
</head>
<body>
<?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="page-header-custom">
            <h2>My Library</h2>
        </div>
        
        <div class="container-fluid">
            <div class="container-box">
                <div class="table-responsive">
                    <table class="table table-custom" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Book Name</th>
                                <th>ISBN</th>
                                <th>Issued Date</th>
                                <th>Return Date</th>
                                <th>Fine (USD)</th>
                            </tr>
                        </thead>
                        <tbody>
<?php 
$sid=$_SESSION['stdid'];
// Added tblbooks.bookImage to the query just in case it's there
$sql="SELECT tblbooks.BookName,tblbooks.ISBNNumber,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnDate,tblissuedbookdetails.id as rid,tblissuedbookdetails.fine from  tblissuedbookdetails join tblstudents on tblstudents.StudentId=tblissuedbookdetails.StudentId join tblbooks on tblbooks.id=tblissuedbookdetails.BookId where tblstudents.StudentId=:sid order by tblissuedbookdetails.id desc";
$query = $dbh -> prepare($sql);
$query-> bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0) {
foreach($results as $result) {               ?>                                      
                            <tr>
                                <td><?php echo htmlentities($cnt);?></td>
                                <td>
                                    <div class="book-title-cell">
                                        <div class="book-icon"><i class="fa fa-book"></i></div>
                                        <div style="font-weight: 700; color: #1a1a1a;"><?php echo htmlentities($result->BookName);?></div>
                                    </div>
                                </td>
                                <td><?php echo htmlentities($result->ISBNNumber);?></td>
                                <td><?php echo htmlentities($result->IssuesDate);?></td>
                                <td><?php if($result->ReturnDate=="") {?>
                                    <span class="status-badge status-not-returned">Not Returned Yet</span>
                                <?php } else { ?>
                                    <span class="status-badge status-returned"><?php echo htmlentities($result->ReturnDate); ?></span>
                                <?php } ?></td>
                                <td><?php echo htmlentities($result->fine);?></td>
                            </tr>
<?php $cnt=$cnt+1;}} ?>                                      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include('includes/footer.php');?>
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

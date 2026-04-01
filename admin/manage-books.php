<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_GET['del']))
{
$id=$_GET['del'];
$sql = "delete from tblbooks  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
$_SESSION['delmsg']="Book deleted successfully ";
header('location:manage-books.php');

}


    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Manage Books</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- DATATABLE STYLE  -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>

    <style>
        /* Modern Dashboard Specific Styles */
        .dash-container {
            padding: 20px 30px;
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
        .dash-search {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-btn {
            background: #2563eb;
            border: 1px solid #2563eb;
            padding: 10px 20px;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .filter-btn:hover {
            background: #1d4ed8;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }

        /* Table Box */
        .table-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }
        
        .overdue-table-wrapper {
            overflow-x: auto;
        }
        
        /* Table Customizations for DataTable */
        table.dataTable {
            width: 100% !important;
            margin-top: 15px !important;
            margin-bottom: 15px !important;
        }
        table.dataTable thead th, table.dataTable thead td {
            text-align: left;
            padding: 15px !important;
            background-color: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0 !important;
            border-top: none !important;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0;
        }
        table.dataTable tbody th, table.dataTable tbody td {
            padding: 15px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: #334155;
            font-size: 14px;
            font-weight: 500;
            vertical-align: middle;
            border-top: none !important;
        }
        table.table-bordered.dataTable {
            border-collapse: collapse !important;
            border: none;
        }
        
        /* DataTables Wrapper overrides */
        .dataTables_wrapper .row {
            margin: 0;
            align-items: center;
        }
        .dataTables_length label, .dataTables_filter label {
            font-family: 'Inter', sans-serif;
            color: #475569;
            font-weight: 600;
            font-size: 14px;
        }
        .dataTables_filter input {
            padding: 8px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            margin-left: 10px;
            font-weight: 400;
            transition: all 0.2s;
        }
        .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .dataTables_length select {
            padding: 6px 30px 6px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            margin: 0 8px;
            font-weight: 500;
            color: #334155;
        }
        
        .action-btns {
            display: flex;
            gap: 10px;
        }
        
        .btn-modern {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }
        .btn-edit:hover {
            background-color: #dbedf;
            color: #1d4ed8;
            border-color: #93c5fd;
            text-decoration: none;
        }
        .btn-delete {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .btn-delete:hover {
            background-color: #fee2e2;
            color: #b91c1c;
            border-color: #fca5a5;
            text-decoration: none;
        }
        
        .book-title-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .book-title-cell img {
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }
        .book-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .book-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 15px;
            line-height: 1.3;
        }
        
        /* Pagination Modernization */
        .pagination > li > a, .pagination > li > span {
            color: #475569;
            border-radius: 6px;
            margin: 0 3px;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            font-weight: 500;
        }
        .pagination > .active > a, .pagination > .active > span, 
        .pagination > .active > a:hover, .pagination > .active > span:hover, 
        .pagination > .active > a:focus, .pagination > .active > span:focus {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }
        .pagination > li > a:hover, .pagination > li > span:hover, 
        .pagination > li > a:focus, .pagination > li > span:focus {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        div.dataTables_info {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        @media(max-width: 768px) {
            .dash-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .dash-search { width: 100%; justify-content: space-between; }
            .dash-container { padding: 15px; }
            .table-box { padding: 15px; }
            .book-title-cell { flex-direction: column; align-items: flex-start; }
        }
    </style>

</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->

    <div class="dash-container">
        <!-- Header -->
        <div class="dash-header">
            <div class="dash-title">
                <h2>Manage Books</h2>
                <p>View, Edit and Delete Books</p>
            </div>
            <div class="dash-search">
                <a href="add-book.php" class="filter-btn"><i class="fa fa-plus"></i> Add New Book</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="table-box">
                    <div class="table-header">
                        <h3>Books List</h3>
                    </div>
                    <div class="overdue-table-wrapper">
                        <?php if($_SESSION['delmsg']!="") {?>
                            <div class="alert alert-success" style="border-radius: 8px; border: none; background-color: #dcfce7; color: #166534; font-weight: 500;">
                                <i class="fa fa-check-circle"></i> <?php echo htmlentities($_SESSION['delmsg']);?>
                                <?php echo htmlentities($_SESSION['delmsg']="");?>
                            </div>
                        <?php } ?>
                        <table class="table" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 28%;">Book Name</th>
                                    <th style="width: 13%;">Category</th>
                                    <th style="width: 13%;">Author</th>
                                    <th style="width: 10%;">ISBN</th>
                                    <th style="width: 9%;">Price</th>
                                    <th style="width: 8%;">Qty</th>
                                    <th style="width: 9%;">Available</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php $sql = "SELECT tblbooks.BookName, tblcategory.CategoryName, tblauthors.AuthorName,
               tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id as bookid,
               tblbooks.bookImage, tblbooks.bookQty,
               COUNT(CASE WHEN (tblissuedbookdetails.RetrunStatus IS NULL OR tblissuedbookdetails.RetrunStatus = '') 
                          THEN tblissuedbookdetails.id END) AS issuedCount
        FROM tblbooks
        JOIN tblcategory ON tblcategory.id = tblbooks.CatId
        JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId
        LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
        GROUP BY tblbooks.id";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                                <tr class="odd gradeX">
                                    <td class="center"><?php echo htmlentities($cnt);?></td>
                                    <td class="center">
                                        <div class="book-title-cell">
                                            <img src="bookimg/<?php echo htmlentities($result->bookImage);?>" width="60" alt="<?php echo htmlentities($result->BookName);?>">
                                            <div class="book-info">
                                                <span class="book-name"><?php echo htmlentities($result->BookName);?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="center"><?php echo htmlentities($result->CategoryName);?></td>
                                    <td class="center"><?php echo htmlentities($result->AuthorName);?></td>
                                    <td class="center"><?php echo htmlentities($result->ISBNNumber);?></td>
                                    <td class="center"><strong>₹<?php echo number_format($result->BookPrice, 2);?></strong></td>
                                    <td class="center"><?php echo htmlentities($result->bookQty ?? 0);?></td>
                                    <?php
                                    $available = max(0, (int)($result->bookQty ?? 0) - (int)$result->issuedCount);
                                    $availColor = $available > 0 ? '#16a34a' : '#dc2626';
                                    $availBg = $available > 0 ? '#f0fdf4' : '#fef2f2';
                                    ?>
                                    <td class="center">
                                        <span style="background:<?php echo $availBg;?>; color:<?php echo $availColor;?>; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:700;">
                                            <?php echo $available; ?>
                                        </span>
                                    </td>
                                    <td class="center">
                                        <div class="action-btns">
                                            <a href="edit-book.php?bookid=<?php echo htmlentities($result->bookid);?>" class="btn-modern btn-edit">
                                                <i class="fa fa-edit"></i> Edit
                                            </a> 
                                            <a href="manage-books.php?del=<?php echo htmlentities($result->bookid);?>" onclick="return confirm('Are you sure you want to delete this book?');" class="btn-modern btn-delete">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
 <?php $cnt=$cnt+1;}} ?>                                      
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    </div>

     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- DATATABLE SCRIPTS  -->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>

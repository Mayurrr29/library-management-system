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
    <title>Online Library Management System | Discover Books</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
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
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding-bottom: 50px;
        }
        .book-card-item {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .book-card-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .book-img-wrapper {
            width: 100%;
            height: 350px;
            background: #f8f8f8;
            overflow: hidden;
        }
        .book-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .book-info {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .book-category {
            font-size: 12px;
            font-weight: 700;
            color: #ff6a4a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .book-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        .book-author {
            font-size: 14px;
            color: #888;
            margin-bottom: 15px;
            font-weight: 500;
        }
        .book-stats {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f0f0f0;
            padding-top: 15px;
        }
        .stat-item {
            font-size: 13px;
            color: #555;
            font-weight: 600;
        }
        .stat-item span {
            color: #1a1a1a;
        }
        .availability {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }
        .avail-yes { background: #f0fdf4; color: #16a34a; }
        .avail-no { background: #fff0f0; color: #e53935; }
    </style>
</head>
<body>
<?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="page-header-custom">
            <h2>All Categories & Books</h2>
        </div>
        
        <div class="container-fluid">
            <div class="books-grid">
<?php $sql = "SELECT tblbooks.BookName,tblcategory.CategoryName,tblauthors.AuthorName,tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.id as bookid,tblbooks.bookImage,tblbooks.isIssued,tblbooks.bookQty,  
               COUNT(tblissuedbookdetails.id) AS issuedBooks,
               COUNT(tblissuedbookdetails.RetrunStatus) AS returnedbook
        FROM tblbooks
        LEFT JOIN tblissuedbookdetails ON tblissuedbookdetails.BookId = tblbooks.id
        LEFT JOIN tblauthors ON tblauthors.id = tblbooks.AuthorId
        Left join tblcategory on tblcategory.id=tblbooks.CatId
        GROUP BY tblbooks.id";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0) {
foreach($results as $result) {               
    
    $available = 0;
    if ($result->issuedBooks == 0) {
        $available = $result->bookQty;
    } else {
        $available = $result->bookQty - ($result->issuedBooks - $result->returnedbook);
    }
    
    $img = "admin/bookimg/" . htmlentities($result->bookImage);
    if(empty($result->bookImage) || !file_exists($img)) {
        $img = "https://via.placeholder.com/300x450/EEE/333?text=".urlencode($result->BookName);
    }
?>  
                <div class="book-card-item">
                    <div class="book-img-wrapper">
                        <img src="<?php echo $img; ?>" alt="<?php echo htmlentities($result->BookName); ?>">
                    </div>
                    <div class="book-info">
                        <div class="book-category"><?php echo htmlentities($result->CategoryName);?></div>
                        <div class="book-title"><?php echo htmlentities($result->BookName);?></div>
                        <div class="book-author">by <?php echo htmlentities($result->AuthorName);?></div>
                        
                        <div class="book-stats">
                            <div class="stat-item">ISBN: <span><?php echo htmlentities($result->ISBNNumber);?></span></div>
                            <?php if($available > 0) { ?>
                                <div class="availability avail-yes"><?php echo htmlentities($available);?> Available</div>
                            <?php } else { ?>
                                <div class="availability avail-no">Out of Stock</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
<?php }} ?>  
            </div>
        </div>
    </div>

<?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>

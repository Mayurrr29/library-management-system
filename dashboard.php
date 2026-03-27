<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {



    
header('location:index.php');
}
else { ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Discover</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
        .dashboard-main {
            position: relative;
            background-color: #fff;
        }
        .beige-bg {
            background-color: #e5e0d8;
            border-bottom-left-radius: 60px;
            padding: 40px 50px 180px 50px;
            position: relative;
        }
        .top-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 20px;
        }
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .user-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .page-title {
            font-size: 42px;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0 0 30px 0;
        }
        .search-container {
            display: flex;
            background: #fff;
            border-radius: 12px;
            padding: 8px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            align-items: center;
            margin-bottom: 50px;
        }
        .search-category {
            padding: 10px 20px;
            border-right: 1px solid #eee;
            font-weight: 500;
            color: #555;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
        }
        .search-input-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        .search-input-wrapper i {
            color: #aaa;
            margin-right: 10px;
        }
        .search-input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 15px;
            color: #333;
        }
        .search-input::placeholder {
            color: #aaa;
        }
        .search-btn {
            background-color: #1c3c3a;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-btn:hover {
            background-color: #112624;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #555;
            margin: 0;
        }
        .view-all {
            background: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            text-decoration: none;
        }
        
        /* Book Carousel */
        .book-carousel {
            display: flex;
            gap: 30px;
            margin-top: -120px; 
            padding: 0 50px 30px 50px;
            overflow-x: auto;
            position: relative;
        }
        .book-card-main {
            flex: 0 0 220px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            transition: transform 0.3s;
            cursor: pointer;
            background: #fff;
            height: 320px;
        }
        .book-card-main:hover {
            transform: translateY(-10px);
        }
        .book-card-main img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        /* Categories */
        .categories-section {
            padding: 20px 50px 50px 50px;
        }
        .cat-list {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 20px;
        }
        .cat-item {
            flex: 0 0 160px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        .cat-item-img {
            width: 110px;
            height: 150px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            object-fit: cover;
            position: relative;
            z-index: 2;
        }
        .cat-bg {
            background: #f8f8f8;
            border-radius: 12px;
            width: 100%;
            height: 110px;
            margin-top: -60px;
            z-index: 1;
        }
        .cat-name {
            font-weight: 700;
            font-size: 15px;
            color: #333;
            text-align: center;
            margin-top: -10px;
            padding: 0 10px;
        }
        
        .book-carousel::-webkit-scrollbar, .cat-list::-webkit-scrollbar {
            height: 6px;
        }
        .book-carousel::-webkit-scrollbar-thumb, .cat-list::-webkit-scrollbar-thumb {
            background-color: #ddd;
            border-radius: 10px;
        }
        
        .filter-btn {
            background: #fff;
            border: 1px solid #eee;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #555;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .filter-btn:after {
            content: " \f1de";
            font-family: FontAwesome;
        }
        
    </style>
</head>
<body>
<?php include('includes/header.php'); ?>
    <div class="content-wrapper" style="background:#fff !important; padding:0 !important;">
        <div class="dashboard-main">
            <!-- Top Beige Area -->
            <div class="beige-bg">
                
                
                <h1 class="page-title">Discover</h1>
                
                <div class="search-container">
                    <div class="search-category">
                        All Categories <i class="fa fa-angle-down" style="margin-left:5px;"></i>
                    </div>
                    <div class="search-input-wrapper">
                        <i class="fa fa-search"></i>
                        <input type="text" class="search-input" placeholder="find the book you like....">
                    </div>
                    <button class="search-btn">Search</button>
                </div>
                
                <div class="section-header">
                    <h2 class="section-title">Book Recomendation</h2>
                    <a href="listed-books.php" class="view-all">View all <i class="fa fa-angle-right" style="margin-left:5px;"></i></a>
                </div>
            </div>
            
            <!-- Books Carousel -->
            <div class="book-carousel">
                <?php
    $sql = "SELECT tblbooks.BookName, tblbooks.bookImage, tblbooks.id from tblbooks ORDER BY id DESC LIMIT 5";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $img = "admin/bookimg/" . htmlentities($result->bookImage);
            if (empty($result->bookImage) || !file_exists($img)) {
                $img = "https://via.placeholder.com/300x450/EEE/333?text=" . urlencode($result->BookName);
            }
?>
                <a href="listed-books.php" style="text-decoration:none;">
                <div class="book-card-main">
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlentities($result->BookName); ?>">
                </div>
                </a>
                <?php
        }
    }
    else { ?>
                    <!-- Mock images to match design if DB is empty -->
                    <div class="book-card-main"><img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=300&q=80"></div>
                    <div class="book-card-main"><img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=300&q=80"></div>
                    <div class="book-card-main"><img src="https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=300&q=80"></div>
                    <div class="book-card-main"><img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=300&q=80"></div>
                <?php
    }?>
            </div>
            
            <!-- Book Categories row -->
            <div class="categories-section">
                <div class="section-header" style="margin-bottom: 10px;">
                    <h2 class="section-title">Book Category</h2>
                    <div class="filter-btn"></div>
                </div>
                
                <div class="cat-list">
                    <?php
    $sql = "SELECT CategoryName, id from tblcategory LIMIT 5";
    $query = $dbh->prepare($sql);
    $query->execute();
    $cats = $query->fetchAll(PDO::FETCH_OBJ);
    $mockColors = ['#f4e5e5', '#e5eef4', '#e5f4ea', '#f4efe5', '#eee5f4'];
    $i = 0;
    if ($query->rowCount() > 0) {
        foreach ($cats as $cat) {
            $bg = $mockColors[$i % 5];
            $i++;

            $sqlb = "SELECT bookImage, BookName FROM tblbooks WHERE CatId=:catid LIMIT 1";
            $qb = $dbh->prepare($sqlb);
            $qb->bindParam(':catid', $cat->id, PDO::PARAM_INT);
            $qb->execute();
            $b = $qb->fetch(PDO::FETCH_OBJ);

            $catImg = "https://via.placeholder.com/150x220/333/FFF?text=" . urlencode($cat->CategoryName);
            if ($b && !empty($b->bookImage)) {
                $catImg = "admin/bookimg/" . $b->bookImage;
            }
?>
                    <div class="cat-item">
                        <img src="<?php echo $catImg; ?>" class="cat-item-img">
                        <div class="cat-bg" style="background: <?php echo $bg; ?>;"></div>
                        <div class="cat-name"><?php echo htmlentities($cat->CategoryName); ?></div>
                    </div>
                    <?php
        }
    }
    else { ?>
                        <div class="cat-item">
                            <img src="https://via.placeholder.com/150x220/333/FFF?text=Money" class="cat-item-img">
                            <div class="cat-bg" style="background: #f8f8f8;"></div>
                            <div class="cat-name">Money/Investing</div>
                        </div>
                        <div class="cat-item">
                            <img src="https://via.placeholder.com/150x220/333/FFF?text=Design" class="cat-item-img">
                            <div class="cat-bg" style="background: #f8f8f8;"></div>
                            <div class="cat-name">Design</div>
                        </div>
                        <div class="cat-item">
                            <img src="https://via.placeholder.com/150x220/333/FFF?text=Business" class="cat-item-img">
                            <div class="cat-bg" style="background: #f8f8f8;"></div>
                            <div class="cat-name">Business</div>
                        </div>
                        <div class="cat-item">
                            <img src="https://via.placeholder.com/150x220/333/FFF?text=Self+Imp" class="cat-item-img">
                            <div class="cat-bg" style="background: #f8f8f8;"></div>
                            <div class="cat-name">Self Improvment</div>
                        </div>
                    <?php
    }?>
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
<?php
}?>

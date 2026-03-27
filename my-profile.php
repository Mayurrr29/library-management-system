<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['update'])) {    
        $sid=$_SESSION['stdid'];  
        $fname=$_POST['fullanme'];
        $mobileno=$_POST['mobileno'];

        $sql="update tblstudents set FullName=:fname,MobileNumber=:mobileno where StudentId=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid',$sid,PDO::PARAM_STR);
        $query->bindParam(':fname',$fname,PDO::PARAM_STR);
        $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
        $query->execute();

        echo '<script>alert("Your profile has been updated")</script>';
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Profile</title>
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .page-header-custom h2 {
            font-weight: 800;
            color: #1a1a1a;
            margin: 0;
            font-size: 32px;
        }
        .form-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 40px;
            max-width: 700px;
            margin: 0 auto;
        }
        .form-card label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
        }
        .form-card .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            height: auto;
            border: 1px solid #eaeaea;
            box-shadow: none;
            background: #f9f9f9;
            margin-bottom: 20px;
        }
        .form-card .form-control:focus {
            border-color: #1c3c3a;
            background: #fff;
        }
        .info-badge {
            display: inline-block;
            background: #f4f6f9;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
            margin-right: 10px;
        }
        .info-badge span {
            color: #1c3c3a;
        }
        .btn-custom {
            background-color: #1c3c3a;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: #112624;
            color: #fff;
        }
        .avatar-wrap {
            text-align: center;
            margin-bottom: 30px;
        }
        .avatar-wrap img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="page-header-custom">
            <h2>Settings & Profile</h2>
        </div>
        
        <div class="container-fluid">
            <div class="form-card">
                <?php 
                $sid=$_SESSION['stdid'];
                $sql="SELECT StudentId,FullName,EmailId,MobileNumber,RegDate,UpdationDate,Status from tblstudents where StudentId=:sid ";
                $query = $dbh -> prepare($sql);
                $query-> bindParam(':sid', $sid, PDO::PARAM_STR);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                if($query->rowCount() > 0) {
                    foreach($results as $result) { ?>  
                <div class="avatar-wrap">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($result->FullName);?>&background=2b4162&color=fff&size=128" alt="Profile">
                </div>
                
                <div style="margin-bottom: 30px; text-align: center;">
                    <div class="info-badge">Student ID: <span><?php echo htmlentities($result->StudentId);?></span></div>
                    <div class="info-badge">Registered: <span><?php echo htmlentities($result->RegDate);?></span></div>
                    <div class="info-badge">Status: 
                        <?php if($result->Status==1){?>
                            <span style="color: #28a745;">Active</span>
                        <?php } else { ?>
                            <span style="color: #dc3545;">Blocked</span>
                        <?php }?>
                    </div>
                </div>

                <form name="signup" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input class="form-control" type="text" name="fullanme" value="<?php echo htmlentities($result->FullName);?>" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile Number</label>
                                <input class="form-control" type="text" name="mobileno" maxlength="10" value="<?php echo htmlentities($result->MobileNumber);?>" autocomplete="off" required />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input class="form-control" type="email" name="email" id="emailid" value="<?php echo htmlentities($result->EmailId);?>" autocomplete="off" required readonly />
                            </div>
                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        <button type="submit" name="update" class="btn btn-custom px-5">Update Profile</button>
                    </div>
                </form>
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

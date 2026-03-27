<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_POST['change'])) {
        $password=md5($_POST['password']);
        $newpassword=md5($_POST['newpassword']);
        $email=$_SESSION['login'];
        $sql ="SELECT Password FROM tblstudents WHERE EmailId=:email and Password=:password";
        $query= $dbh -> prepare($sql);
        $query-> bindParam(':email', $email, PDO::PARAM_STR);
        $query-> bindParam(':password', $password, PDO::PARAM_STR);
        $query-> execute();
        $results = $query -> fetchAll(PDO::FETCH_OBJ);
        if($query -> rowCount() > 0) {
            $con="update tblstudents set Password=:newpassword where EmailId=:email";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
            $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            $msg="Your Password succesfully changed";
        } else {
            $error="Your current password is wrong";  
        }
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Password</title>
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
        .form-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 40px;
            max-width: 600px;
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
        .btn-custom {
            background-color: #1c3c3a;
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color: #112624;
            color: #fff;
        }
        .alert-custom {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
    <script type="text/javascript">
    function valid() {
        if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value) {
            alert("New Password and Confirm Password Field do not match  !!");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
<?php include('includes/header.php');?>
    <div class="content-wrapper">
        <div class="page-header-custom">
            <h2>Change Password</h2>
        </div>
        
        <div class="container-fluid">
            <?php if($error){?>
                <div class="alert alert-danger alert-custom"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
            <?php } else if($msg){?>
                <div class="alert alert-success alert-custom"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
            <?php }?>
            
            <div class="form-card">
                <form role="form" method="post" onSubmit="return valid();" name="chngpwd">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input class="form-control" type="password" name="password" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label>Enter New Password</label>
                        <input class="form-control" type="password" name="newpassword" autocomplete="off" required />
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required />
                    </div>
                    <button type="submit" name="change" class="btn btn-custom">Change Password</button> 
                </form>
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

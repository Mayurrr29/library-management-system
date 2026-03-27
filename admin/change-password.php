<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_POST['change']))
  {
$password=md5($_POST['password']);
$newpassword=md5($_POST['newpassword']);
$username=$_SESSION['alogin'];
  $sql ="SELECT Password FROM admin where UserName=:username and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':username', $username, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query -> rowCount() > 0)
{
$con="update admin set Password=:newpassword where UserName=:username";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':username', $username, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();
$msg="Your Password successfully changed!";
}
else {
$error="Your current password is wrong";  
}
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Change Password</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- INTER FONT -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>

<script type="text/javascript">
function valid()
{
if(document.chngpwd.newpassword.value!= document.chngpwd.confirmpassword.value)
{
alert("New Password and Confirm Password Field do not match!");
document.chngpwd.confirmpassword.focus();
return false;
}
return true;
}
</script>

  <style>
/* Modern Dashboard Form Styles */
  .dash-container {
      padding: 30px;
      font-family: 'Inter', sans-serif;
      color: #1e293b;
      background-color: #f8fafc;
      min-height: calc(100vh - 150px);
      display: flex;
      justify-content: center;
  }
  .form-box-wrapper {
      width: 100%;
      max-width: 500px;
  }
  .form-box {
      background: #fff;
      border-radius: 12px;
      padding: 35px;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
      border: 1px solid #f1f5f9;
      width: 100%;
  }
  .form-header {
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid #e2e8f0;
      text-align: center;
  }
  .form-header h3 {
      margin: 0;
      font-size: 22px;
      font-weight: 700;
      color: #1e293b;
  }
  .form-header p {
      margin: 5px 0 0 0;
      color: #64748b;
      font-size: 14px;
      font-weight: 500;
  }
  
  .form-group {
      margin-bottom: 20px;
  }
  .form-group label {
      display: block;
      font-weight: 600;
      color: #334155;
      margin-bottom: 8px;
      font-size: 14px;
  }
  .form-control {
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      padding: 12px 15px;
      height: auto;
      font-size: 15px;
      color: #0f172a;
      box-shadow: 0 1px 2px rgba(0,0,0,0.02);
      transition: all 0.2s;
      width: 100%;
  }
  .form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      outline: none;
  }
  
  .btn-submit {
      background: #0f172a;
      color: #fff;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 15px;
      border: none;
      box-shadow: 0 4px 6px -1px rgba(15,23,42,0.2);
      transition: all 0.2s;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      margin-top: 10px;
  }
  .btn-submit:hover {
      background: #1e293b;
      transform: translateY(-1px);
      box-shadow: 0 6px 8px -1px rgba(15,23,42,0.3);
  }

  /* Alert Styling overrides */
  .alert {
      border-radius: 8px;
      border: none;
      font-weight: 500;
      margin-bottom: 20px;
  }
  .alert-success { background-color: #dcfce7; color: #166534; }
  .alert-danger { background-color: #fee2e2; color: #b91c1c; }

</style>
</head>


<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->

    <div class="dash-container">
        
        <div class="form-box-wrapper">
            
            <?php if($error){?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
            <?php } else if($msg){?>
                <div class="alert alert-success"><i class="fa fa-check-circle"></i> <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
            <?php }?>  
            
            <div class="form-box">
                <div class="form-header">
                    <h3><i class="fa fa-lock" style="color:#64748b; margin-right:8px;"></i> Change Password</h3>
                    <p>Update your admin account credentials</p>
                </div>
                
                <form role="form" method="post" onSubmit="return valid();" name="chngpwd">

                    <div class="form-group">
                        <label>Current Password</label>
                        <input class="form-control" type="password" name="password" autocomplete="off" placeholder="Enter your current password" required  />
                    </div>

                    <div class="form-group">
                        <label>New Password</label>
                        <input class="form-control" type="password" name="newpassword" autocomplete="off" placeholder="Enter a new secure password" required  />
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input class="form-control" type="password" name="confirmpassword" autocomplete="off" placeholder="Confirm your new password" required  />
                    </div>

                    <button type="submit" name="change" class="btn-submit">Update Password <i class="fa fa-check"></i></button> 
                </form>
            </div>
        </div>

    </div>

     <!-- CONTENT-WRAPPER SECTION END-->
 <?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>

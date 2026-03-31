<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['signup']))
{
 
//Code for student ID
$count_my_page = ("studentid.txt");
$hits = file($count_my_page);
$hits[0] ++;
$fp = fopen($count_my_page , "w");
fputs($fp , "$hits[0]");
fclose($fp); 
$StudentId= $hits[0];   
$fname=$_POST['fullanme'];
$mobileno=$_POST['mobileno'];
$email=$_POST['email']; 
$password=md5($_POST['password']); 
$status=1;
$sql="INSERT INTO  tblstudents(StudentId,FullName,MobileNumber,EmailId,Password,Status) VALUES(:StudentId,:fname,:mobileno,:email,:password,:status)";
$query = $dbh->prepare($sql);
$query->bindParam(':StudentId',$StudentId,PDO::PARAM_STR);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':password',$password,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
echo '<script>alert("Your Registration successfull and your student id is  "+"'.$StudentId.'")</script>';
}
else 
{
echo "<script>alert('Something went wrong. Please try again');</script>";
}
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Library Management System | Create Account</title>
    <link href="assets/css/auth-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script type="text/javascript">
    function valid()
    {
    if(document.signup.password.value!= document.signup.confirmpassword.value)
    {
    alert("Password and Confirm Password Field do not match  !!");
    document.signup.confirmpassword.focus();
    return false;
    }
    return true;
    }
    </script>
    <script>
    function checkAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
    url: "check_availability.php",
    data:'emailid='+$("#emailid").val(),
    type: "POST",
    success:function(data){
    $("#user-availability-status").html(data);
    $("#loaderIcon").hide();
    },
    error:function (){}
    });
    }
    </script>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left" style="background: url('assets/img/libraryImg.jpg') no-repeat center center/cover;">
            <div class="auth-left-logo">
                LibraryMS
            </div>
            <div class="auth-left-content">
                <h1>Find your next great read</h1>
                <p>Register to unlock our complete catalog<br>and manage your borrowing seamlessly</p>
                <div class="auth-dots">
                    <span></span>
                    <span class="active"></span>
                    <span></span>
                </div>
            </div>
        </div>
        
        <div class="auth-right">
            <div class="auth-top-right">
                <a href="index.php" class="btn-top">Sign In</a>
            </div>
            
            <div class="auth-form-container">
                <h2>Create Account</h2>
                <p>Register as a new student</p>
                
                <form name="signup" method="post" onSubmit="return valid();">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input class="form-control" type="text" name="fullanme" autocomplete="off" required placeholder="John Doe" />
                    </div>
                    
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input class="form-control" type="text" name="mobileno" maxlength="10" autocomplete="off" required placeholder="1234567890" />
                    </div>
                                                
                    <div class="form-group">
                        <label>Email Address</label>
                        <input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()" autocomplete="off" required placeholder="example@gmail.com" />
                        <span id="user-availability-status" style="font-size:12px; margin-top:5px; display:block;"></span> 
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" type="password" name="password" autocomplete="off" required placeholder="********" />
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input class="form-control" type="password" name="confirmpassword" autocomplete="off" required placeholder="********" />
                    </div>
                    
                    <button type="submit" name="signup" class="btn-primary" id="submit">Register Now</button>
                    
                    <div class="auth-footer">
                        Already have an account? <a href="index.php">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

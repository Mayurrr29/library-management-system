<?php
session_start();
error_reporting(0);
include('includes/config.php');

$msg = '';
$error = '';

if (isset($_POST['change'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);
    $sql = "SELECT EmailId FROM tblstudents WHERE EmailId=:email and MobileNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();
    if ($query->rowCount() > 0) {
        $con = "UPDATE tblstudents SET Password=:newpassword WHERE EmailId=:email AND MobileNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        $msg = 'Password changed successfully. You can now log in.';
    } else {
        $error = 'Email address or mobile number is incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LibraryMS — Password Recovery</title>
    <link href="assets/css/auth-style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
    function valid() {
        if (document.chngpwd.newpassword.value !== document.chngpwd.confirmpassword.value) {
            alert("New passwords do not match.");
            document.chngpwd.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>
    <style>
    .alert-box {
        padding: 12px 14px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 16px;
    }
    .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .form-group { margin-bottom: 1.1rem; }
    .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left" style="background: url('assets/img/libraryImg.jpg') no-repeat center center/cover;">
            <div class="auth-left-logo">LibraryMS</div>
            <div class="auth-left-content">
                <h1>Reset your password</h1>
                <p>Enter your registered email and mobile number to reset your password securely.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-top-right">
                <a href="index.php" class="btn-top">Back to Sign In</a>
            </div>

            <div class="auth-form-container">
                <h2>Password Recovery</h2>
                <p>Enter your account details to set a new password</p>

                <?php if ($msg): ?>
                    <div class="alert-box alert-success"><?php echo htmlentities($msg); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert-box alert-error"><?php echo htmlentities($error); ?></div>
                <?php endif; ?>

                <form role="form" name="chngpwd" method="post" onsubmit="return valid();">
                    <div class="form-group">
                        <label>Registered Email</label>
                        <input class="form-control" type="email" name="email" required autocomplete="off" placeholder="example@gmail.com" />
                    </div>
                    <div class="form-group">
                        <label>Registered Mobile Number</label>
                        <input class="form-control" type="text" name="mobile" required autocomplete="off" placeholder="10-digit mobile number" />
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input class="form-control" type="password" name="newpassword" required autocomplete="off" placeholder="••••••••" />
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input class="form-control" type="password" name="confirmpassword" required autocomplete="off" placeholder="••••••••" />
                    </div>

                    <button type="submit" name="change" class="btn-primary">Reset Password</button>

                    <div class="auth-footer">
                        Remember your password? <a href="index.php">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
session_start();
include('includes/config.php');
error_reporting(0);
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    $msg = '';
    $error = '';

    if (isset($_POST['change'])) {
        $password    = md5($_POST['password']);
        $newpassword = md5($_POST['newpassword']);
        $email       = $_SESSION['login'];

        $sql = "SELECT Password FROM tblstudents WHERE EmailId=:email AND Password=:password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $con = "UPDATE tblstudents SET Password=:newpassword WHERE EmailId=:email";
            $chng = $dbh->prepare($con);
            $chng->bindParam(':email', $email, PDO::PARAM_STR);
            $chng->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chng->execute();
            $msg = 'Password changed successfully!';
        } else {
            $error = 'Current password is incorrect.';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LibraryMS — Change Password</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background: #f7f8f8; margin: 0; }

    /* ── Page header ── */
    .cp-page-header {
        background: #fff;
        border-bottom: 1px solid #e8e8e8;
        padding: 28px 48px;
    }

    .cp-page-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 3px;
        letter-spacing: -0.4px;
    }

    .cp-page-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* ── Body ── */
    .cp-body {
        padding: 28px 48px 56px;
        max-width: 520px;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        overflow: hidden;
    }

    .form-card__head {
        padding: 16px 24px;
        border-bottom: 1px solid #f3f4f6;
    }

    .form-card__head-title { font-size: 14px; font-weight: 700; color: #111827; margin: 0 0 2px; }
    .form-card__head-sub   { font-size: 12.5px; color: #6b7280; }

    .form-card__body { padding: 24px; }

    .form-group-custom { margin-bottom: 18px; }

    .form-group-custom label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-wrap {
        position: relative;
    }

    .toggle-pw {
        position: absolute;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 13px;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        transition: color 0.15s;
    }

    .toggle-pw:hover { color: #18534f; }

    .form-group-custom input {
        width: 100%;
        border: 1px solid #e4e4e4;
        border-radius: 7px;
        padding: 10px 40px 10px 12px;
        font-family: 'Inter', sans-serif;
        font-size: 13.5px;
        color: #111827;
        background: #f9f9f9;
        outline: none;
        transition: all 0.18s;
    }

    .form-group-custom input:focus {
        border-color: #18534f;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(24, 83, 79, 0.08);
    }

    /* Strength meter */
    .strength-wrap { margin-top: 7px; }

    .strength-bar {
        height: 3px;
        background: #e8e8e8;
        border-radius: 2px;
        overflow: hidden;
    }

    .strength-fill {
        height: 100%;
        border-radius: 2px;
        width: 0;
        transition: width 0.3s, background 0.3s;
    }

    .strength-weak   { width: 33%; background: #ef4444; }
    .strength-medium { width: 66%; background: #f59e0b; }
    .strength-strong { width: 100%; background: #22c55e; }

    .strength-label {
        font-size: 11px;
        font-weight: 600;
        margin-top: 4px;
        color: #9ca3af;
    }

    .form-divider {
        height: 1px;
        background: #f3f4f6;
        margin: 18px 0;
    }

    .btn-save {
        background: #18534f;
        color: #fff;
        border: none;
        padding: 11px 0;
        width: 100%;
        border-radius: 7px;
        font-family: 'Inter', sans-serif;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        margin-top: 6px;
    }

    .btn-save:hover { background: #0f3d3a; }

    .alert-custom {
        padding: 12px 16px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 16px;
    }

    .alert-success-custom { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .alert-error-custom   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    .security-note {
        background: #f9f9f9;
        border: 1px solid #e8e8e8;
        border-radius: 7px;
        padding: 12px 14px;
        font-size: 12.5px;
        color: #4b5563;
        margin-top: 18px;
        line-height: 1.5;
    }
    </style>
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="content-wrapper" style="background:#f7f8f8 !important; padding:0 !important;">

    <div class="cp-page-header">
        <h1>Security</h1>
        <p>Change your account password</p>
    </div>

    <div class="cp-body">
        <div class="form-card">
            <div class="form-card__head">
                <div class="form-card__head-title">Change Password</div>
                <div class="form-card__head-sub">Use a strong password with at least 8 characters</div>
            </div>
            <div class="form-card__body">

                <?php if ($msg): ?>
                    <div class="alert-custom alert-success-custom"><?php echo htmlentities($msg); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert-custom alert-error-custom"><?php echo htmlentities($error); ?></div>
                <?php endif; ?>

                <form method="post" name="chngpwd" onsubmit="return validatePasswords()">
                    <div class="form-group-custom">
                        <label>Current Password</label>
                        <div class="input-wrap">
                            <input type="password" name="password" id="currentPwd" required autocomplete="off">
                            <button type="button" class="toggle-pw" onclick="togglePw('currentPwd', this)"><i class="fa fa-eye"></i></button>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-group-custom">
                        <label>New Password</label>
                        <div class="input-wrap">
                            <input type="password" name="newpassword" id="newPwd" required autocomplete="off" oninput="checkStrength(this.value)">
                            <button type="button" class="toggle-pw" onclick="togglePw('newPwd', this)"><i class="fa fa-eye"></i></button>
                        </div>
                        <div class="strength-wrap">
                            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                            <div class="strength-label" id="strengthLabel"></div>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label>Confirm New Password</label>
                        <div class="input-wrap">
                            <input type="password" name="confirmpassword" id="confirmPwd" required autocomplete="off">
                            <button type="button" class="toggle-pw" onclick="togglePw('confirmPwd', this)"><i class="fa fa-eye"></i></button>
                        </div>
                    </div>

                    <button type="submit" name="change" class="btn-save">Update Password</button>
                </form>

                <div class="security-note">
                    Use at least 8 characters with a mix of letters, numbers, and symbols for a strong password.
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
<script>
function togglePw(fieldId, btn) {
    var field = document.getElementById(fieldId);
    var icon  = btn.querySelector('i');
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fa fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fa fa-eye';
    }
}

function checkStrength(pw) {
    var fill  = document.getElementById('strengthFill');
    var label = document.getElementById('strengthLabel');
    fill.className = 'strength-fill';
    if (pw.length === 0) { label.textContent = ''; return; }
    var strong = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/;
    var medium = /^(?=.*[a-zA-Z])(?=.*\d).{6,}$/;
    if (strong.test(pw)) {
        fill.classList.add('strength-strong');
        label.textContent = 'Strong';
        label.style.color = '#22c55e';
    } else if (medium.test(pw)) {
        fill.classList.add('strength-medium');
        label.textContent = 'Medium — add symbols or uppercase';
        label.style.color = '#f59e0b';
    } else {
        fill.classList.add('strength-weak');
        label.textContent = 'Weak — use letters and numbers';
        label.style.color = '#ef4444';
    }
}

function validatePasswords() {
    var np = document.getElementById('newPwd').value;
    var cp = document.getElementById('confirmPwd').value;
    if (np !== cp) {
        alert('Passwords do not match.');
        document.getElementById('confirmPwd').focus();
        return false;
    }
    return true;
}
</script>
</body>
</html>
<?php } ?>

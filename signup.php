<?php 
session_start();
include('includes/config.php');
error_reporting(0);

$signup_msg   = '';
$signup_error = '';
$form_data    = [];

if(isset($_POST['signup']))
{
    $fname    = trim($_POST['fullanme']);
    $mobileno = trim($_POST['mobileno']);
    $email    = trim($_POST['email']); 
    $password = $_POST['password'];
    $confirm  = $_POST['confirmpassword'];
    $form_data = $_POST;

    // Server-side validation
    if (strlen($fname) < 3) {
        $signup_error = 'Full name must be at least 3 characters long.';
    } elseif (!preg_match('/^[0-9]{10}$/', $mobileno)) {
        $signup_error = 'Mobile number must be exactly 10 digits.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $signup_error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirm) {
        $signup_error = 'Password and Confirm Password do not match.';
    } else {
        // Check email uniqueness
        $chk = $dbh->prepare("SELECT id FROM tblstudents WHERE EmailId=:email");
        $chk->bindParam(':email', $email, PDO::PARAM_STR);
        $chk->execute();
        if ($chk->rowCount() > 0) {
            $signup_error = 'This email address is already registered. Please sign in.';
        } else {
            // Generate student ID
            $count_my_page = ("studentid.txt");
            $hits = file($count_my_page);
            $hits[0]++;
            $fp = fopen($count_my_page , "w");
            fputs($fp, "$hits[0]");
            fclose($fp); 
            $StudentId = $hits[0];

            $pwd_hash = md5($password);
            $status   = 1;
            $sql = "INSERT INTO tblstudents(StudentId,FullName,MobileNumber,EmailId,Password,Status) VALUES(:StudentId,:fname,:mobileno,:email,:password,:status)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':StudentId', $StudentId, PDO::PARAM_STR);
            $query->bindParam(':fname',     $fname,     PDO::PARAM_STR);
            $query->bindParam(':mobileno',  $mobileno,  PDO::PARAM_STR);
            $query->bindParam(':email',     $email,     PDO::PARAM_STR);
            $query->bindParam(':password',  $pwd_hash,  PDO::PARAM_STR);
            $query->bindParam(':status',    $status,    PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $signup_msg = "Registration successful! Your Student ID is <strong>" . htmlspecialchars($StudentId) . "</strong>. Please sign in.";
                $form_data  = []; // Clear form on success
            } else {
                $signup_error = 'Something went wrong. Please try again.';
            }
        }
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

    <style>
        .form-group { margin-bottom: 16px; }
        .field-error {
            font-size: 12px;
            color: #dc2626;
            margin-top: 4px;
            display: none;
            font-weight: 500;
        }
        .field-error.show { display: block; }
        .form-control.invalid {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1) !important;
        }
        .form-control.valid {
            border-color: #16a34a !important;
        }

        /* Alert banners */
        .alert-server {
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-server.error { background:#fef2f2; border:1px solid #fecaca; color:#b91c1c; }
        .alert-server.success { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }

        .pwd-toggle { position: relative; }
        .pwd-toggle input { padding-right: 42px !important; }
        .pwd-toggle .toggle-icon {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%); cursor: pointer;
            color: #94a3b8; font-size: 15px; transition: color 0.2s;
        }
        .pwd-toggle .toggle-icon:hover { color: #475569; }

        .email-status {
            font-size: 12px;
            margin-top: 4px;
            font-weight: 500;
            display: none;
        }
        .email-status.show { display: block; }
        .email-status.ok   { color: #16a34a; }
        .email-status.taken { color: #dc2626; }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-left" style="background: url('assets/img/libraryImg.jpg') no-repeat center center/cover;">
            <div class="auth-left-logo">LibraryMS</div>
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

                <?php if (!empty($signup_msg)): ?>
                    <div class="alert-server success">
                        <i class="fa fa-check-circle" style="margin-top:2px; font-size:16px;"></i>
                        <div><?php echo $signup_msg; ?> <a href="index.php" style="color:#166534; text-decoration:underline;">Click here to sign in</a></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($signup_error)): ?>
                    <div class="alert-server error">
                        <i class="fa fa-exclamation-circle" style="margin-top:2px; font-size:16px;"></i>
                        <?php echo htmlspecialchars($signup_error); ?>
                    </div>
                <?php endif; ?>
                
                <form name="signup" method="post" id="signupForm" novalidate>
                    <input type="hidden" name="signup" value="1" />
                    <div class="form-group">
                        <label>Full Name</label>
                        <input class="form-control" type="text" name="fullanme" id="fullname"
                               autocomplete="off" placeholder="Enter your full name"
                               value="<?php echo isset($form_data['fullanme']) ? htmlspecialchars($form_data['fullanme']) : ''; ?>" />
                        <span class="field-error" id="name_err">Full name must be at least 3 characters.</span>
                    </div>
                    
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input class="form-control" type="tel" name="mobileno" id="mobileno"
                               maxlength="10" autocomplete="off" placeholder="Enter your 10-digit mobile number"
                               value="<?php echo isset($form_data['mobileno']) ? htmlspecialchars($form_data['mobileno']) : ''; ?>" />
                        <span class="field-error" id="mobile_err">Please enter a valid 10-digit mobile number.</span>
                    </div>
                                        
                    <div class="form-group">
                        <label>Email Address</label>
                        <input class="form-control" type="email" name="email" id="emailid"
                               autocomplete="off" placeholder="Enter your email address"
                               value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" />
                        <span class="email-status" id="email_status"></span>
                        <span class="field-error" id="email_err">Please enter a valid email address.</span>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <div class="pwd-toggle">
                            <input class="form-control" type="password" name="password" id="password"
                                   autocomplete="off" placeholder="Create a password" />
                            <span class="toggle-icon" onclick="togglePwd('password', this)">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <span class="field-error" id="pwd_err">Password must be at least 8 characters.</span>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="pwd-toggle">
                            <input class="form-control" type="password" name="confirmpassword" id="confirmpassword"
                                   autocomplete="off" placeholder="Confirm your password" />
                            <span class="toggle-icon" onclick="togglePwd('confirmpassword', this)">
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        <span class="field-error" id="confirm_err">Passwords do not match.</span>
                    </div>
                    
                    <button type="submit" class="btn-primary" id="signupBtn">Register Now</button>
                    
                    <div class="auth-footer">
                        Already have an account? <a href="index.php">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function togglePwd(inputId, icon) {
        var input = document.getElementById(inputId);
        var i = icon.querySelector('i');
        input.type = (input.type === 'password') ? 'text' : 'password';
        i.className = (input.type === 'text') ? 'fa fa-eye-slash' : 'fa fa-eye';
    }

    function validateEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

    // Full name
    $('#fullname').on('blur input', function() {
        if ($(this).val().trim().length < 3) {
            $(this).addClass('invalid').removeClass('valid');
            $('#name_err').addClass('show');
        } else {
            $(this).addClass('valid').removeClass('invalid');
            $('#name_err').removeClass('show');
        }
    });

    // Mobile
    $('#mobileno').on('blur input', function() {
        if (!/^[0-9]{10}$/.test($(this).val().trim())) {
            $(this).addClass('invalid').removeClass('valid');
            $('#mobile_err').addClass('show');
        } else {
            $(this).addClass('valid').removeClass('invalid');
            $('#mobile_err').removeClass('show');
        }
    });

    // Email validation only (no AJAX check that can block submission)
    var emailTimer;
    $('#emailid').on('input', function() {
        var v = $(this).val().trim();
        clearTimeout(emailTimer);
        if (!validateEmail(v)) {
            $(this).addClass('invalid').removeClass('valid');
            $('#email_err').addClass('show');
            return;
        }
        $(this).addClass('valid').removeClass('invalid');
        $('#email_err').removeClass('show');
    });

    // Password validation
    $('#password').on('input', function() {
        var v = $(this).val();
        if (v.length < 8) {
            $(this).addClass('invalid').removeClass('valid');
            $('#pwd_err').addClass('show');
        } else {
            $(this).addClass('valid').removeClass('invalid');
            $('#pwd_err').removeClass('show');
        }
    });

    // Confirm password
    $('#confirmpassword').on('blur input', function() {
        if ($(this).val() !== $('#password').val() || !$(this).val()) {
            $(this).addClass('invalid').removeClass('valid');
            $('#confirm_err').addClass('show');
        } else {
            $(this).addClass('valid').removeClass('invalid');
            $('#confirm_err').removeClass('show');
        }
    });

    // Form submit validation
    $('#signupForm').on('submit', function(e) {
        var valid = true;
        var name    = $('#fullname').val().trim();
        var mobile  = $('#mobileno').val().trim();
        var email   = $('#emailid').val().trim();
        var pwd     = $('#password').val();
        var confirm = $('#confirmpassword').val();

        if (name.length < 3) { $('#fullname').addClass('invalid'); $('#name_err').addClass('show'); valid = false; }
        if (!/^[0-9]{10}$/.test(mobile)) { $('#mobileno').addClass('invalid'); $('#mobile_err').addClass('show'); valid = false; }
        if (!validateEmail(email)) { $('#emailid').addClass('invalid'); $('#email_err').addClass('show'); valid = false; }
        if (pwd.length < 8) { $('#password').addClass('invalid'); $('#pwd_err').addClass('show'); valid = false; }
        if (pwd !== confirm) { $('#confirmpassword').addClass('invalid'); $('#confirm_err').addClass('show'); valid = false; }

        if (!valid) { e.preventDefault(); return; }
        $('#signupBtn').prop('disabled', true).text('Creating account...');
    });
    </script>
</body>
</html>

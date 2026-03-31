<?php
session_start();
include('includes/config.php');
error_reporting(0);
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    $msg = '';
    $error = '';
    $sid = $_SESSION['stdid'];

    // ── Handle profile image upload via AJAX ──
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $upload_dir = 'assets/img/profiles/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        $file = $_FILES['profile_image'];
        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($file['type'], $allowed) && $file['size'] <= 5 * 1024 * 1024) {
            $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fname = 'user_' . $sid . '_' . time() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $upload_dir . $fname)) {
                $upd = $dbh->prepare("UPDATE tblstudents SET ProfileImage=:img WHERE StudentId=:sid");
                $upd->bindParam(':img', $fname, PDO::PARAM_STR);
                $upd->bindParam(':sid', $sid, PDO::PARAM_STR);
                $upd->execute();
                $msg = 'Profile photo updated!';
            }
        } else {
            $error = 'Invalid file. Use JPG/PNG/GIF/WebP under 5MB.';
        }
    }

    // ── Handle profile update ──
    if (isset($_POST['update'])) {
        $fname   = $_POST['fullanme'];
        $mobileno = $_POST['mobileno'];
        $sql = "UPDATE tblstudents SET FullName=:fname, MobileNumber=:mobileno WHERE StudentId=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->execute();
        $msg = 'Profile updated successfully!';
    }

    // ── Fetch user data ──
    $q = $dbh->prepare("SELECT StudentId, FullName, EmailId, MobileNumber, RegDate, Status, ProfileImage FROM tblstudents WHERE StudentId=:sid");
    $q->bindParam(':sid', $sid, PDO::PARAM_STR);
    $q->execute();
    $user = $q->fetch(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LibraryMS — Profile</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background: #f7f8f8; margin: 0; }

    /* ── Page header ── */
    .prof-page-header {
        background: #fff;
        border-bottom: 1px solid #e8e8e8;
        padding: 28px 48px;
    }

    .prof-page-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 3px;
        letter-spacing: -0.4px;
    }

    .prof-page-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* ── Two column body ── */
    .prof-body {
        padding: 28px 48px 56px;
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* ── Left: Identity card ── */
    .identity-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        overflow: hidden;
        position: sticky;
        top: 76px;
    }

    .identity-card__top {
        background: #18534f;
        height: 64px;
    }

    .identity-card__body {
        padding: 0 20px 20px;
        text-align: center;
    }

    .avatar-wrap {
        position: relative;
        display: inline-block;
        margin-top: -28px;
        margin-bottom: 12px;
    }

    .avatar-wrap__img {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid #fff;
        display: block;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }

    .avatar-wrap__initials {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #18534f;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 22px;
        border: 3px solid #fff;
        margin: 0 auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }

    .avatar-upload-btn {
        position: absolute;
        bottom: -4px;
        right: -4px;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #18534f;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #fff;
        font-size: 9px;
        transition: background 0.2s;
    }

    .avatar-upload-btn:hover { background: #0f3d3a; }

    #avatar-file-input { display: none; }

    .identity-card__name {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 2px;
        letter-spacing: -0.2px;
    }

    .identity-card__email {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 16px;
        word-break: break-all;
    }

    .upload-progress {
        margin-bottom: 12px;
        display: none;
    }

    .upload-progress-bar {
        height: 3px;
        background: #e8e8e8;
        border-radius: 2px;
        overflow: hidden;
    }

    .upload-progress-fill {
        height: 100%;
        background: #18534f;
        border-radius: 2px;
        width: 0;
        transition: width 1s ease;
    }

    .upload-msg {
        font-size: 11.5px;
        color: #18534f;
        margin-top: 5px;
        font-weight: 600;
    }

    .identity-card__meta {
        border-top: 1px solid #f3f4f6;
        padding-top: 14px;
        text-align: left;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        font-size: 12.5px;
        border-bottom: 1px solid #f9f9f9;
    }

    .meta-row:last-child { border-bottom: none; }

    .meta-row__label { color: #6b7280; font-weight: 500; }
    .meta-row__value { color: #111827; font-weight: 600; }

    .status-active   { color: #15803d; }
    .status-inactive { color: #b91c1c; }

    /* ── Right: Form card ── */
    .form-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        overflow: hidden;
    }

    .form-card__head {
        padding: 18px 24px;
        border-bottom: 1px solid #f3f4f6;
    }

    .form-card__head-title {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 2px;
    }

    .form-card__head-sub {
        font-size: 12.5px;
        color: #6b7280;
    }

    .form-card__body {
        padding: 24px;
    }

    .form-group-custom {
        margin-bottom: 18px;
    }

    .form-group-custom label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group-custom input {
        width: 100%;
        border: 1px solid #e4e4e4;
        border-radius: 7px;
        padding: 10px 12px;
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

    .form-group-custom input[readonly] {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .btn-save {
        background: #18534f;
        color: #fff;
        border: none;
        padding: 11px 28px;
        border-radius: 7px;
        font-family: 'Inter', sans-serif;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
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
    </style>
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="content-wrapper" style="background:#f7f8f8 !important; padding:0 !important;">

    <div class="prof-page-header">
        <h1>Profile Settings</h1>
        <p>Manage your account information and photo</p>
    </div>

    <div class="prof-body">

        <!-- Left: Identity Card -->
        <div class="identity-card">
            <div class="identity-card__top"></div>
            <div class="identity-card__body">
                <?php
                $profileImg = (!empty($user->ProfileImage) && file_exists('assets/img/profiles/' . $user->ProfileImage))
                              ? 'assets/img/profiles/' . $user->ProfileImage : '';
                $initials   = strtoupper(substr($user->FullName ?: 'U', 0, 1));
                ?>
                <div class="avatar-wrap" id="avatarWrap">
                    <?php if ($profileImg): ?>
                        <img src="<?php echo htmlentities($profileImg); ?>" class="avatar-wrap__img" id="avatarPreview" alt="Profile Photo">
                    <?php else: ?>
                        <div class="avatar-wrap__initials" id="avatarInitials"><?php echo $initials; ?></div>
                    <?php endif; ?>
                    <label class="avatar-upload-btn" for="avatar-file-input" title="Change photo">
                        <i class="fa fa-camera"></i>
                    </label>
                </div>

                <input type="file" id="avatar-file-input" accept="image/*" onchange="uploadAvatar(this)">

                <div class="upload-progress" id="uploadProgress">
                    <div class="upload-progress-bar"><div class="upload-progress-fill" id="progressFill"></div></div>
                    <div class="upload-msg" id="uploadMsg">Uploading…</div>
                </div>

                <div class="identity-card__name"><?php echo htmlentities($user->FullName ?: 'User'); ?></div>
                <div class="identity-card__email"><?php echo htmlentities($user->EmailId ?: ''); ?></div>

                <div class="identity-card__meta">
                    <div class="meta-row">
                        <span class="meta-row__label">Student ID</span>
                        <span class="meta-row__value"><?php echo htmlentities($user->StudentId); ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-row__label">Member since</span>
                        <span class="meta-row__value"><?php echo htmlentities($user->RegDate ?: 'N/A'); ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-row__label">Status</span>
                        <span class="meta-row__value <?php echo ($user->Status == 1) ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo ($user->Status == 1) ? 'Active' : 'Inactive'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Edit Form -->
        <div class="form-card">
            <div class="form-card__head">
                <div class="form-card__head-title">Personal Information</div>
                <div class="form-card__head-sub">Update your name and contact number</div>
            </div>
            <div class="form-card__body">
                <?php if ($msg): ?>
                    <div class="alert-custom alert-success-custom"><?php echo htmlentities($msg); ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert-custom alert-error-custom"><?php echo htmlentities($error); ?></div>
                <?php endif; ?>

                <form name="signup" method="post" enctype="multipart/form-data">
                    <div class="form-row-2">
                        <div class="form-group-custom">
                            <label>Full Name</label>
                            <input type="text" name="fullanme" value="<?php echo htmlentities($user->FullName ?: ''); ?>" required autocomplete="off">
                        </div>
                        <div class="form-group-custom">
                            <label>Mobile Number</label>
                            <input type="text" name="mobileno" maxlength="10" value="<?php echo htmlentities($user->MobileNumber ?? ''); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group-custom">
                        <label>Email Address <span style="color:#9ca3af; font-weight:400; text-transform:none; letter-spacing:0;">(cannot be changed)</span></label>
                        <input type="email" value="<?php echo htmlentities($user->EmailId ?: ''); ?>" readonly>
                    </div>
                    <div class="form-group-custom">
                        <label>Student ID <span style="color:#9ca3af; font-weight:400; text-transform:none; letter-spacing:0;">(read-only)</span></label>
                        <input type="text" value="<?php echo htmlentities($user->StudentId); ?>" readonly>
                    </div>
                    <button type="submit" name="update" class="btn-save">Save Changes</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
<script>
function uploadAvatar(input) {
    if (!input.files || !input.files[0]) return;
    var file = input.files[0];
    var allowedTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];
    if (!allowedTypes.includes(file.type)) { alert('Use JPG, PNG, GIF, or WebP.'); return; }
    if (file.size > 5 * 1024 * 1024) { alert('Max file size is 5MB.'); return; }

    var reader = new FileReader();
    reader.onload = function(e) {
        var initials = document.getElementById('avatarInitials');
        if (initials) initials.remove();
        var prev = document.getElementById('avatarPreview');
        if (!prev) {
            prev = document.createElement('img');
            prev.id = 'avatarPreview';
            prev.className = 'avatar-wrap__img';
            prev.alt = 'Profile Photo';
            document.getElementById('avatarWrap').insertBefore(prev, document.querySelector('.avatar-upload-btn'));
        }
        prev.src = e.target.result;
    };
    reader.readAsDataURL(file);

    var prog = document.getElementById('uploadProgress');
    var fill = document.getElementById('progressFill');
    var msg  = document.getElementById('uploadMsg');
    prog.style.display = 'block';
    fill.style.width = '0%';
    msg.textContent = 'Uploading…';
    setTimeout(function() { fill.style.width = '60%'; }, 100);

    var fd = new FormData();
    fd.append('profile_image', file);

    fetch('upload-profile-image.php', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        fill.style.width = '100%';
        if (data.success) {
            msg.textContent = 'Photo updated';
            msg.style.color = '#15803d';
        } else {
            msg.textContent = data.message || 'Upload failed';
            msg.style.color = '#b91c1c';
        }
        setTimeout(function() { prog.style.display = 'none'; }, 3000);
    })
    .catch(function() {
        msg.textContent = 'Upload failed';
        msg.style.color = '#b91c1c';
        setTimeout(function() { prog.style.display = 'none'; }, 3000);
    });
}
</script>
</body>
</html>
<?php } ?>

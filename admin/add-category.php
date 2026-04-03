<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{ 

if(isset($_POST['create']))
{
    $category=$_POST['category'];
    $status=$_POST['status'];
    $sql="INSERT INTO  tblcategory(CategoryName,Status) VALUES(:category,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':category',$category,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
        $success="Category Listed successfully";
    }
    else 
    {
        $error="Something went wrong. Please try again";
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Add Category</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- INTER FONT -->
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>

    <style>
        .dash-container {
            padding: 20px 30px;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            min-height: calc(100vh - 150px);
        }

        /* Page Header */
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .dash-title h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }
        .dash-title p {
            margin: 6px 0 0 0;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .back-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
            text-decoration: none;
        }

        /* Form Card */
        .form-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
        }
        .form-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 22px 28px;
            border-bottom: 1px solid #f1f5f9;
            background: linear-gradient(135deg, #f8fafc, #fff);
        }
        .form-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .form-card-title h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }
        .form-card-title p {
            margin: 3px 0 0 0;
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        .form-card-body {
            padding: 30px 28px;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 22px;
        }

        /* Form Group */
        .form-group-modern {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        .form-group-modern label {
            font-weight: 600;
            font-size: 13px;
            color: #374151;
            letter-spacing: 0.01em;
        }
        .form-group-modern label span.req {
            color: #ef4444;
            margin-left: 2px;
        }
        .form-control-modern {
            padding: 11px 15px;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            outline: none;
            transition: all 0.2s;
            width: 100%;
        }
        .form-control-modern:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }

        /* Radio Buttons */
        .radio-group-modern {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }
        .radio-modern {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #475569;
            font-weight: 500;
        }
        .radio-modern input[type="radio"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        /* Divider */
        .form-divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 28px 0;
        }

        /* Action Buttons */
        .form-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            justify-content: flex-end;
            padding-top: 8px;
        }
        .btn-submit-modern {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #fff;
            padding: 12px 28px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-submit-modern:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }
        .btn-cancel-modern {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            color: #475569;
            padding: 12px 24px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-cancel-modern:hover {
            background: #e2e8f0;
            color: #1e293b;
            text-decoration: none;
        }

        /* Alert Messages */
        .alert-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 600;
        }
        .alert-modern.error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .alert-modern.success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        @media(max-width: 768px) {
            .dash-container { padding: 15px; }
            .form-card-body { padding: 20px; }
            .form-card-header { padding: 18px 20px; }
            .dash-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .form-actions { flex-direction: column-reverse; }
            .btn-submit-modern, .btn-cancel-modern { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
<?php include('includes/header.php');?>

<div class="dash-container">
    <!-- Page Header -->
    <div class="dash-header">
        <div class="dash-title">
            <h2>Add New Category</h2>
            <p>Fill in the details below to add a new category to the library</p>
        </div>
        <a href="manage-categories.php" class="back-btn"><i class="fa fa-arrow-left"></i> Back to Categories</a>
    </div>

    <?php if(isset($error) && $error): ?>
        <div class="alert-modern error"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if(isset($success) && $success): ?>
        <div class="alert-modern success"><i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fa fa-list-alt"></i></div>
            <div class="form-card-title">
                <h3>Category Information</h3>
                <p>All fields marked with <span style="color:#ef4444;">*</span> are required</p>
            </div>
        </div>

        <div class="form-card-body">
            <form role="form" method="post">
                <div class="form-grid">
                    <!-- Category Name -->
                    <div class="form-group-modern">
                        <label>Category Name <span class="req">*</span></label>
                        <input class="form-control-modern" type="text" name="category" placeholder="e.g. Science Fiction" autocomplete="off" required />
                    </div>

                    <!-- Status -->
                    <div class="form-group-modern">
                        <label>Status <span class="req">*</span></label>
                        <div class="radio-group-modern">
                            <label class="radio-modern">
                                <input type="radio" name="status" id="status" value="1" checked="checked"> Active
                            </label>
                            <label class="radio-modern">
                                <input type="radio" name="status" id="status" value="0"> Inactive
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="form-divider">

                <div class="form-actions">
                    <a href="manage-categories.php" class="btn-cancel-modern"><i class="fa fa-times"></i> Cancel</a>
                    <button type="submit" name="create" class="btn-submit-modern">
                        <i class="fa fa-plus-circle"></i> Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>

<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) 
{
    header('location:index.php');
}
else {
    // Fetch statistics
    $sql = "SELECT id from tblbooks ";
    $query = $dbh->prepare($sql);
    $query->execute();
    $totalBooks = $query->rowCount();

    $sql2 = "SELECT id from tblissuedbookdetails where (RetrunStatus='' || RetrunStatus is null)";
    $query2 = $dbh->prepare($sql2);
    $query2->execute();
    $borrowedBooks = $query2->rowCount();

    $sql3 = "SELECT id from tblstudents ";
    $query3 = $dbh->prepare($sql3);
    $query3->execute();
    $totalMembers = $query3->rowCount();

    $sql4 = "SELECT id FROM tblissuedbookdetails WHERE (RetrunStatus='' || RetrunStatus is null) AND IssuesDate < DATE_SUB(NOW(), INTERVAL 7 DAY)";
    $query4 = $dbh->prepare($sql4);
    $query4->execute();
    $overdueBooks = $query4->rowCount();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Admin Dash Board</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
        /* Modern Dashboard Specific Styles */
        .dash-container {
            padding: 10px 15px;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
        }
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
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }
        .dash-title p {
            margin: 8px 0 0 0;
            color: #3b82f6;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .dash-search {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .search-input {
            position: relative;
        }
        .search-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .search-input input {
            padding: 10px 15px 10px 45px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            width: 320px;
            font-size: 14px;
            outline: none;
            color: #334155;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .filter-btn {
            background: #fff;
            border: 1px solid #cbd5e1;
            padding: 10px 15px;
            border-radius: 8px;
            color: #475569;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            font-size: 16px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-right: 20px;
        }
        .stat-icon.books { background-color: #e0f2fe; color: #0284c7; }
        .stat-icon.borrowed { background-color: #e0e7ff; color: #4f46e5; }
        .stat-icon.overdue { background-color: #fee2e2; color: #dc2626; }
        .stat-icon.members { background-color: #e0f2fe; color: #0369a1; }
        
        .stat-info { flex-grow: 1; text-align: right; }
        .stat-info span {
            display: block;
            color: #475569;
            font-size: 15px;
            font-weight: 700;
        }
        .stat-info h3 {
            margin: 5px 0 0 0;
            color: #1e293b;
            font-size: 28px;
            font-weight: 800;
        }
        .stat-info h3.danger { color: #dc2626; }
        .stat-info h3.primary { color: #0284c7; }

        /* Quick Actions Tabs */
        .quick-actions-section {
            margin-bottom: 25px;
        }
        .quick-actions-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }
        .tabs {
            display: flex;
            border-bottom: 2px solid #e2e8f0;
            gap: 30px;
            margin-bottom: 25px;
        }
        .tab {
            padding: 10px 0;
            color: #64748b;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            position: relative;
            text-decoration: none !important;
        }
        .tab a { color: inherit; text-decoration: none; }
        .tab.active {
            color: #2563eb;
        }
        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #2563eb;
            border-radius: 3px 3px 0 0;
        }

        /* Table Box */
        .table-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }
        .table-header a {
            color: #2563eb;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .overdue-table-wrapper {
            overflow-x: auto;
        }
        .overdue-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        .overdue-table th {
            text-align: left;
            padding: 12px 15px;
            background-color: #f8fafc;
            color: #475569;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .overdue-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            font-weight: 500;
        }
        
        .badge-danger-soft {
            background-color: #fee2e2;
            color: #dc2626;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .action-btns {
            display: flex;
            gap: 15px;
        }
        .action-btns button {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 18px;
            padding: 0;
        }
        .action-btns button:hover {
            color: #0f172a;
        }
        
        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }
        .pagination-controls {
            display: flex;
            gap: 15px;
        }
        .pagination-controls a {
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .pagination-controls a:hover {
            color: #2563eb;
        }

        @media(max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media(max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .dash-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .dash-search { width: 100%; }
            .search-input { flex-grow: 1; }
            .search-input input { width: 100%; }
            .tabs { overflow-x: auto; white-space: nowrap; padding-bottom: 5px; }
        }

        /* Embedded Search bar (below stats, above table) */
        .dash-search-bar {
            margin-bottom: 25px;
        }
        .search-input-full {
            position: relative;
            display: flex;
            align-items: center;
        }
        .search-input-full i {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 15px;
            pointer-events: none;
        }
        .search-input-full input {
            width: 100%;
            padding: 12px 18px 12px 46px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #334155;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            outline: none;
            transition: all 0.2s;
        }
        .search-input-full input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }
    </style>
</head>
<body>
<?php include('includes/header.php'); ?>

    <div class="dash-container">
        
        <!-- Header -->
        <div class="dash-header">
            <div class="dash-title">
                <h2>Welcome! <?php echo htmlspecialchars(ucfirst($_SESSION['alogin'])); ?></h2>
                <p id="live-datetime">Loading...</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon books"><i class="fa fa-book"></i></div>
                <div class="stat-info">
                    <span>Total Books</span>
                    <h3 class="primary"><?php echo htmlentities($totalBooks); ?></h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon borrowed"><i class="fa fa-book"></i></div>
                <div class="stat-info">
                    <span>Borrowed Books</span>
                    <h3 class="primary"><?php echo htmlentities($borrowedBooks); ?></h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon overdue"><i class="fa fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <span>Overdue Books</span>
                    <h3 class="danger"><?php echo htmlentities($overdueBooks); ?></h3>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon members"><i class="fa fa-users"></i></div>
                <div class="stat-info">
                    <span>Total Members</span>
                    <h3 class="primary"><?php echo htmlentities($totalMembers); ?></h3>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="dash-search-bar">
            <div class="search-input-full">
                <i class="fa fa-search"></i>
                <input type="text" id="dashboardSearch" placeholder="Search by book title, borrower name..." oninput="filterOverdueTable(this.value)">
            </div>
        </div>

        <!-- Tabs -->
        <div class="quick-actions-section">
            <h3>Quick Actions</h3>
            <div class="tabs">
                <div class="tab active">Overview</div>
                <div class="tab"><a href="add-book.php">Add New Book</a></div>
                <div class="tab"><a href="reg-students.php">Register New Member</a></div>
                
            </div>
        </div>

        <!-- Overdue Table box -->
        <div class="table-box">
            <div class="table-header">
                <h3>Overdue Books</h3>
                <a href="manage-issued-books.php">Open Page <i class="fa fa-external-link"></i></a>
            </div>
            <div class="overdue-table-wrapper">
                <table class="overdue-table">
                    <thead>
                        <tr>
                            <th>BOOK TITLE</th>
                            <th>BORROWER</th>
                            <th>DUE DATE</th>
                            <th>DAYS OVERDUE</th>
                            <th>FINE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
    // Fetch currently issued books with real DueDate
    $sqlList = "SELECT tblbooks.BookName, tblstudents.FullName, tblissuedbookdetails.IssuesDate,
                       tblissuedbookdetails.DueDate, tblissuedbookdetails.id, tblissuedbookdetails.fine 
                FROM tblissuedbookdetails 
                JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentID 
                JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
                WHERE (tblissuedbookdetails.RetrunStatus IS NULL OR tblissuedbookdetails.RetrunStatus='')
                ORDER BY tblissuedbookdetails.id DESC LIMIT 5";
    $queryList = $dbh->prepare($sqlList);
    $queryList->execute();
    $resultsList = $queryList->fetchAll(PDO::FETCH_OBJ);

    if ($queryList->rowCount() > 0) {
        foreach ($resultsList as $result) {
            $now = new DateTime();
            $daysOverdue = 0;

            // Use stored DueDate if available, else fall back to 14 days from issue
            if (!empty($result->DueDate)) {
                $dueDateObj = new DateTime($result->DueDate);
            } else {
                $dueDateObj = new DateTime($result->IssuesDate);
                $dueDateObj->modify('+14 days');
            }
            $dueDate = $dueDateObj->format('d M Y');

            if ($now > $dueDateObj) {
                $interval = $now->diff($dueDateObj);
                $daysOverdue = $interval->days;
            }

            // Auto fine: ₹50 per day
            $autoFine = $daysOverdue * 50;
            $fineStr = "₹" . number_format($autoFine, 2);

?>
                                <tr>
                                    <td><?php echo htmlentities($result->BookName); ?></td>
                                    <td><?php echo htmlentities($result->FullName); ?></td>
                                    <td><?php echo htmlentities($dueDate); ?></td>
                                    <td>
                                        <?php if ($daysOverdue > 0) { ?>
                                            <span class="badge-danger-soft"><i class="fa fa-exclamation-circle"></i> <?php echo htmlentities($daysOverdue); ?> Days</span>
                                        <?php
            }
            else { ?>
                                            <span style="color:#059669; font-weight:600;">Not Overdue</span>
                                        <?php
            }?>
                                    </td>
                                    <td><?php echo htmlentities($fineStr); ?></td>
                                </tr>
                                <?php
        }
    }
    else { ?>
                            <tr><td colspan="6" style="text-align:center;">No overdue books found.</td></tr>
                        <?php
    }?>
                    </tbody>
                </table>
            </div>
            
            <div class="pagination-container">
                <div>Showing 1 to <?php echo min(5, $queryList->rowCount()); ?> of <?php echo $borrowedBooks; ?> entries</div>
                <div class="pagination-controls">
                    <a href="#" class="disabled"><i class="fa fa-angle-double-left"></i> Previous</a>
                    <a href="#">Next <i class="fa fa-angle-double-right"></i></a>
                </div>
            </div>
        </div>

    </div>

<?php include('includes/footer.php'); ?>
    <!-- CORE JQUERY  -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
    <script>
        // Live clock
        function updateClock() {
            var now = new Date();
            var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
            var months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
            var day = days[now.getDay()].toUpperCase();
            var date = String(now.getDate()).padStart(2,'0');
            var month = months[now.getMonth()];
            var year = now.getFullYear();
            var hours = now.getHours();
            var minutes = String(now.getMinutes()).padStart(2,'0');
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            var hStr = String(hours).padStart(2,'0');
            var el = document.getElementById('live-datetime');
            if (el) el.textContent = month + ' ' + date + ', ' + year + ' | ' + day + ', ' + hStr + '.' + minutes + ' ' + ampm;
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Set the active tab dynamically
        $('.tab').click(function(){
            $('.tab').removeClass('active');
            $(this).addClass('active');
        });

        // Filter overdue table rows
        function filterOverdueTable(query) {
            query = query.toLowerCase().trim();
            var rows = document.querySelectorAll('.overdue-table tbody tr');
            rows.forEach(function(row) {
                var text = row.textContent.toLowerCase();
                row.style.display = (!query || text.indexOf(query) !== -1) ? '' : 'none';
            });
        }
    </script>
</body>
</html>
<?php
}?>

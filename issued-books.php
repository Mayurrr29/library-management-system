<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else { ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>LibraryMS — My Borrowings</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    body { font-family: 'Inter', sans-serif; background: #f7f8f8; margin: 0; }

    /* ── Page header ── */
    .ib-page-header {
        background: #fff;
        border-bottom: 1px solid #e8e8e8;
        padding: 28px 48px 0;
    }

    /* User identity row */
    .ib-user-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .ib-user-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .ib-avatar {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        object-fit: cover;
        display: block;
        flex-shrink: 0;
    }

    .ib-avatar-fallback {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #18534f;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 700;
        font-size: 17px;
        flex-shrink: 0;
    }

    .ib-user-info__name {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.3px;
    }

    .ib-user-info__email {
        font-size: 12.5px;
        color: #6b7280;
        margin-top: 2px;
    }

    .ib-page-label {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* Stats row */
    .ib-stats-row {
        display: flex;
        border-top: 1px solid #f3f4f6;
    }

    .ib-stat-item {
        padding: 14px 28px;
        border-right: 1px solid #f3f4f6;
        min-width: 150px;
    }

    .ib-stat-item:last-child { border-right: none; }

    .ib-stat-item__val {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        line-height: 1;
        margin-bottom: 4px;
    }

    .ib-stat-item__val.green { color: #15803d; }
    .ib-stat-item__val.amber { color: #b45309; }

    .ib-stat-item__label {
        font-size: 11px;
        color: #6b7280;
        font-weight: 500;
    }

    /* ── Body ── */
    .ib-body {
        padding: 28px 48px 56px;
    }

    /* ── Table card shell ── */
    .table-card {
        background: #fff;
        border: 1px solid #e8e8e8;
        border-radius: 8px;
        overflow: hidden;
    }

    .table-card__head {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-card__head-title {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .table-card__head-sub {
        font-size: 12px;
        color: #6b7280;
    }

    /* ── Search / filter bar above table ── */
    .table-toolbar {
        padding: 12px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .table-search-wrap {
        display: flex;
        align-items: center;
        background: #f7f8f8;
        border: 1px solid #e4e4e4;
        border-radius: 7px;
        overflow: hidden;
        width: 240px;
    }

    .table-search-wrap i {
        padding: 0 10px;
        color: #9ca3af;
        font-size: 13px;
    }

    .table-search-input {
        border: none;
        outline: none;
        background: transparent;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        color: #111827;
        padding: 9px 0;
        width: 100%;
    }

    .table-search-input::placeholder { color: #d1d5db; }

    /* ── The actual table ── */
    .borrow-table {
        width: 100%;
        border-collapse: collapse;
    }

    .borrow-table thead th {
        padding: 11px 16px;
        font-size: 10.5px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        background: #f9f9f9;
        border-bottom: 1px solid #e8e8e8;
        text-align: left;
        white-space: nowrap;
    }

    .borrow-table tbody td {
        padding: 14px 16px;
        font-size: 13.5px;
        color: #374151;
        font-weight: 500;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .borrow-table tbody tr:last-child td {
        border-bottom: none;
    }

    .borrow-table tbody tr:hover td {
        background: #fafafa;
    }

    /* Book cover thumbnail */
    .book-cover {
        width: 38px;
        height: 52px;
        border-radius: 4px;
        object-fit: cover;
        display: block;
        background: #e8e8e8;
        border: 1px solid #e4e4e4;
        flex-shrink: 0;
    }

    .book-cover-placeholder {
        width: 38px;
        height: 52px;
        border-radius: 4px;
        background: #f0f0f0;
        border: 1px solid #e4e4e4;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #d1d5db;
        font-size: 16px;
    }

    /* Book cell with cover + name */
    .book-cell {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .book-cell__meta {}

    .book-cell__name {
        font-weight: 600;
        color: #111827;
        font-size: 13.5px;
        margin-bottom: 2px;
    }

    .book-cell__isbn {
        font-size: 11px;
        color: #6b7280;
        font-family: monospace;
    }

    /* Row number */
    .row-num {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 600;
    }

    /* Date cell */
    .date-cell {
        font-size: 13px;
        color: #4b5563;
        white-space: nowrap;
    }

    /* Status badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 5px;
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-pending  { background: #fffbeb; color: #b45309; }
    .badge-returned { background: #f0fdf4; color: #15803d; }
    .badge-fine     { background: #fef2f2; color: #b91c1c; }
    .badge-fine-none { background: #f0fdf4; color: #15803d; }

    /* ── Pagination (custom) ── */
    .table-pagination {
        padding: 14px 20px;
        border-top: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table-pagination__info {
        font-size: 12.5px;
        color: #6b7280;
    }

    .pagination-btns {
        display: flex;
        gap: 4px;
    }

    .pag-btn {
        padding: 6px 12px;
        font-size: 12.5px;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        border: 1px solid #e4e4e4;
        border-radius: 6px;
        background: #fff;
        color: #374151;
        cursor: pointer;
        transition: all 0.15s;
    }

    .pag-btn:hover { background: #f7f8f8; }
    .pag-btn.active { background: #18534f; color: #fff; border-color: #18534f; }
    .pag-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state h3 { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 8px; }
    .empty-state p  { font-size: 13.5px; color: #9ca3af; }
    </style>
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="content-wrapper" style="background:#f7f8f8 !important; padding:0 !important;">

    <?php
    $sid = $_SESSION['stdid'];

    // Fetch user for profile widget
    $user_q = $dbh->prepare("SELECT FullName, EmailId, ProfileImage FROM tblstudents WHERE StudentId=:sid");
    $user_q->bindParam(':sid', $sid, PDO::PARAM_STR);
    $user_q->execute();
    $user_data = $user_q->fetch(PDO::FETCH_OBJ);
    $u_name     = $user_data ? $user_data->FullName : 'Member';
    $u_email    = $user_data ? $user_data->EmailId   : '';
    $u_initials = strtoupper(substr($u_name, 0, 1));
    $u_img = ($user_data && !empty($user_data->ProfileImage) && file_exists('assets/img/profiles/' . $user_data->ProfileImage))
             ? 'assets/img/profiles/' . $user_data->ProfileImage : '';

    // Stats
    $tot_q = $dbh->prepare("SELECT COUNT(*) FROM tblissuedbookdetails
        JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId
        WHERE tblstudents.StudentId = :sid");
    $tot_q->bindParam(':sid', $sid, PDO::PARAM_STR);
    $tot_q->execute();
    $total = $tot_q->fetchColumn();

    $ret_q = $dbh->prepare("SELECT COUNT(*) FROM tblissuedbookdetails
        JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId
        WHERE tblstudents.StudentId = :sid
        AND tblissuedbookdetails.ReturnDate IS NOT NULL AND tblissuedbookdetails.ReturnDate != ''");
    $ret_q->bindParam(':sid', $sid, PDO::PARAM_STR);
    $ret_q->execute();
    $returned = $ret_q->fetchColumn();
    $pending  = $total - $returned;
    ?>

    <!-- ── Page Header ── -->
    <div class="ib-page-header">
        <div class="ib-user-row">
            <div class="ib-user-left">
                <?php if ($u_img): ?>
                    <img src="<?php echo htmlentities($u_img); ?>" class="ib-avatar" alt="Profile">
                <?php else: ?>
                    <div class="ib-avatar-fallback"><?php echo $u_initials; ?></div>
                <?php endif; ?>
                <div>
                    <div class="ib-user-info__name"><?php echo htmlentities($u_name); ?></div>
                    <div class="ib-user-info__email"><?php echo htmlentities($u_email); ?></div>
                </div>
            </div>
            <div class="ib-page-label">My Borrowings</div>
        </div>
        <div class="ib-stats-row">
            <div class="ib-stat-item">
                <div class="ib-stat-item__val"><?php echo $total; ?></div>
                <div class="ib-stat-item__label">Total issued</div>
            </div>
            <div class="ib-stat-item">
                <div class="ib-stat-item__val green"><?php echo $returned; ?></div>
                <div class="ib-stat-item__label">Returned</div>
            </div>
            <div class="ib-stat-item">
                <div class="ib-stat-item__val amber"><?php echo $pending; ?></div>
                <div class="ib-stat-item__label">Pending return</div>
            </div>
        </div>
    </div>

    <div class="ib-body">
        <?php
        $sql = "SELECT tblbooks.BookName, tblbooks.ISBNNumber, tblbooks.bookImage,
                       tblissuedbookdetails.IssuesDate, tblissuedbookdetails.DueDate,
                       tblissuedbookdetails.ReturnDate,
                       tblissuedbookdetails.id as rid, tblissuedbookdetails.fine
                FROM tblissuedbookdetails
                JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId
                JOIN tblbooks    ON tblbooks.id = tblissuedbookdetails.BookId
                WHERE tblstudents.StudentId = :sid
                ORDER BY tblissuedbookdetails.id DESC";
        $query = $dbh->prepare($sql);
        $query->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $total_rows = count($results);
        ?>

        <div class="table-card">
            <div class="table-card__head">
                <div class="table-card__head-title">Borrow History</div>
                <div class="table-card__head-sub"><?php echo $total_rows; ?> record<?php echo $total_rows != 1 ? 's' : ''; ?></div>
            </div>

            <?php if ($total_rows > 0): ?>

            <!-- Custom search -->
            <div class="table-toolbar">
                <div class="table-search-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" class="table-search-input" id="tableSearch" placeholder="Search books…" oninput="filterTable()">
                </div>
            </div>

            <div class="table-responsive">
                <table class="borrow-table" id="borrowTable">
                    <thead>
                        <tr>
                            <th style="width:36px;">#</th>
                            <th style="width:52px;">Cover</th>
                            <th>Book</th>
                            <th>Issued Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Fine (₹)</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php $cnt = 1; foreach ($results as $result):
                            // Resolve book image
                            $bookImg = '';
                            if (!empty($result->bookImage)) {
                                $imgPath = 'admin/bookimg/' . $result->bookImage;
                                if (file_exists($imgPath)) $bookImg = $imgPath;
                            }

                            // Format dates
                            $issueDate  = !empty($result->IssuesDate) ? date('d M Y', strtotime($result->IssuesDate)) : '—';
                            $returnDate = !empty($result->ReturnDate)  ? date('d M Y', strtotime($result->ReturnDate))  : null;

                            // Due date + overdue?
                            $dueDisplay = '—';
                            $isOverdue  = false;
                            $autoFine   = 0;
                            if (!empty($result->DueDate)) {
                                $dueDisplay = date('d M Y', strtotime($result->DueDate));
                                if (empty($result->ReturnDate)) {
                                    $today = new DateTime();
                                    $dueD  = new DateTime($result->DueDate);
                                    if ($today > $dueD) {
                                        $isOverdue = true;
                                        $diff = $today->diff($dueD);
                                        $autoFine = $diff->days * 50;
                                    }
                                }
                            }
                        ?>
                        <tr>
                            <td><span class="row-num"><?php echo $cnt; ?></span></td>
                            <td>
                                <?php if ($bookImg): ?>
                                    <img src="<?php echo htmlentities($bookImg); ?>"
                                         alt="<?php echo htmlentities($result->BookName); ?>"
                                         class="book-cover">
                                <?php else: ?>
                                    <div class="book-cover-placeholder">
                                        <i class="fa fa-book"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="book-cell">
                                    <div class="book-cell__meta">
                                        <div class="book-cell__name"><?php echo htmlentities($result->BookName); ?></div>
                                        <div class="book-cell__isbn"><?php echo htmlentities($result->ISBNNumber); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="date-cell"><?php echo $issueDate; ?></span></td>
                            <td>
                                <?php if ($isOverdue): ?>
                                    <span class="date-cell" style="color:#b91c1c; font-weight:700;">
                                        <i class="fa fa-exclamation-triangle"></i> <?php echo $dueDisplay; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="date-cell"><?php echo $dueDisplay; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (empty($result->ReturnDate)): ?>
                                    <span class="badge-status <?php echo $isOverdue ? 'badge-fine' : 'badge-pending'; ?>">
                                        <?php echo $isOverdue ? 'Overdue' : 'Pending'; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status badge-returned"><?php echo $returnDate; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $fineAmt = (!empty($result->fine) && $result->fine > 0) ? $result->fine : $autoFine;
                                if ($fineAmt > 0): ?>
                                    <span class="badge-status badge-fine">₹<?php echo number_format($fineAmt, 2); ?></span>
                                <?php else: ?>
                                    <span class="badge-status badge-fine-none">No fine</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $cnt++; endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-pagination">
                <div class="table-pagination__info" id="paginationInfo"></div>
                <div class="pagination-btns" id="paginationBtns"></div>
            </div>

            <?php else: ?>
            <div class="empty-state">
                <h3>No borrowing history</h3>
                <p>You haven't borrowed any books yet. Visit the library to get started.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script>
// ── Simple client-side search + pagination ──
var rowsPerPage = 10;
var currentPage = 1;
var filteredRows = [];

function getAllRows() {
    return Array.from(document.querySelectorAll('#tableBody tr'));
}

function filterTable() {
    var query = document.getElementById('tableSearch').value.toLowerCase();
    filteredRows = getAllRows().filter(function(row) {
        return row.textContent.toLowerCase().includes(query);
    });
    currentPage = 1;
    renderPage();
}

function renderPage() {
    var allRows = getAllRows();

    // Hide all rows
    allRows.forEach(function(r) { r.style.display = 'none'; });

    // Show only filtered rows for current page
    var start = (currentPage - 1) * rowsPerPage;
    var end   = start + rowsPerPage;
    var pageRows = filteredRows.slice(start, end);
    pageRows.forEach(function(r) { r.style.display = ''; });

    // Pagination info
    var totalFiltered = filteredRows.length;
    var infoEl = document.getElementById('paginationInfo');
    if (totalFiltered === 0) {
        infoEl.textContent = 'No records found';
    } else {
        var dispStart = start + 1;
        var dispEnd   = Math.min(end, totalFiltered);
        infoEl.textContent = 'Showing ' + dispStart + ' – ' + dispEnd + ' of ' + totalFiltered + ' record' + (totalFiltered !== 1 ? 's' : '');
    }

    // Pagination buttons
    var totalPages = Math.ceil(totalFiltered / rowsPerPage);
    var btnsEl = document.getElementById('paginationBtns');
    btnsEl.innerHTML = '';

    var prev = document.createElement('button');
    prev.className = 'pag-btn';
    prev.textContent = '← Prev';
    prev.disabled = currentPage <= 1;
    prev.onclick = function() { currentPage--; renderPage(); };
    btnsEl.appendChild(prev);

    for (var p = 1; p <= totalPages; p++) {
        (function(page) {
            var btn = document.createElement('button');
            btn.className = 'pag-btn' + (page === currentPage ? ' active' : '');
            btn.textContent = page;
            btn.onclick = function() { currentPage = page; renderPage(); };
            btnsEl.appendChild(btn);
        })(p);
    }

    var next = document.createElement('button');
    next.className = 'pag-btn';
    next.textContent = 'Next →';
    next.disabled = currentPage >= totalPages;
    next.onclick = function() { currentPage++; renderPage(); };
    btnsEl.appendChild(next);
}

// Init on load
document.addEventListener('DOMContentLoaded', function() {
    filteredRows = getAllRows();
    renderPage();
});
</script>
</body>
</html>
<?php } ?>

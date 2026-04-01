<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{ 

if(isset($_POST['issue']))
{
    $studentid=strtoupper(trim($_POST['studentid']));
    $bookid=$_POST['bookid']; 
    $aremark=$_POST['aremark'];
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : date('Y-m-d', strtotime('+14 days'));
    $isissued=1;
    $aqty=$_POST['aqty'];

    if($aqty > 0) {
        $sql="INSERT INTO tblissuedbookdetails(StudentID, BookId, DueDate, remark) VALUES(:studentid, :bookid, :due_date, :aremark)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':studentid',$studentid,PDO::PARAM_STR);
        $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
        $query->bindParam(':due_date',$due_date,PDO::PARAM_STR);
        $query->bindParam(':aremark',$aremark,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            $_SESSION['msg']="Book issued successfully";
            header('location:manage-issued-books.php');
        } else {
            $_SESSION['error']="Something went wrong. Please try again";
            header('location:manage-issued-books.php');
        }
    } else {
        $_SESSION['error']="Book Not available";
        header('location:manage-issued-books.php');   
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Issue a New Book</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap' rel='stylesheet'>

    <style>
        .dash-container {
            padding: 30px;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            min-height: calc(100vh - 150px);
            display: flex;
            justify-content: center;
        }
        .form-box {
            background: #fff;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
            width: 100%;
            max-width: 680px;
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
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .form-group label span { color: #dc2626; margin-left: 3px; }
        .form-control {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 12px 15px;
            height: auto;
            font-size: 15px;
            color: #0f172a;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            transition: all 0.2s;
            width: 100%;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        textarea.form-control { min-height: 100px; resize: vertical; }

        /* Autocomplete */
        .autocomplete-wrap {
            position: relative;
        }
        .autocomplete-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 999;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 260px;
            overflow-y: auto;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            display: none;
        }
        .autocomplete-item {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }
        .autocomplete-item:last-child { border-bottom: none; }
        .autocomplete-item:hover { background: #f0f9ff; }
        .autocomplete-item .item-main { font-weight: 600; color: #1e293b; font-size: 14px; }
        .autocomplete-item .item-sub { font-size: 12px; color: #64748b; margin-top: 2px; }
        .autocomplete-item .avail-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 8px;
        }
        .avail-ok { background: #dcfce7; color: #166534; }
        .avail-no { background: #fee2e2; color: #991b1b; }

        /* Info result boxes */
        .info-result-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 12px 15px;
            margin-top: 10px;
            font-size: 13px;
            color: #0369a1;
            display: none;
        }
        .info-result-box.show { display: block; }
        .info-result-box.error { background: #fff0f0; border-color: #fca5a5; color: #b91c1c; }

        .btn-submit {
            background: #2563eb;
            color: #fff;
            padding: 13px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(37,99,235,0.2);
            transition: all 0.2s;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .btn-submit:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 8px -1px rgba(37,99,235,0.3);
        }
        .btn-submit:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .section-divider {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
            margin: 25px 0 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
    </style>
</head>
<body>
    <!--MENU SECTION START-->
<?php include('includes/header.php');?>
<!--MENU SECTION END-->

    <div class="dash-container">
        <div class="form-box">
            <div class="form-header">
                <h3><i class="fa fa-book" style="color:#3b82f6; margin-right:8px;"></i>Issue a New Book</h3>
                <p>Search and register a book checkout to a student</p>
            </div>
            
            <form role="form" method="post" id="issueForm">
                <input type="hidden" name="aqty" id="aqty_hidden" value="0" />

                <div class="section-divider">Student Details</div>

                <div class="form-group">
                    <label>Student ID<span>*</span></label>
                    <div class="autocomplete-wrap">
                        <input class="form-control" type="text" name="studentid" id="studentid" 
                               autocomplete="off" placeholder="Type Student ID or search by name..." />
                        <div class="autocomplete-list" id="student_list"></div>
                    </div>
                    <div class="info-result-box" id="student_info"></div>
                </div>

                <div class="section-divider">Book Details</div>

                <div class="form-group">
                    <label>ISBN Number or Book Title<span>*</span></label>
                    <div class="autocomplete-wrap">
                        <input class="form-control" type="text" name="book_search" id="book_search" 
                               autocomplete="off" placeholder="Type ISBN or book title to search..." />
                        <input type="hidden" name="bookid" id="bookid_hidden" />
                        <div class="autocomplete-list" id="book_list"></div>
                    </div>
                    <div class="info-result-box" id="book_info"></div>
                </div>

                <div class="form-group">
                    <label>Due Date<span>*</span></label>
                    <input class="form-control" type="date" name="due_date" id="due_date" 
                           value="<?php echo date('Y-m-d', strtotime('+14 days')); ?>"
                           min="<?php echo date('Y-m-d'); ?>" required />
                    <small style="color:#64748b; font-size:12px; margin-top:5px; display:block;">
                        <i class="fa fa-info-circle"></i> Default is 14 days from today. Fine: ₹50/day after due date.
                    </small>
                </div>

                <div class="form-group">
                    <label>Remark<span>*</span></label>
                    <textarea class="form-control" name="aremark" id="aremark" 
                              placeholder="Add condition or notes about this checkout..." required></textarea>
                </div>

                <button type="submit" name="issue" id="submit" class="btn-submit" disabled>
                    <i class="fa fa-check-circle"></i> Issue Book
                </button>
            </form>
        </div>
    </div>

<?php include('includes/footer.php');?>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>

<script>
var studentSelected = false;
var bookSelected = false;

function checkSubmit() {
    $('#submit').prop('disabled', !(studentSelected && bookSelected));
}

// ── Student Autocomplete ──
var studentTimer;
$('#studentid').on('input', function() {
    var q = $(this).val().trim();
    studentSelected = false;
    checkSubmit();
    clearTimeout(studentTimer);
    if (q.length < 1) { $('#student_list').hide(); return; }
    studentTimer = setTimeout(function() {
        $.ajax({
            url: 'search_students.php',
            data: { q: q },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                var html = '';
                if (data && data.length > 0) {
                    $.each(data, function(i, s) {
                        html += '<div class="autocomplete-item" data-id="' + s.StudentId + '" data-name="' + s.FullName + '">' +
                                    '<div class="item-main">' + s.StudentId + ' — ' + s.FullName + '</div>' +
                                    '<div class="item-sub">' + s.EmailId + ' | ' + s.MobileNumber + '</div>' +
                                '</div>';
                    });
                    $('#student_list').html(html).show();
                } else {
                    $('#student_list').html('<div class="autocomplete-item"><div class="item-sub" style="color:#b91c1c;">No student found</div></div>').show();
                }
            }
        });
    }, 300);
});

$(document).on('click', '#student_list .autocomplete-item', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');
    if (!id) return;
    $('#studentid').val(id);
    $('#student_list').hide();
    // Verify via get_student.php
    $.ajax({
        url: 'get_student.php',
        data: { studentid: id },
        type: 'POST',
        success: function(data) {
            $('#student_info').html('<i class="fa fa-user"></i> <strong>' + id + '</strong> — ' + data).addClass('show').removeClass('error');
        }
    });
    studentSelected = true;
    checkSubmit();
});

// ── Book Autocomplete ──
var bookTimer;
$('#book_search').on('input', function() {
    var q = $(this).val().trim();
    bookSelected = false;
    $('#bookid_hidden').val('');
    $('#aqty_hidden').val('0');
    checkSubmit();
    clearTimeout(bookTimer);
    if (q.length < 1) { $('#book_list').hide(); return; }
    bookTimer = setTimeout(function() {
        $.ajax({
            url: 'search_books.php',
            data: { q: q },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                var html = '';
                if (data && data.length > 0) {
                    $.each(data, function(i, b) {
                        var avail = parseInt(b.available);
                        var badge = avail > 0 
                            ? '<span class="avail-badge avail-ok">' + avail + ' available</span>'
                            : '<span class="avail-badge avail-no">Out of stock</span>';
                        html += '<div class="autocomplete-item" data-id="' + b.id + '" data-avail="' + avail + '" data-title="' + b.BookName + '">' +
                                    '<div class="item-main">' + b.BookName + badge + '</div>' +
                                    '<div class="item-sub">ISBN: ' + b.ISBNNumber + ' | ' + b.AuthorName + '</div>' +
                                '</div>';
                    });
                    $('#book_list').html(html).show();
                } else {
                    $('#book_list').html('<div class="autocomplete-item"><div class="item-sub" style="color:#b91c1c;">No books found</div></div>').show();
                }
            }
        });
    }, 300);
});

$(document).on('click', '#book_list .autocomplete-item', function() {
    var id = $(this).data('id');
    var avail = parseInt($(this).data('avail'));
    var title = $(this).data('title');
    if (!id) return;
    $('#book_search').val($(this).find('.item-main').text().replace(/\s*\d+ available|\s*Out of stock/g, '').trim());
    $('#book_list').hide();
    if (avail > 0) {
        $('#bookid_hidden').val(id);
        $('#aqty_hidden').val(avail);
        $('#book_info').html('<i class="fa fa-check-circle"></i> <strong>' + title + '</strong> — ' + avail + ' cop' + (avail > 1 ? 'ies' : 'y') + ' available').addClass('show').removeClass('error');
        bookSelected = true;
    } else {
        $('#bookid_hidden').val('');
        $('#aqty_hidden').val('0');
        $('#book_info').html('<i class="fa fa-times-circle"></i> This book is currently OUT OF STOCK and cannot be issued.').addClass('show error').removeClass('');
        bookSelected = false;
    }
    checkSubmit();
});

// Close dropdowns when clicking outside
$(document).on('click', function(e) {
    if (!$(e.target).closest('.autocomplete-wrap').length) {
        $('.autocomplete-list').hide();
    }
});

// Form validation before submit
$('#issueForm').on('submit', function(e) {
    if (!studentSelected) {
        e.preventDefault();
        alert('Please select a valid student from the search results.');
        return false;
    }
    if (!bookSelected || !$('#bookid_hidden').val()) {
        e.preventDefault();
        alert('Please select an available book from the search results.');
        return false;
    }
});
</script>

</body>
</html>
<?php } ?>

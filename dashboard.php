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
        <title>LibraryMS — Home</title>
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet" />
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

            body {
                font-family: 'Inter', sans-serif;
                background: #f7f8f8;
                margin: 0;
            }

            /* ════════════════════════
                   PAGE HEADER (greeting + stats)
                ════════════════════════ */
            .dash-page-header {
                background: #fff;
                border-bottom: 1px solid #e8e8e8;
                padding: 28px 48px 0;
            }

            .dash-welcome {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                margin-bottom: 24px;
            }

            .dash-greeting {
                font-size: 11px;
                font-weight: 600;
                color: #6b7280;
                letter-spacing: 0.8px;
                text-transform: uppercase;
                margin-bottom: 6px;
            }

            .dash-title {
                font-size: 22px;
                font-weight: 700;
                color: #111827;
                letter-spacing: -0.4px;
                margin: 0;
            }

            .dash-title span {
                color: #18534f;
            }

            .dash-stats-row {
                display: flex;
                border-top: 1px solid #f3f4f6;
            }

            .dash-stat-item {
                padding: 14px 24px;
                border-right: 1px solid #f3f4f6;
                min-width: 140px;
            }

            .dash-stat-item:last-child {
                border-right: none;
            }

            .dash-stat-item__val {
                font-size: 20px;
                font-weight: 700;
                color: #111827;
                line-height: 1;
                margin-bottom: 4px;
            }

            .dash-stat-item__label {
                font-size: 11px;
                color: #6b7280;
                font-weight: 500;
            }

            /* ════════════════════════
                   HERO BANNER (library image)
                ════════════════════════ */
            .dash-hero {
                position: relative;
                height: 420px;
                overflow: hidden;
            }

            .dash-hero__img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center 40%;
                display: block;
            }

            .dash-hero__overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to right,
                        rgba(10, 35, 33, 0.82) 0%,
                        rgba(10, 35, 33, 0.55) 50%,
                        rgba(10, 35, 33, 0.1) 100%);
            }

            .dash-hero__content {
                position: absolute;
                top: 50%;
                left: 48px;
                transform: translateY(-50%);
                max-width: 520px;
            }

            .dash-hero__eyebrow {
                font-size: 11px;
                font-weight: 700;
                color: #4ec8c0;
                letter-spacing: 1.2px;
                text-transform: uppercase;
                margin-bottom: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .dash-hero__eyebrow::before {
                content: '';
                display: inline-block;
                width: 24px;
                height: 2px;
                background: #4ec8c0;
            }

            .dash-hero__title {
                font-size: 38px;
                font-weight: 700;
                color: #fff;
                line-height: 1.15;
                letter-spacing: -1px;
                margin-bottom: 16px;
            }

            .dash-hero__title em {
                font-style: normal;
                color: #a7f3d0;
            }

            .dash-hero__desc {
                font-size: 15px;
                color: rgba(255, 255, 255, 0.75);
                line-height: 1.65;
                margin-bottom: 28px;
            }

            .dash-hero__actions {
                display: flex;
                gap: 12px;
                align-items: center;
                flex-wrap: wrap;
            }

            .btn-hero-primary {
                background: #18534f;
                color: #fff;
                padding: 11px 24px;
                border-radius: 7px;
                font-size: 13.5px;
                font-weight: 600;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: background 0.2s;
                white-space: nowrap;
            }

            .btn-hero-primary:hover {
                background: #0f3d3a;
                text-decoration: none;
                color: #fff;
            }

            .btn-hero-outline {
                background: transparent;
                color: #fff;
                padding: 10px 22px;
                border-radius: 7px;
                font-size: 13.5px;
                font-weight: 500;
                text-decoration: none;
                border: 1px solid rgba(255, 255, 255, 0.4);
                cursor: pointer;
                transition: border-color 0.2s, background 0.2s;
                white-space: nowrap;
            }

            .btn-hero-outline:hover {
                border-color: rgba(255, 255, 255, 0.7);
                background: rgba(255, 255, 255, 0.08);
                text-decoration: none;
                color: #fff;
            }

            /* ════════════════════════
                   SEARCH BANNER
                ════════════════════════ */
            .dash-search-section {
                background: #18534f;
                padding: 32px 48px;
            }

            .dash-search-section__label {
                font-size: 11px;
                font-weight: 600;
                color: rgba(255, 255, 255, 0.75);
                letter-spacing: 0.8px;
                text-transform: uppercase;
                margin-bottom: 10px;
            }

            .dash-search-section__title {
                font-size: 18px;
                font-weight: 600;
                color: #fff;
                margin-bottom: 18px;
                letter-spacing: -0.3px;
            }

            .dash-search {
                display: flex;
                background: #fff;
                border-radius: 7px;
                overflow: hidden;
                max-width: 660px;
            }

            .dash-search__cat {
                padding: 0 14px;
                border-right: 1px solid #e8e8e8;
                background: #f9f9f9;
                display: flex;
                align-items: center;
                flex-shrink: 0;
            }

            .dash-search__cat select {
                border: none;
                background: transparent;
                font-family: 'Inter', sans-serif;
                font-size: 13px;
                font-weight: 500;
                color: #374151;
                cursor: pointer;
                outline: none;
                appearance: none;
                padding-right: 16px;
            }

            .dash-search__input-wrap {
                flex: 1;
                display: flex;
                align-items: center;
                padding: 0 14px;
            }

            .dash-search__input {
                border: none;
                outline: none;
                width: 100%;
                font-size: 13.5px;
                font-family: 'Inter', sans-serif;
                color: #111827;
                background: transparent;
                padding: 13px 0;
            }

            .dash-search__input::placeholder {
                color: #d1d5db;
            }

            .dash-search__btn {
                background: #111827;
                color: #fff;
                border: none;
                padding: 0 22px;
                font-family: 'Inter', sans-serif;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s;
                flex-shrink: 0;
            }

            .dash-search__btn:hover {
                background: #1f2937;
            }

            /* ════════════════════════
                   SERVICES STRIP
                ════════════════════════ */
            .services-strip {
                background: #fff;
                border-top: 1px solid #e8e8e8;
                border-bottom: 1px solid #e8e8e8;
                padding: 0 48px;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
            }

            .service-item {
                padding: 24px 20px;
                border-right: 1px solid #f3f4f6;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .service-item:last-child {
                border-right: none;
            }

            .service-item__number {
                font-size: 11px;
                font-weight: 700;
                color: #18534f;
                letter-spacing: 0.5px;
            }

            .service-item__title {
                font-size: 13.5px;
                font-weight: 700;
                color: #111827;
                letter-spacing: -0.2px;
            }

            .service-item__desc {
                font-size: 12.5px;
                color: #6b7280;
                line-height: 1.5;
            }

            /* ════════════════════════
                   SECTION COMMON
                ════════════════════════ */
            .dash-section {
                padding: 36px 48px;
            }

            .dash-section+.dash-section {
                padding-top: 0;
            }

            .section-hd {
                display: flex;
                justify-content: space-between;
                align-items: baseline;
                margin-bottom: 20px;
            }

            .section-hd__title {
                font-size: 15px;
                font-weight: 700;
                color: #111827;
                letter-spacing: -0.2px;
            }

            .section-hd__link {
                font-size: 12.5px;
                font-weight: 500;
                color: #18534f;
                text-decoration: none;
                transition: color 0.15s;
            }

            .section-hd__link:hover {
                color: #0f3d3a;
                text-decoration: none;
            }

            /* ════════════════════════
                   QUICK ACCESS
                ════════════════════════ */
            .quick-access {
                background: #fff;
                border-top: 1px solid #e8e8e8;
                border-bottom: 1px solid #e8e8e8;
                padding: 0 48px;
                display: flex;
                align-items: center;
                overflow-x: auto;
            }

            .quick-access__label {
                font-size: 11px;
                font-weight: 700;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 14px 18px;
                border-right: 1px solid #f3f4f6;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .quick-access__link {
                display: flex;
                align-items: center;
                padding: 14px 16px;
                font-size: 13px;
                font-weight: 500;
                color: #374151;
                text-decoration: none;
                border-right: 1px solid #f3f4f6;
                white-space: nowrap;
                transition: color 0.15s, background 0.15s;
            }

            .quick-access__link:hover {
                color: #111827;
                background: #f9f9f9;
                text-decoration: none;
            }

            /* ════════════════════════
                   BOOK ROW (horizontal scroll)
                ════════════════════════ */
            .book-row {
                display: flex;
                gap: 14px;
                overflow-x: auto;
                padding-bottom: 6px;
                scroll-snap-type: x mandatory;
            }

            .book-row::-webkit-scrollbar {
                height: 3px;
            }

            .book-row::-webkit-scrollbar-track {
                background: transparent;
            }

            .book-row::-webkit-scrollbar-thumb {
                background: #e4e4e4;
                border-radius: 10px;
            }

            .book-thumb {
                flex: 0 0 140px;
                border-radius: 7px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.09);
                transition: transform 0.2s, box-shadow 0.2s;
                cursor: pointer;
                height: 210px;
                background: #e8e8e8;
                scroll-snap-align: start;
                text-decoration: none;
                display: block;
                flex-shrink: 0;
            }

            .book-thumb:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
            }

            .book-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            /* ════════════════════════
                   LIBRARY PHOTO GALLERY
                ════════════════════════ */
            .photo-gallery {
                display: grid;
                grid-template-columns: 2fr 1fr 1fr;
                grid-template-rows: 200px 200px;
                gap: 10px;
            }

            .photo-gallery__item {
                border-radius: 7px;
                overflow: hidden;
                position: relative;
                background: #e8e8e8;
            }

            .photo-gallery__item--tall {
                grid-row: 1 / 3;
            }

            .photo-gallery__item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                transition: transform 0.4s ease;
            }

            .photo-gallery__item:hover img {
                transform: scale(1.04);
            }

            .photo-gallery__item__caption {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 10px 14px;
                background: linear-gradient(to top, rgba(0, 0, 0, 0.55), transparent);
                font-size: 12px;
                font-weight: 600;
                color: rgba(255, 255, 255, 0.9);
            }

            /* ════════════════════════
                   CATEGORY GRID
                ════════════════════════ */
            .cat-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
                gap: 12px;
            }

            .cat-card {
                background: #fff;
                border: 1px solid #e8e8e8;
                border-radius: 7px;
                overflow: hidden;
                transition: border-color 0.18s, box-shadow 0.18s;
                cursor: pointer;
                text-decoration: none;
                display: block;
            }

            .cat-card:hover {
                border-color: #18534f;
                box-shadow: 0 4px 14px rgba(24, 83, 79, 0.1);
                text-decoration: none;
            }

            .cat-card__img {
                width: 100%;
                height: 100px;
                object-fit: cover;
                display: block;
                background: #f3f4f6;
            }

            .cat-card__body {
                padding: 9px 12px 11px;
            }

            .cat-card__name {
                font-size: 13px;
                font-weight: 600;
                color: #111827;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                margin-bottom: 2px;
            }

            .cat-card__count {
                font-size: 11.5px;
                color: #6b7280;
            }

            /* ════════════════════════
                   ABOUT SECTION
                ════════════════════════ */
            .about-section {
                background: #fff;
                border-top: 1px solid #e8e8e8;
                border-bottom: 1px solid #e8e8e8;
                padding: 56px 48px;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 64px;
                align-items: center;
            }

            .about-section__left {}

            .about-section__eyebrow {
                font-size: 11px;
                font-weight: 700;
                color: #18534f;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 12px;
            }

            .about-section__title {
                font-size: 26px;
                font-weight: 700;
                color: #111827;
                letter-spacing: -0.6px;
                line-height: 1.25;
                margin-bottom: 16px;
            }

            .about-section__body {
                font-size: 14px;
                color: #374151;
                line-height: 1.75;
                margin-bottom: 28px;
            }

            .about-features {
                display: flex;
                flex-direction: column;
                gap: 14px;
            }

            .about-feature {
                display: flex;
                gap: 14px;
                align-items: flex-start;
            }

            .about-feature__dot {
                width: 8px;
                height: 8px;
                border-radius: 2px;
                background: #18534f;
                margin-top: 5px;
                flex-shrink: 0;
            }

            .about-feature__text {
                font-size: 13.5px;
                color: #374151;
                line-height: 1.5;
            }

            .about-feature__text strong {
                color: #111827;
                font-weight: 600;
                display: block;
                margin-bottom: 1px;
            }

            .about-section__right {
                position: relative;
            }

            .about-img-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 220px 160px;
                gap: 10px;
            }

            .about-img-grid__item {
                border-radius: 7px;
                overflow: hidden;
                background: #e8e8e8;
            }

            .about-img-grid__item--wide {
                grid-column: 1 / 3;
            }

            .about-img-grid__item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            /* ════════════════════════
                   HOURS BANNER
                ════════════════════════ */
            .hours-banner {
                background: #0c3a36;
                padding: 32px 48px;
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 24px;
                align-items: center;
            }

            .hours-banner__title {
                font-size: 16px;
                font-weight: 700;
                color: #fff;
                margin-bottom: 6px;
                letter-spacing: -0.2px;
            }

            .hours-banner__sub {
                font-size: 13px;
                color: rgba(255, 255, 255, 0.75);
            }

            .hours-grid {
                display: flex;
                gap: 32px;
            }

            .hours-item__day {
                font-size: 11px;
                font-weight: 700;
                color: rgba(255, 255, 255, 0.5);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 4px;
            }

            .hours-item__time {
                font-size: 13px;
                font-weight: 600;
                color: #a7f3d0;
            }

            /* ════════════════════════
                   NOTICE STRIP
                ════════════════════════ */
            .notice-strip {
                background: #fffbeb;
                border-top: 1px solid #fde68a;
                border-bottom: 1px solid #fde68a;
                padding: 14px 48px;
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 13px;
                color: #92400e;
            }

            .notice-strip__badge {
                font-size: 10.5px;
                font-weight: 700;
                background: #fde68a;
                color: #78350f;
                padding: 2px 8px;
                border-radius: 4px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                flex-shrink: 0;
            }
        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <div class="content-wrapper"
            style="background:#f7f8f8 !important; padding:0 !important; margin-top: 60px !important;">

            <?php
            $sid = $_SESSION['stdid'];

            // Greeting
            $greet_sql = "SELECT FullName FROM tblstudents WHERE StudentId=:sid";
            $greet_q = $dbh->prepare($greet_sql);
            $greet_q->bindParam(':sid', $sid, PDO::PARAM_STR);
            $greet_q->execute();
            $greet_u = $greet_q->fetch(PDO::FETCH_OBJ);
            $greet_name = $greet_u ? explode(' ', trim($greet_u->FullName))[0] : 'there';

            $hour = (int) date('G');
            $timeGreet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

            // Stats
            $total_books = $dbh->query("SELECT COUNT(*) FROM tblbooks")->fetchColumn();
            $total_cats = $dbh->query("SELECT COUNT(*) FROM tblcategory")->fetchColumn();
            $my_issued = $dbh->prepare("SELECT COUNT(*) FROM tblissuedbookdetails JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId WHERE tblstudents.StudentId=:sid");
            $my_issued->bindParam(':sid', $sid, PDO::PARAM_STR);
            $my_issued->execute();
            $my_count = $my_issued->fetchColumn();

            // Categories
            $cat_sql = "SELECT id, CategoryName FROM tblcategory ORDER BY CategoryName";
            $cat_q = $dbh->prepare($cat_sql);
            $cat_q->execute();
            $cats = $cat_q->fetchAll(PDO::FETCH_OBJ);
            ?>

            <!-- ── Stats Header ── -->
            <!-- <div class="dash-page-header">
        <div class="dash-welcome">
            <div>
                <div class="dash-greeting"><?php echo $timeGreet; ?>, <?php echo htmlentities($greet_name); ?></div>
                <h1 class="dash-title">Library <span>Management System</span></h1>
            </div>
        </div>
        <div class="dash-stats-row">
            <div class="dash-stat-item">
                <div class="dash-stat-item__val"><?php echo number_format($total_books); ?></div>
                <div class="dash-stat-item__label">Books in collection</div>
            </div>
            <div class="dash-stat-item">
                <div class="dash-stat-item__val"><?php echo $total_cats; ?></div>
                <div class="dash-stat-item__label">Categories</div>
            </div>
            <div class="dash-stat-item">
                <div class="dash-stat-item__val"><?php echo $my_count; ?></div>
                <div class="dash-stat-item__label">My borrowed books</div>
            </div>
        </div>
    </div> -->

            <!-- ── Hero Banner ── -->
            <div class="dash-hero">
                <img class="dash-hero__img"
                    src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1600&q=80"
                    alt="Library shelves filled with books">
                <div class="dash-hero__overlay"></div>
                <div class="dash-hero__content">
                    <div class="dash-hero__eyebrow">Welcome to the Library</div>
                    <h2 class="dash-hero__title">Knowledge at <em>your fingertips</em></h2>
                    <p class="dash-hero__desc">Browse our curated collection of books across every subject. Borrow, read,
                        and return — all from one place.</p>
                    <div class="dash-hero__actions">
                        <a href="listed-books.php" class="btn-hero-primary">Browse Books</a>
                        <a href="issued-books.php" class="btn-hero-outline">My Borrowings</a>
                    </div>
                </div>
            </div>

            <!-- ── Notice Strip ── -->
            <div class="notice-strip">
                <span class="notice-strip__badge">Notice</span>
                Books must be returned within 14 days of issue. Overdue books may attract a daily fine.
            </div>

            <!-- ── Search Banner ── -->
            <div class="dash-search-section">
                <div class="dash-search-section__label">Catalogue Search</div>
                <div class="dash-search-section__title">Find a book in the collection</div>
                <form action="listed-books.php" method="GET">
                    <div class="dash-search">
                        <div class="dash-search__cat">
                            <select name="cat">
                                <option value="">All Categories</option>
                                <?php foreach ($cats as $c): ?>
                                    <option value="<?php echo htmlentities($c->id); ?>">
                                        <?php echo htmlentities($c->CategoryName); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="dash-search__input-wrap">
                            <input type="text" name="search" class="dash-search__input"
                                placeholder="Search by title, author, or ISBN…" autocomplete="off">
                        </div>
                        <button type="submit" class="dash-search__btn">Search</button>
                    </div>
                </form>
            </div>

            <!-- ── Services Strip ── -->
            <div class="services-strip">
                <div class="service-item">
                    <div class="service-item__number">01</div>
                    <div class="service-item__title">Browse Catalogue</div>
                    <div class="service-item__desc">Search thousands of books by title, author, or ISBN across all
                        categories.</div>
                </div>
                <div class="service-item">
                    <div class="service-item__number">02</div>
                    <div class="service-item__title">Borrow Books</div>
                    <div class="service-item__desc">Issue books directly at the library counter with your student ID.</div>
                </div>
                <div class="service-item">
                    <div class="service-item__number">03</div>
                    <div class="service-item__title">Track Returns</div>
                    <div class="service-item__desc">View your borrowing history and check pending return deadlines.</div>
                </div>
                <div class="service-item">
                    <div class="service-item__number">04</div>
                    <div class="service-item__title">Stay Updated</div>
                    <div class="service-item__desc">New books are added regularly. Check back often for the latest
                        additions.</div>
                </div>
            </div>

            <!-- ── Quick Access ── -->
            <div class="quick-access">
                <div class="quick-access__label">Browse by</div>
                <a href="listed-books.php" class="quick-access__link">All Books</a>
                <?php foreach (array_slice($cats, 0, 6) as $qc): ?>
                    <a href="listed-books.php?cat=<?php echo $qc->id; ?>"
                        class="quick-access__link"><?php echo htmlentities($qc->CategoryName); ?></a>
                <?php endforeach; ?>
                <a href="issued-books.php" class="quick-access__link">My Borrowings</a>
            </div>

            <!-- ── Recently Added Books ── -->
            <div class="dash-section">
                <div class="section-hd">
                    <div class="section-hd__title">Recently Added</div>
                    <a href="listed-books.php" class="section-hd__link">View all books &rarr;</a>
                </div>
                <div class="book-row">
                    <?php
                    $books_sql = "SELECT BookName, bookImage, id FROM tblbooks ORDER BY id DESC LIMIT 14";
                    $books_q = $dbh->prepare($books_sql);
                    $books_q->execute();
                    $books = $books_q->fetchAll(PDO::FETCH_OBJ);
                    if ($books_q->rowCount() > 0) {
                        foreach ($books as $bk) {
                            $img = 'admin/bookimg/' . htmlentities($bk->bookImage);
                            if (empty($bk->bookImage) || !file_exists($img)) {
                                $img = 'https://via.placeholder.com/300x450/e8e8e8/9ca3af?text=' . urlencode($bk->BookName);
                            }
                            echo '<a href="listed-books.php" class="book-thumb"><img src="' . $img . '" alt="' . htmlentities($bk->BookName) . '"></a>';
                        }
                    } else {
                        $placeholders = [
                            'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1532012197267-da84d127e765?auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                            'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=300&q=80',
                        ];
                        foreach ($placeholders as $ph) {
                            echo '<a href="listed-books.php" class="book-thumb"><img src="' . $ph . '"></a>';
                        }
                    }
                    ?>
                </div>
            </div>

            <!-- ── Library Photo Gallery ── -->
            <div class="dash-section" style="padding-top: 0;">
                <div class="section-hd">
                    <div class="section-hd__title">Inside Our Library</div>
                </div>
                <div class="photo-gallery">
                    <div class="photo-gallery__item photo-gallery__item--tall">
                        <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=900&q=80"
                            alt="Library reading room" loading="lazy">
                        <div class="photo-gallery__item__caption">Main Reading Hall</div>
                    </div>
                    <div class="photo-gallery__item">
                        <img src="https://images.unsplash.com/photo-1601330676094-f67a35e167db?q=80&w=1074&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            alt="Book shelves" loading="lazy">
                        <div class="photo-gallery__item__caption">Book Stacks</div>
                    </div>
                    <div class="photo-gallery__item">
                        <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=600&q=80"
                            alt="Library corridor" loading="lazy">
                        <div class="photo-gallery__item__caption">Catalogue Aisles</div>
                    </div>
                    <div class="photo-gallery__item">
                        <img src="https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&w=600&q=80"
                            alt="Study area" loading="lazy">
                        <div class="photo-gallery__item__caption">Study Tables</div>
                    </div>
                    <div class="photo-gallery__item">
                        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=600&q=80"
                            alt="Reading corner" loading="lazy">
                        <div class="photo-gallery__item__caption">Reading Corner</div>
                    </div>
                </div>
            </div>

            <!-- ── Browse by Category ── -->
            <div class="dash-section" style="padding-top: 0;">
                <div class="section-hd">
                    <div class="section-hd__title">Browse by Category</div>
                    <a href="listed-books.php" class="section-hd__link">All categories &rarr;</a>
                </div>
                <div class="cat-grid">
                    <?php foreach ($cats as $cat):
                        $img_q = $dbh->prepare("SELECT bookImage FROM tblbooks WHERE CatId=:cid AND bookImage != '' LIMIT 1");
                        $img_q->bindParam(':cid', $cat->id, PDO::PARAM_INT);
                        $img_q->execute();
                        $bk2 = $img_q->fetch(PDO::FETCH_OBJ);
                        $catImg = ($bk2 && !empty($bk2->bookImage) && file_exists('admin/bookimg/' . $bk2->bookImage))
                            ? 'admin/bookimg/' . $bk2->bookImage
                            : 'https://via.placeholder.com/320x200/e8e8e8/9ca3af?text=' . urlencode($cat->CategoryName);

                        $book_count_q = $dbh->prepare("SELECT COUNT(*) FROM tblbooks WHERE CatId=:cid");
                        $book_count_q->bindParam(':cid', $cat->id, PDO::PARAM_INT);
                        $book_count_q->execute();
                        $book_count = $book_count_q->fetchColumn();
                        ?>
                        <a href="listed-books.php?cat=<?php echo $cat->id; ?>" class="cat-card">
                            <img src="<?php echo $catImg; ?>" class="cat-card__img"
                                alt="<?php echo htmlentities($cat->CategoryName); ?>">
                            <div class="cat-card__body">
                                <div class="cat-card__name"><?php echo htmlentities($cat->CategoryName); ?></div>
                                <div class="cat-card__count"><?php echo $book_count; ?>
                                    <?php echo $book_count == 1 ? 'book' : 'books'; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ── About Section ── -->
            <div class="about-section">
                <div class="about-section__left">
                    <div class="about-section__eyebrow">About the Library</div>
                    <h2 class="about-section__title">A place for learning, discovery, and quiet study</h2>
                    <p class="about-section__body">Our library is a central hub for academic resources, curated to support
                        every learner. From reference titles to course textbooks, fiction to research journals — we maintain
                        a rich and growing collection available to all registered members.</p>
                    <div class="about-features">
                        <div class="about-feature">
                            <div class="about-feature__dot"></div>
                            <div class="about-feature__text">
                                <strong>Extensive Catalogue</strong>
                                Thousands of books across dozens of categories, updated regularly with new additions.
                            </div>
                        </div>
                        <div class="about-feature">
                            <div class="about-feature__dot"></div>
                            <div class="about-feature__text">
                                <strong>Simple Borrowing</strong>
                                Issue books with your student ID and track all returns from your personal dashboard.
                            </div>
                        </div>
                        <div class="about-feature">
                            <div class="about-feature__dot"></div>
                            <div class="about-feature__text">
                                <strong>Dedicated Study Spaces</strong>
                                Quiet reading areas available for individual and group study during library hours.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-section__right">
                    <div class="about-img-grid">
                        <div class="about-img-grid__item about-img-grid__item--wide">
                            <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=900&q=80"
                                alt="Library interior" loading="lazy">
                        </div>
                        <div class="about-img-grid__item">
                            <img src="https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=600&q=80"
                                alt="Reading books" loading="lazy">
                        </div>
                        <div class="about-img-grid__item">
                            <img src="https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=600&q=80"
                                alt="Open book close up" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Library Hours Banner ── -->
            <div class="hours-banner">
                <div>
                    <div class="hours-banner__title">Library Hours</div>
                    <div class="hours-banner__sub">We are open throughout the week. Visit us during reading hours.</div>
                </div>
                <div class="hours-grid">
                    <div class="hours-item">
                        <div class="hours-item__day">Mon – Fri</div>
                        <div class="hours-item__time">8:00 AM – 8:00 PM</div>
                    </div>
                    <div class="hours-item">
                        <div class="hours-item__day">Saturday</div>
                        <div class="hours-item__time">9:00 AM – 5:00 PM</div>
                    </div>
                    <div class="hours-item">
                        <div class="hours-item__day">Sunday</div>
                        <div class="hours-item__time">Closed</div>
                    </div>
                </div>
            </div>

        </div>

        <?php include('includes/footer.php'); ?>
        <script src="assets/js/jquery-1.10.2.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/custom.js"></script>
    </body>

    </html>
<?php } ?>
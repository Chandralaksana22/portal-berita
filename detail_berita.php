<?php
require_once 'config/auth.php';
require_once 'config/db.php';

// Debug: Display PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect or show an error message if the ID is not provided or not numeric
    header("Location: news.php");
    exit();
}

$news_id = $_GET['id'];

// Fetch the news details
$query_news_details = "SELECT p.*, u.username 
                       FROM posts p
                       JOIN users u ON p.publisher_id = u.id
                       WHERE p.id = ? AND p.status = 'approved'";

$stmt_news_details = $mysqli->prepare($query_news_details);
$stmt_news_details->bind_param("i", $news_id);
$stmt_news_details->execute();
$result_news_details = $stmt_news_details->get_result();

// Debug: Display database errors
if (!$result_news_details) {
    echo 'Query error: ' . $mysqli->error;
    exit();
}

// Check if the news article exists and is approved
if ($result_news_details->num_rows === 0) {
    // Redirect or show an error message if the news article does not exist or is not approved
    header("Location: news.php");
    exit();
}

// Get the news details
$row = $result_news_details->fetch_assoc();

// Increment views
$updated_views = $row['views'] + 1;
$query_update_views = "UPDATE posts SET views = ? WHERE id = ?";
$stmt_update_views = $mysqli->prepare($query_update_views);
$stmt_update_views->bind_param("ii", $updated_views, $news_id);
$stmt_update_views->execute();

// Debug: Display database errors
if ($stmt_update_views->errno) {
    echo 'Query error: ' . $stmt_update_views->error;
    exit();
}

// Fetch recent posts
$query_recent_posts = "SELECT p.id, p.judul, u.username, p.views, p.gambar
                       FROM posts p
                       JOIN users u ON p.publisher_id = u.id
                       WHERE p.status = 'approved'
                       ORDER BY p.created_at DESC
                       LIMIT 5";


$result_recent_posts = $mysqli->query($query_recent_posts);

// Debug: Display database errors
if (!$result_recent_posts) {
    echo 'Query error: ' . $mysqli->error;
    exit();
}

// Display the news details
?>



<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BlogXpress | Blog and News Minimal Responsive HTML Template</title>
    <meta name="description" content="Author: AxilTheme, Template: HTML, Category: Blog, Price: $13.00,
    Length: 23 pages">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/media/favicon.svg">
    <link rel="stylesheet" href="assets/css/fonts/icomoon.css">
    <link rel="stylesheet" href="assets/css/vendor/slick/slick.css">
    <link rel="stylesheet" href="assets/css/vendor/slick/slick-theme.css">
    <link rel="stylesheet" href="assets/css/vendor/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">


    <!-- Site Stylesheet -->
    <link rel="stylesheet" href="assets/css/app.css">

</head>

<body class="mobilemenu-active">
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  	<![endif]-->


    <a href="#main-wrapper" id="backto-top" class="back-to-top" aria-label="Back To Top">
    <svg viewBox="0 0 24 24" width="20px" height="20px" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 20V4M12 4L6 10M12 4L18 10" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
    </a>

    <div id="main-wrapper" class="main-wrapper">

        <?php include 'assets/components/navigation.php'; ?>
        <!--=====================================-->
        <!--=          Post Area Start          =-->
        <!--=====================================-->
        <section class="space-top-60 space-bottom-60 single-blog-wrap1 bg-color-light-1 transition-default">
            <div class="container">
                <div class="row sticky-coloum-wrap">
                    <div class="col-lg-8 sticky-coloum-item">
                        <div class="single-blog-content content-layout1 pe-lg-4">
                            <h1 class="entry-title color-dark-1"><?php echo $row['judul']; ?></h1>
                            <ul class="entry-meta color-dark-1">
                                <li class="post-author">
                                    by
                                    <a href="#">
                                        <?php echo $row['username']; ?>
                                    </a>
                                </li>
                                <li>
                                <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="#1C274C" stroke-width="1.5"/>
                                    <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#1C274C" stroke-width="1.5"/>
                                    </svg> <?php echo $updated_views; ?> Views
                                </li>
                            </ul>
                            <div class="axil-social social-layout-1 size-small gap-12">
                                <ul>
                                    <li class="facebook">
                                        <a aria-label="Learn more from Facebook" href="https://facebook.com/">
                                        <svg width="15px" height="15px" viewBox="-5 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>facebook [#ffffff]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-385.000000, -7399.000000)" fill="#ffffff"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M335.821282,7259 L335.821282,7250 L338.553693,7250 L339,7246 L335.821282,7246 L335.821282,7244.052 C335.821282,7243.022 335.847593,7242 337.286884,7242 L338.744689,7242 L338.744689,7239.14 C338.744689,7239.097 337.492497,7239 336.225687,7239 C333.580004,7239 331.923407,7240.657 331.923407,7243.7 L331.923407,7246 L329,7246 L329,7250 L331.923407,7250 L331.923407,7259 L335.821282,7259 Z" id="facebook-[#ffffff]"> </path> </g> </g> </g> </g></svg>
                                        </a>
                                    </li>
                                    <li class="instagram">
                                        <a aria-label="Learn more from Instagram" href="https://instagram.com/">
                                           <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z" fill="#fffafa"></path> <path d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z" fill="#fffafa"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z" fill="#fffafa"></path> </g></svg>
                                        </a>
                                    </li>
                                    <li class="mail-fast">
                                        <a aria-label="Learn more from Mail fast" href="https://mail-fast.com/">
                                        <svg viewBox="0 0 24 24" width="15px" height="15px" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M4 19L9 14M20 19L15 14M3.02832 10L10.2246 14.8166C10.8661 15.2443 11.1869 15.4581 11.5336 15.5412C11.8399 15.6146 12.1593 15.6146 12.4657 15.5412C12.8124 15.4581 13.1332 15.2443 13.7747 14.8166L20.971 10M10.2981 4.06879L4.49814 7.71127C3.95121 8.05474 3.67775 8.22648 3.4794 8.45864C3.30385 8.66412 3.17176 8.90305 3.09111 9.161C3 9.45244 3 9.77535 3 10.4212V16.8C3 17.9201 3 18.4802 3.21799 18.908C3.40973 19.2843 3.71569 19.5903 4.09202 19.782C4.51984 20 5.0799 20 6.2 20H17.8C18.9201 20 19.4802 20 19.908 19.782C20.2843 19.5903 20.5903 19.2843 20.782 18.908C21 18.4802 21 17.9201 21 16.8V10.4212C21 9.77535 21 9.45244 20.9089 9.161C20.8282 8.90305 20.6962 8.66412 20.5206 8.45864C20.3223 8.22648 20.0488 8.05474 19.5019 7.71127L13.7019 4.06879C13.0846 3.68116 12.776 3.48735 12.4449 3.4118C12.152 3.34499 11.848 3.34499 11.5551 3.4118C11.224 3.48735 10.9154 3.68116 10.2981 4.06879Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                        </a>
                                    </li>
                                    <li class="pinterest">
                                        <a aria-label="Learn more from Pinterest" href="https://pinterest.com/">
                                        <svg style="fill: white;" fill="#000000" width="15px" height="15px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="7935ec95c421cee6d86eb22ecd12951c"> <path style="display: inline;" d="M220.646,338.475C207.223,408.825,190.842,476.269,142.3,511.5 c-14.996-106.33,21.994-186.188,39.173-270.971c-29.293-49.292,3.518-148.498,65.285-124.059 c76.001,30.066-65.809,183.279,29.38,202.417c99.405,19.974,139.989-172.476,78.359-235.054 C265.434-6.539,95.253,81.775,116.175,211.161c5.09,31.626,37.765,41.22,13.062,84.884c-57.001-12.65-74.005-57.6-71.822-117.533 c3.53-98.108,88.141-166.787,173.024-176.293c107.34-12.014,208.081,39.398,221.991,140.376 c15.67,113.978-48.442,237.412-163.23,228.529C258.085,368.704,245.023,353.283,220.646,338.475z"> </path> </g> </g></svg>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="mb-4 box-border-dark-1 radius-default transition-default">
                                <div class="figure-holder img-height-100 radius-medium">
                                    <img width="810" height="490" src="assets/img/<?php echo $row['gambar']; ?>" alt="Post">
                                </div>
                            </div>
                            <p><?php echo $row['isi']; ?></p>
                        </div>
                    </div>
                    <div class="col-lg-4 sticky-coloum-item">
                        <div class="sidebar-global sidebar-layout4">
                            <div class="sidebar-widget">
                                <div class="section-heading heading-style-6">
                                    <h3 class="title">Short Stories</h3>
                                </div>
                                <div class="widget-post post-layout1">
                                    <?php while ($recent_row = $result_recent_posts->fetch_assoc()) : ?>
                                        <div class="post-box">
                                            <div class="figure-holder radius-default">
                                                <a href="detail_news.php?id=<?php echo $recent_row['id']; ?>" class="link-wrap figure-overlay img-height-100"><img width="700" height="470" src="assets/img/<?php echo $recent_row['gambar']; ?>" alt="Post"></a>
                                            </div>
                                            <div class="content-holder">
                                                <h3 class="entry-title h3-small underline-animation" style="color:#a2a2a2"><a href="detail_news.php?id=<?php echo $recent_row['id']; ?>" class="link-wrap"><?php echo $recent_row['judul']; ?>a</a></h3>
                                                <ul class="entry-meta color-light-1-fixed">
                                                    <li>
                                                    <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="#1C274C" stroke-width="1.5"/>
                                    <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#1C274C" stroke-width="1.5"/>
                                    </svg><?php echo $recent_row['views']; ?> Views
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>

                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </section>
        <!-- <section class="space-top-50 space-bottom-60 bg-color-light-2">
            <div class="container">
                <div class="section-heading heading-style-1">
                    <h2 class="title">Recent Post</h2>
                </div>
                <div class="position-relative">
                    <div id="post-slider-3" class="post-slider-3 gutter-30 outer-top-5 initially-none">
                        <div class="slick-list draggable">
                            <div class="slick-track" style="opacity: 1; width: 4966px; transform: translate3d(-1146px, 0px, 0px);">
                            <?php while ($recent_row = $result_recent_posts->fetch_assoc()) : ?>
                            <div class="single-slide">
                                <div class="post-box-layout6 box-border-dark-1 radius-default padding-20 bg-color-scandal box-shadow-large shadow-style-2 transition-default">
                                    <div class="figure-holder radius-default">
                                        <a href="detail_news.php?id=<?php echo $recent_row['id']; ?>" class="link-wrap img-height-100"><img width="660" height="470" src="assets/img/<?php echo $recent_row['gambar']; ?>" alt="Post"></a>
                                    </div>
                                    <div class="content-holder">
                                        <h3 class="entry-title color-dark-1-fixed underline-animation"><a href="detail_news.php?id=<?php echo $recent_row['id']; ?>" class="link-wrap">Underwater Exercise Is Used Strengthen Muscles</a></h3>
                                        <ul class="entry-meta color-dark-1-fixed">
                                            <li class="post-author">
                                                <a href="#">
                                                    <?php echo $recent_row['username']; ?>
                                                </a>
                                            </li>
                                            <li>
                                                <i class="regular-eye"></i><?php echo $recent_row['views']; ?> Views
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                            </div>
                        </div>

                    </div>
                    <ul class="slider-navigation-layout1 position-layout2 color-light-1 nav-size-large">
                        <li id="post-prev-3" class="prev"><i class="regular-arrow-left"></i></li>
                        <li id="post-next-3" class="next"><i class="regular-arrow-right"></i></li>
                    </ul>
                </div>
            </div>
        </section> -->
        <footer class="footer footer1">
            <div class="footer-main">
                <div class="container">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="footer-widget">
                                <div class="footer-about pe-lg-5">
                                    <div class="logo-holder">
                                        <a href="index.html" class="link-wrap img-height-100" aria-label="Site Logo"><img width="131" height="47" src="assets/media/logo-light.svg" alt="logo"></a>
                                    </div>
                                    <p class="description">Expert insights, industry trends, and inspiring stories that help you live and work on your own terms. Expert insights, industry trends.</p>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                  
                </div>
                <div class="footer-copyright">
                    <span class="copyright-text">© 2024. All rights reserved.</span>
                </div>
            </div>
        </footer>
    </div>


    <!-- Jquery Js -->
    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="assets/js/vendor/isotope.pkgd.min.js"></script>
    <script src="assets/js/vendor/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/vendor/slick.min.js"></script>
    <script src="assets/js/vendor/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/vendor/js.cookie.js"></script>
    <script src="assets/js/vendor/jquery.style.switcher.js"></script>
    <script src="assets/js/vendor/jquery.mb.YTPlayer.min.js"></script>
    <script src="assets/js/vendor/theia-sticky-sidebar.min.js"></script>
    <script src="assets/js/vendor/resize-sensor.min.js"></script>


    <!-- Site Scripts -->
    <script src="assets/js/app.js"></script>
</body>

</html>
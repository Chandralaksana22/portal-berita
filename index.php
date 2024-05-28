<?php
require_once 'config/auth.php';
require_once 'config/db.php';

// Fetch approved posts
$query_approved_posts = "SELECT posts.id, posts.judul, posts.gambar, posts.views, users.username
                        FROM posts
                        INNER JOIN users ON posts.publisher_id = users.id
                        WHERE posts.status = 'approved'
                        ORDER BY posts.id DESC";

$result_approved_posts = $mysqli->query($query_approved_posts);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the latest news (limit 1)
$query_latest_news = "SELECT p.*, u.username 
                      FROM posts p
                      JOIN users u ON p.publisher_id = u.id
                      WHERE p.status = 'approved'
                      ORDER BY p.created_at DESC
                      LIMIT 1";

$result_latest_news = $mysqli->query($query_latest_news);

// Debug: Display database errors
if (!$result_latest_news) {
    echo 'Query error: ' . $mysqli->error;
    exit();
}

// Fetch the recent news (limit 3)
$query_recent_news = "SELECT p.*, u.username 
                      FROM posts p
                      JOIN users u ON p.publisher_id = u.id
                      WHERE p.status = 'approved'
                      ORDER BY p.created_at DESC
                      LIMIT 1, 3";

$result_recent_news = $mysqli->query($query_recent_news);

// Debug: Display database errors
if (!$result_recent_news) {
    echo 'Query error: ' . $mysqli->error;
    exit();
}
// Fetch popular posts (top 5 based on views)
$query_popular_posts = "SELECT p.*, u.username 
                        FROM posts p
                        JOIN users u ON p.publisher_id = u.id
                        WHERE p.status = 'approved'
                        ORDER BY p.views DESC
                        LIMIT 5";

$result_popular_posts = $mysqli->query($query_popular_posts);

// Debug: Display database errors
if (!$result_popular_posts) {
    echo 'Query error: ' . $mysqli->error;
    exit();
}
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
    <link href="https://db.onlinewebfonts.com/c/0265b98b68ecf1b3d801b5df4dc155e7?family=icomoon" rel="stylesheet">
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
        <section class="post-wrap-layout15 space-top-40 bg-color-light-1 transition-default">
            <div class="container">
                <div class="box-border-dark-2 bg-color-light-1 radius-default padding-30 pxy-xs-10 transition-default">
                    <div class="slick-slider slick-dot-style2" data-slick='{
                "infinite": true, 
                "autoplay": false, 
                "arrows": false, 
                "dots": false, 
                "slidesToShow": 1
            }'>

                        <?php while ($row = $result_approved_posts->fetch_assoc()) : ?>
                            <div class="post-box-layout17 radius-default" style="background-image: url(assets/img/<?= $row['gambar'] ?>);">
                                <div class="content-holder">
                                    <h3 class="entry-title h3-large color-dark-1 underline-animation"><a href="detail_berita.php?id=<?= $row['id'] ?>" class="link-wrap">
                                            <?= $row['judul'] ?></a></h3>
                                    <ul class="entry-meta color-dark-2">
                                        <li class="post-author">
                                            <a href="author.html">
                                                <img src="assets/media/blog/profile5.webp" alt="Author">
                                                Author : <?php echo $row['username']; ?>
                                            </a>
                                        </li>
                                        <li>
                                            
                                    <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="#1C274C" stroke-width="1.5"/>
                                    <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#1C274C" stroke-width="1.5"/>
                                    </svg><?= $row['views'] ?> Views
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </section>
        <!--=====================================-->
        <!--=          Post Area Start          =-->
        <!--=====================================-->
        <section class="post-wrap-layout16 space-top-50 bg-color-light-1 transition-default">
            <div class="container">
                <div class="section-heading heading-style-5">
                    <h2 class="title">Recent Articles</h2>
                </div>
                <div class="row g-3">
                    <div class="col-lg-7">
                        <?php while ($latest_row = $result_latest_news->fetch_assoc()) : ?>
                            <div class="animated-border border-style-large transition-default">
                                <div class="animation-child post-box-layout18 box-border-dark-2 radius-default padding-20 bg-color-light-1 transition-default">
                                    <div class="figure-holder radius-default">
                                        <a href="detail_berita.php?id=<?php echo $latest_row['id']; ?>" class="link-wrap"><img width="670" height="478" src="assets/img/<?php echo $latest_row['gambar']; ?>" alt="Post"></a>
                                    </div>
                                    <div class="content-holder">
                                        <h3 class="entry-title color-dark-1 h3-regular underline-animation"><a href="detail_berita.php?id=<?php echo $latest_row['id']; ?>" class="link-wrap"> <?php echo $latest_row['judul']; ?></a></h3>
                                        <div class="entry-description color-dark-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?php echo $latest_row['isi']; ?></div>
                                        <ul class="entry-meta color-dark-2">
                                            <li class="post-author">
                                                <a href="author.html">
                                                    By: <?php echo $latest_row['username']; ?>
                                                </a>
                                            </li>
                                            <li>
                                                <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="#1C274C" stroke-width="1.5"/>
                                    <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#1C274C" stroke-width="1.5"/>
                                    </svg><?php echo $latest_row['views']; ?> Views
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="col-lg-5">
                        <div class="multi-posts-layout4">
                            <?php while ($recent_row = $result_recent_news->fetch_assoc()) : ?>
                                <div class="animated-border border-style-medium transition-default">
                                    <div class="animation-child post-box bg-color-light-1 radius-default box-border-dark-2 transition-default">
                                        <div class="content-holder">
                                            <div class="entry-category style-3 color-dark-1">
                                                <ul>
                                                    <li>
                                                        <a href="#">New</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <h3 class="entry-title color-dark-1 h3-small underline-animation"><a href="detail_berita.php?id=<?php echo $recent_row['id']; ?>" class="link-wrap" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?php echo $recent_row['judul']; ?></a></h3>
                                            <ul class="entry-meta color-dark-1">
                                                <li class="post-author">
                                                    <a href="#">
                                                     By :<?php echo $recent_row['username']; ?>
                                                    </a>
                                                </li>
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
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="post-wrap-layout18 space-top-50 bg-color-light-1 transition-default">
            <div class="container">
                <div class="section-heading heading-style-5">
                    <h2 class="title">Popular Articles</h2>
                </div>
                <div class="row sticky-coloum-wrap">
                    <div class="col-12 sticky-coloum-item">
                        <div class="row g-3 pe-lg-4">
                        <?php while ($popular_row = $result_popular_posts->fetch_assoc()) : ?>
                            <div class="col-12">
                                <div class="h-100 animated-border border-style-medium transition-default">
                                    <div class="animation-child post-box-layout9 box-border-dark-2 figure-scale-animation radius-default padding-20 bg-color-light-1 transition-default">
                                        <div class="figure-holder radius-default">
                                            <a href="detail_berita.php?id=<?php echo $popular_row['id']; ?>" class="link-wrap img-height-100"><img width="500" height="500" src="assets/img/<?php echo $popular_row['gambar']; ?>" alt="Post"></a>
                                        </div>
                                        <div class="content-holder">
                                            <div>
                                                <div class="entry-category style-2 color-dark-2">
                                                    <ul>
                                                        <li>
                                                            <a href="#">Popular</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <h3 class="entry-title color-dark-1 underline-animation"><a href="detail_berita.php?id=<?php echo $popular_row['id']; ?>" class="link-wrap"><?php echo $popular_row['judul']; ?></a></h3>
                                                <div class="entry-description color-dark-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;"><?php echo $popular_row['isi']; ?></div>
                                                <ul class="entry-meta color-dark-2 mt-2">
                                                    <li class="post-author">
                                                        <a href="#">
                                                            By : <?php echo $popular_row['username']; ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5" d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z" stroke="#1C274C" stroke-width="1.5"/>
                                    <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="#1C274C" stroke-width="1.5"/>
                                    </svg><?php echo $popular_row['views']; ?> Views
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <footer class="footer footer1 mt-5">
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
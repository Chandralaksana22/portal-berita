<?php
require_once 'config/auth.php';
require_once 'config/db.php';
redirect_if_not_logged_in();

if (!is_publisher()) {
    header("Location: index.html");
    exit();
}

// Ambil data notifikasi
$query_get_notifikasi = "SELECT * FROM notifikasi WHERE user_id = " . get_user_id() . " ORDER BY timestamp DESC";
$result_get_notifikasi = $mysqli->query($query_get_notifikasi);

// Set notifikasi menjadi dibaca
$query_set_read = "UPDATE notifikasi SET status = 'read' WHERE user_id = " . get_user_id();
$mysqli->query($query_set_read);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Publisher Dashboard</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Atlantis Lite - Bootstrap 4 Admin Dashboard</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['assets/css/fonts.min.css']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/atlantis.min.css">

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css">
    <!-- Tambahkan stylesheet di sini -->
    <script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }

        textarea {
            width: 100%;
            height: 150px;
        }

        h3 {
            margin-top: 20px;
        }

        p {
            margin-bottom: 5px;
        }

        .referensi-container {
            margin-top: 10px;
        }
    </style>

</head>
<div class="wrapper">
    <?php include 'components/navigation.php'; ?>
    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-primary-gradient">
                <div class="page-inner py-5">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="text-white pb-2 fw-bold">Dashboard Publisher</h2>
                            <h5 class="text-white op-7 mb-2">Notifications</h5>
                        </div>
                        <div class="ml-md-auto py-2 py-md-0">
                            <a href="components/logout.php" class="btn btn-secondary btn-round">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-inner mt--5">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Notifications</h4>
                            </div>
                            <div class="card-body">
                                <?php if ($result_get_notifikasi->num_rows > 0) : ?>
                                    <?php while ($row_notifikasi = $result_get_notifikasi->fetch_assoc()) : ?>
                                        <div class="notifikasi <?= $row_notifikasi['status'] ?>">
                                            <p><?= $row_notifikasi['pesan'] ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <p>Tidak ada notifikasi.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="copyright ml-auto">
                    2024, made with <i class="fa fa-heart heart text-danger"></i> Bootstrap
                </div>
            </div>
        </footer>
    </div>
</div>
<style>
        h1 {
            margin-top: 20px;
        }

        .notifikasi-container {
            margin-top: 10px;
        }

        .notifikasi {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .notifikasi.unread {
            background-color: #f2f2f2;
        }
    </style>
</html>
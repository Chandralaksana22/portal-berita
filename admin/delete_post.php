<?php
require_once 'config/auth.php';
require_once 'config/db.php';

redirect_if_not_logged_in();

if (!is_admin()) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Query untuk mendapatkan informasi postingan yang akan dihapus
    $query_get_post = "SELECT * FROM posts WHERE id=$post_id";
    $result_get_post = $mysqli->query($query_get_post);

    // Periksa apakah query berhasil dieksekusi
    if (!$result_get_post) {
        die("Query failed: " . $mysqli->error);
    }

    // Periksa apakah postingan ditemukan
    if ($result_get_post->num_rows === 0) {
        // Redirect jika postingan tidak ditemukan
        header("Location: admin.php");
        exit();
    }

    $post = $result_get_post->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Pastikan $post_id sudah didefinisikan
    if (!isset($_GET['id'])) {
        die("Error: Post ID not defined");
    }

    $post_id = $_GET['id'];

    // Query untuk menghapus postingan
    $query_delete_post = "DELETE FROM posts WHERE id=$post_id";
    $result_delete_post = $mysqli->query($query_delete_post);

    // Periksa apakah query berhasil dieksekusi
    if (!$result_delete_post) {
        die("Query failed: " . $mysqli->error);
    }

    // Redirect ke halaman admin dengan notifikasi
    header("Location: index.php?message=Postingan berhasil dihapus");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
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
</head>

<body>

</body>

<body>
    <div class="wrapper">
        <?php include 'components/navigation.php'; ?>
        <div class="wrapper">
            <?php include 'components/navigation.php'; ?>
            <?php if (!empty($message)) : ?>
                <p style="color: green;"><?= $message ?></p>
            <?php endif; ?>
            <div class="main-panel">
                <div class="content">
                    <div class="panel-header bg-primary-gradient">
                        <div class="page-inner py-5">
                            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                                <div>
                                    <h2 class="text-white pb-2 fw-bold">Dashboard Admin</h2>
                                    <h5 class="text-white op-7 mb-2">Detail</h5>
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
                                        <h4 class="card-title">Detail Post</h4>
                                    </div>
                                    <div class="card-body">
                                        <h1>Delete Postingan</h1>
                                        <h3>Anda yakin ingin menghapus postingan ini?</h3>
                                        <h4>Judul: <?= $post['judul'] ?></h4>

                                        <form method="post" action="delete_post.php?id=<?= $post_id ?>">
                                            <button class="btn btn-danger" type="submit" name="confirm_delete">Hapus Postingan</button>
                                        </form>
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
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
    <script src="assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Atlantis JS -->
    <script src="assets/js/atlantis.min.js"></script>

    <!-- Atlantis DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({});

            $('#multi-filter-select').DataTable({
                "pageLength": 5,
                initComplete: function() {
                    this.api().columns().every(function() {
                        var column = this;
                        var select = $('<select class="form-control"><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search(val ? '^' + val + '$' : '', true, false)
                                    .draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }
            });

            // Add Row
            $('#add-row').DataTable({
                "pageLength": 5,
            });

            var action = '<td> <div class="form-button-action"> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $('#addRowButton').click(function() {
                $('#add-row').dataTable().fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action
                ]);
                $('#addRowModal').modal('hide');

            });
        });
    </script>
</body>

</html>
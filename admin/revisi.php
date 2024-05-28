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

    // Ambil data postingan yang perlu direvisi
    $query_get_post = "SELECT * FROM posts WHERE id=?";

    $stmt_get_post = $mysqli->prepare($query_get_post);
    $stmt_get_post->bind_param("i", $post_id);
    $stmt_get_post->execute();
    $result_get_post = $stmt_get_post->get_result();

    if ($result_get_post->num_rows === 1) {
        $row_post = $result_get_post->fetch_assoc();
        $referensi_array = explode(',', $row_post['referensi']);
        $catatan_referensi_array = explode(',', $row_post['catatan_referensi']);
    } else {
        // Redirect jika postingan tidak ditemukan
        header("Location: admin.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses form submission untuk revisi
    $post_id = $_POST['post_id'];
    $catatan_judul = $_POST['catatan_judul'];
    $catatan_isi = $_POST['catatan_isi'];
    $catatan_referensi_array = $_POST['catatan_referensi'];

    // Gabungkan catatan referensi menjadi satu string dengan pemisah koma
    $catatan_referensi = implode(',', $catatan_referensi_array);

    // Update status postingan menjadi 'revision' dan simpan catatan
    $query_update_post = "UPDATE posts SET status='revision', catatan_judul=?, catatan_isi=?, catatan_referensi=? WHERE id=?";

    $stmt_update_post = $mysqli->prepare($query_update_post);
    $stmt_update_post->bind_param("sssi", $catatan_judul, $catatan_isi, $catatan_referensi, $post_id);
    $stmt_update_post->execute();

    // Ambil publisher_id dari tabel posts
    $query_get_publisher_id = "SELECT publisher_id FROM posts WHERE id=?";

    $stmt_get_publisher_id = $mysqli->prepare($query_get_publisher_id);
    $stmt_get_publisher_id->bind_param("i", $post_id);
    $stmt_get_publisher_id->execute();
    $result_get_publisher_id = $stmt_get_publisher_id->get_result();

    if (!$result_get_publisher_id) {
        echo 'Query error: ' . $stmt_get_publisher_id->error;
        exit();
    }

    $row_publisher_id = $result_get_publisher_id->fetch_assoc();
    $publisher_id = $row_publisher_id['publisher_id'];

    // Tambahkan notifikasi untuk publisher
    $pesan_notifikasi_publisher = "Postingan Anda membutuhkan revisi dari admin.";
    $query_insert_notifikasi_publisher = "INSERT INTO notifikasi (user_id, pesan, status) VALUES (?, ?, 'unread')";

    $stmt_insert_notifikasi_publisher = $mysqli->prepare($query_insert_notifikasi_publisher);
    $stmt_insert_notifikasi_publisher->bind_param("is", $publisher_id, $pesan_notifikasi_publisher);
    $stmt_insert_notifikasi_publisher->execute();

    // Redirect ke halaman admin dengan notifikasi
    header("Location: index.php?message=Postingan membutuhkan revisi");
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
                                        <?php if (isset($row_post)) : ?>
                                            <form method="post" action="revisi.php">
                                                <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                                <div class="form-group">
                                                    <label for="disableinput">Judul</label>
                                                    <input type="text" name="judul" class="form-control" value="<?= $row_post['judul'] ?>" id="disableinput" placeholder="Enter Input" disabled="">
											    </div>
                                                <br>
                                                <div class="form-group">
                                                    <label for="disableinput">Catatan Untuk Judul</label>
                                                    <input type="text" name="catatan_judul" class="form-control">
											    </div>
                                                <br>
                                                <div class="form-group">
                                                    <label for="comment">Isi Berita</label>
                                                    
                                                    <div class="my-2">
                                                    <?= $row_post['isi'] ?>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="catatan_isi">Catatan untuk Isi Berita:</label>
                                                    <textarea class="form-control" rows="2" name="catatan_isi"></textarea>
                                                </div>
                                                <br>
                                                
                                                <?php foreach ($referensi_array as $index => $referensi) : ?>
                                                    <div class="form-group">
                                                    <label for="catatan_referensi">Catatan untuk Referensi <?= $index + 1 ?>:</label>
                                                    <input type="text" name="referensi[]" class="form-control" id="disableinput" placeholder="Enter Input" value="<?= $referensi ?>" readonly>
                                                    </div>
                                                    <br>
                                                    <div class="form-group">
                                                    <label for="catatan_referensi">Catatan untuk Referensi <?= $index + 1 ?>:</label>
                                                    <input type="text" name="catatan_referensi[]"  class="form-control">
                                                    <br>
                                                    </div>
                                                <?php endforeach; ?>
    
                                                

                                                <button class="btn btn-primary" type="submit">Kirim Revisi</button>
                                                <a class="btn btn-primary" href="approve.php?id=<?= $post_id ?>">Approve</a>
                                            </form>
                                        <?php else : ?>
                                            <p>Postingan tidak ditemukan.</p>
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
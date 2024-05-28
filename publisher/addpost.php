<?php
require_once 'config/auth.php';
require_once 'config/db.php';

redirect_if_not_logged_in();

if (!is_publisher()) {
    header("Location: index.html");
    exit();
}

$publisher_id = $_SESSION['user_id'];

// Query untuk mendapatkan postingan yang dibuat oleh publisher
$query_posts = "SELECT * FROM posts WHERE publisher_id=$publisher_id ORDER BY id DESC";
$result_posts = $mysqli->query($query_posts);

// Periksa apakah query berhasil dieksekusi
if (!$result_posts) {
    die("Query failed: " . $mysqli->error);
}
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
    <script>
        function addReference() {
            // Buat elemen input untuk referensi baru
            var input = document.createElement("input");
            input.type = "text";
            input.name = "referensi[]";
            input.className = "form-control form-control my-4";

            // Buat tombol untuk menghapus referensi
            var removeButton = document.createElement("button");
            removeButton.textContent = "Remove";
            removeButton.type = "button";
            removeButton.className = "btn btn-danger";
            removeButton.onclick = function() {
                // Hapus elemen referensi saat tombol di klik
                this.parentElement.remove();
            };

            // Buat elemen div untuk menampung input dan tombol remove
            var div = document.createElement("div");
            div.appendChild(input);
            div.appendChild(removeButton);

            // Tambahkan elemen div ke dalam container referensi
            document.getElementById("referensi-container").appendChild(div);
        }
    </script>
</head>

<body>
    <div class="wrapper">
        <?php include 'components/navigation.php'; ?>
        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">Dashboard Publisher</h2>
                                <h5 class="text-white op-7 mb-2">Add Post</h5>
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
                                    <h4 class="card-title">Add Post</h4>
                                </div>
                                <div class="card-body">
                                    <form method="post" action="create_post.php" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="largeInput">Title</label>
                                            <input type="text" name="judul" class="form-control form-control" id="defaultInput" placeholder="Title">
                                        </div>
                                        <div class="form-group">
                                        <label for="gambar">Content</label>
                                        <input type="file" name="gambar" accept="image/*">
                                        </div>
                                        <div class="form-group">
                                        <label for="isi">Content</label>
                                        <textarea class="ckeditor" id="ckedtor" name="isi" id="isi" name="isi" required></textarea>
                                        </div>

                                        <div class="form-group">
                                        <label for="referensi">Referensi:</label>
                                        <div id="referensi-container">
                                            <!-- Container untuk menampung input referensi -->
                                            <input class="form-control form-control" type="text" name="referensi[]">
                                            <br>
                                            <button class="btn btn-primary" type="button" onclick="addReference()">Add More</button>
                                        </div>
                                        <br>
                                        </div>

                                        <div class="form-group">
                                        <button class="btn btn-success" type="submit">Add Post</button>
                                        </div>
                                        
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
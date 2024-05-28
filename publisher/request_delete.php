<?php
require_once 'config/auth.php';
require_once 'config/db.php';

redirect_if_not_logged_in();

if (!is_publisher()) {
    header("Location: index.html");
    exit();
}

$publisher_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Pastikan postingan milik publisher yang sedang login
    $query_check_owner = "SELECT * FROM posts WHERE id=$post_id AND publisher_id=$publisher_id";
    $result_check_owner = $mysqli->query($query_check_owner);

    // Periksa apakah query berhasil dieksekusi
    if (!$result_check_owner) {
        die("Query failed: " . $mysqli->error);
    }

    // Periksa apakah postingan ditemukan
    if ($result_check_owner->num_rows === 0) {
        // Redirect jika postingan bukan milik publisher yang sedang login
        header("Location: publisher.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Pastikan $post_id sudah didefinisikan
    if (!isset($_GET['id'])) {
        die("Error: Post ID not defined");
    }

    $post_id = $_GET['id'];

    // Kirim permintaan penghapusan ke admin
    $query_request_delete = "UPDATE posts SET status='request_delete' WHERE id=$post_id";
    $result_request_delete = $mysqli->query($query_request_delete);

    // Periksa apakah query berhasil dieksekusi
    if (!$result_request_delete) {
        die("Query failed: " . $mysqli->error);
    }

    // Redirect ke halaman publisher dengan notifikasi
    header("Location: index.php?message=Permintaan penghapusan telah dikirim");
    exit();
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

            // Buat tombol untuk menghapus referensi
            var removeButton = document.createElement("button");
            removeButton.textContent = "Remove";
            removeButton.type = "button";
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
                                <h5 class="text-white op-7 mb-2">Request Delete</h5>
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
                                    <h4 class="card-title">Request Delete</h4>
                                </div>
                                <div class="card-body">
                                    <h1>Request Delete Postingan</h1>
                                    <p>Anda yakin ingin mengirim permintaan penghapusan untuk postingan ini?</p>
                                    <form method="post" action="request_delete.php?id=<?= $post_id ?>">
                                        <button class="btn btn-danger mb-5" type="submit" name="confirm_delete">Kirim Permintaan Penghapusan</button>
                                    </form>
                                    <p><a class="btn btn-primary" href="index.php">Kembali ke Dashboard Publisher</a></p>
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


</body>

</html>
<?php
require_once 'config/auth.php';
require_once 'config/db.php';

redirect_if_not_logged_in();

if (!is_publisher()) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Ambil data postingan yang akan diedit
    $query_get_post = "SELECT * FROM posts WHERE id=$post_id";
    $result_get_post = $mysqli->query($query_get_post);

    if ($result_get_post->num_rows === 1) {
        $row_post = $result_get_post->fetch_assoc();
        $catatan_admin_judul = $row_post['catatan_judul'];
        $catatan_admin_isi = $row_post['catatan_isi'];
        $referensi_array = explode(',', $row_post['referensi']);
        $catatan_admin_referensi_array = explode(',', $row_post['catatan_referensi']);
    } else {
        // Redirect jika postingan tidak ditemukan
        header("Location: publisher.php");
        exit();
    }
}

// Inisialisasi referensi dan catatan referensi
$referensi_count = count($referensi_array);
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
    <script>
        function addReferensi() {
            var referensiContainer = document.getElementById('referensi-container');
            var newReferensiInput = document.createElement('input');
            newReferensiInput.type = 'text';
            newReferensiInput.name = 'referensi[]';
            newReferensiInput.value = '';
            newReferensiInput.className = "form-control form-control my-4";
            newReferensiInput.required = true;

            referensiContainer.appendChild(newReferensiInput);
            referensiContainer.appendChild(document.createElement('br'));

            // Tambahkan juga kolom catatan referensi
        }

        function removeReferensi() {
            var referensiContainer = document.getElementById('referensi-container');
            var referensiInputs = referensiContainer.getElementsByTagName('input');
            var catatanReferensiInputs = referensiContainer.getElementsByTagName('textarea');

            if (referensiInputs.length > 1) {
                referensiContainer.removeChild(referensiInputs[referensiInputs.length - 1]);
                referensiContainer.removeChild(referensiContainer.lastElementChild); // remove <br>
                referensiContainer.removeChild(catatanReferensiInputs[catatanReferensiInputs.length - 1]);
                referensiContainer.removeChild(referensiContainer.lastElementChild); // remove <br>
            }
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
                                <h5 class="text-white op-7 mb-2">Detail Post</h5>
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
                                        <form method="post" action="update_post.php">
                                            <input type="hidden" name="post_id" value="<?= $post_id ?>">

                                            <label for="judul">Title:</label>
                                            <input class="form-control" type="text" name="judul" value="<?= $row_post['judul'] ?>">
                                            <br>
                                            <label for="judul">Note:</label>
                                            <input class="form-control" type="text" name="judul" value="<?= $catatan_admin_judul ?>" disabled>
                                            <br>
                                            <br>
                                            <label for="isi">Content:</label>
                                            <textarea class="ckeditor" id="ckedtor" name="isi" required><?= $row_post['isi'] ?></textarea>
                                            <br>
                                            <label for="judul">Note:</label>
                                            <input class="form-control" type="text" name="judul" value="<?= $catatan_admin_isi ?>" disabled>
                                            <br>
                                            <!-- Tampilkan kolom referensi -->
                                            <label for="referensi">Referensi:</label>
                                            <div id="referensi-container" class="referensi-container">
                                                <?php for ($i = 0; $i < $referensi_count; $i++) : ?>
                                                    <?php if (isset($referensi_array[$i]) && isset($catatan_admin_referensi_array[$i])) : ?>
                                                        <input class="form-control" type="text" name="referensi[]" value="<?= $referensi_array[$i] ?>" required>
                                                        <br>
                                                        <label for="catatan_referensi[]">Note:</label>
                                                        <input class="form-control" name="catatan_referensi[]" value="<?= $catatan_admin_referensi_array[$i] ?>" required disabled>
                                                        <br>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </div>

                                            <button type="button" class="btn btn-primary" onclick="addReferensi()">Tambah Referensi</button>
                                            <button type="button" class="btn btn-danger" onclick="removeReferensi()">Hapus Referensi</button>
                                            <br>
                                            <br>
                                            <!-- Tampilkan catatan admin
                                            <h3>Catatan Admin:</h3>
                                            <p>Catatan untuk Judul: <?= $catatan_admin_judul ?></p>
                                            <p>Catatan untuk Isi Berita: <?= $catatan_admin_isi ?></p> -->

                                            <button type="submit" class="btn btn-success">Update Postingan</button>
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


</body>

</html>
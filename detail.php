<?php
require_once 'db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = $_GET['id'];

    // Ambil data berita dari database
    $query_detail = "SELECT * FROM posts WHERE id = $post_id";
    $result_detail = $mysqli->query($query_detail);

    if ($result_detail->num_rows === 1) {
        $row_detail = $result_detail->fetch_assoc();
    } else {
        // Redirect jika berita tidak ditemukan
        header("Location: index.php");
        exit();
    }
} else {
    // Redirect jika parameter ID tidak valid
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Berita</title>
    <!-- Tambahkan stylesheet di sini -->
</head>
<body>
    <h1><?= $row_detail['judul'] ?></h1>
    <p><?= $row_detail['isi'] ?></p>
    <p>Referensi: <?= $row_detail['referensi'] ?></p>
    <p>Views: <?= $row_detail['views'] ?></p>
</body>
</html>

<?php
require_once 'config/auth.php';
require_once 'config/db.php';

redirect_if_not_logged_in();

if (!is_publisher()) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $referensi = isset($_POST['referensi']) ? $_POST['referensi'] : [];
    $publisher_id = get_user_id();

    // File upload handling
    $uploadDir = '../assets/img/'; // Directory where you want to store uploaded images
    $uploadFile = $uploadDir . basename($_FILES['gambar']['name']);

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadFile)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "File upload failed.\n";
    }

    // Buat postingan baru
    $query_create_post = "INSERT INTO posts (publisher_id, judul, isi, referensi, gambar, status) VALUES (?, ?, ?, ?, ?, 'pending')";

    $stmt_create_post = $mysqli->prepare($query_create_post);

    if (!$stmt_create_post) {
        echo 'Query error: ' . $mysqli->error;
        exit();
    }

    $referensi_string = implode(',', $referensi);
    $gambar = $_FILES['gambar']['name']; // Add the file name to the query
    $stmt_create_post->bind_param("issss", $publisher_id, $judul, $isi, $referensi_string, $gambar);
    $result_create_post = $stmt_create_post->execute();

    if (!$result_create_post) {
        echo 'Query error: ' . $stmt_create_post->error;
        exit();
    }

    $pesan_notifikasi = "Postingan baru dengan judul '$judul' telah dibuat dan memerlukan review dari admin.";
    $query_insert_notifikasi = "INSERT INTO notifikasi (user_id, pesan, status) VALUES (?, ?, 'unread')";

    $stmt_insert_notifikasi = $mysqli->prepare($query_insert_notifikasi);
    $stmt_insert_notifikasi->bind_param("is", $publisher_id, $pesan_notifikasi);
    $result_insert_notifikasi = $stmt_insert_notifikasi->execute();

    if (!$result_insert_notifikasi) {
        echo 'Query error: ' . $stmt_insert_notifikasi->error;
        exit();
    }

    header("Location: index.php");
    exit();
}
?>

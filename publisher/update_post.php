<?php
require_once 'config/auth.php';
require_once 'config/db.php';
redirect_if_not_logged_in();

if (!is_publisher()) {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $referensi = implode(',', $_POST['referensi']);
    $catatan_referensi = implode(',', $_POST['catatan_referensi']);

    // Simpan perubahan pada postingan
    $query_update_post = "UPDATE posts SET judul=?, isi=?, referensi=?, catatan_referensi=?, status='pending' WHERE id=?";
    
    $stmt = $mysqli->prepare($query_update_post);
    $stmt->bind_param("ssssi", $judul, $isi, $referensi, $catatan_referensi, $post_id);
    
    $result_update_post = $stmt->execute();
    
    if (!$result_update_post) {
        echo 'Query error: ' . $stmt->error;
        exit();
    }

    // Ambil id penerbit postingan
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

    // Tambahkan notifikasi
    $pesan_notifikasi = "Postingan dengan judul '$judul' memerlukan review dari admin.";
    $query_insert_notifikasi = "INSERT INTO notifikasi (user_id, pesan, status) VALUES (?, ?, 'unread')";
    
    $stmt_insert_notifikasi = $mysqli->prepare($query_insert_notifikasi);
    $stmt_insert_notifikasi->bind_param("is", $row_publisher_id['publisher_id'], $pesan_notifikasi);
    $result_insert_notifikasi = $stmt_insert_notifikasi->execute();

    if (!$result_insert_notifikasi) {
        echo 'Query error: ' . $stmt_insert_notifikasi->error;
        exit();
    }

    header("Location: index.php");
    exit();
}
?>
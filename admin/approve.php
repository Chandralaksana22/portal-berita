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

    // Set status postingan menjadi 'approved'
    $query = "UPDATE posts SET status='approved' WHERE id=?";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();

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
    $pesan_notifikasi_publisher = "Postingan Anda telah disetujui oleh admin.";
    $query_insert_notifikasi_publisher = "INSERT INTO notifikasi (user_id, pesan, status) VALUES (?, ?, 'unread')";
    
    $stmt_insert_notifikasi_publisher = $mysqli->prepare($query_insert_notifikasi_publisher);
    $stmt_insert_notifikasi_publisher->bind_param("is", $publisher_id, $pesan_notifikasi_publisher);
    $stmt_insert_notifikasi_publisher->execute();

    // Redirect ke halaman admin dengan notifikasi
    header("Location: index.php?message=Postingan telah disetujui");
    exit();
} else {
    header("Location: index.php");
    exit();
}

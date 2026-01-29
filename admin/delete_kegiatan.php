<?php 
session_start();
include '../dbconnect.php';

if (!isset($_SESSION['log']) || $_SESSION['log'] !== "Logged") {
    header("location:login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Pastikan ID yang diterima adalah angka
    if ($id > 0) {
        $query = "DELETE FROM kegiatan WHERE id = $id";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Kegiatan berhasil dihapus!');</script>";
            echo "<meta http-equiv='refresh' content='1; URL=index.php'>"; // Redirect ke halaman utama
        } else {
            echo "<script>alert('Gagal menghapus kegiatan!');</script>";
            echo "<meta http-equiv='refresh' content='1; URL=index.php'>";
        }
    } else {
        echo "<script>alert('ID tidak valid!');</script>";
        echo "<meta http-equiv='refresh' content='1; URL=index.php'>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!');</script>";
    echo "<meta http-equiv='refresh' content='1; URL=index.php'>";
}
?>

<?php
include '../dbconnect.php';
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM disfo_login WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Admin berhasil dihapus!'); window.location.href='newadm.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus admin!');</script>";
    }
}
?>

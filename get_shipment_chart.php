<?php
// session_start();
include ('dbconnect.php');

// Fetch data dari database untuk chart
$query = mysqli_query($conn, "
    SELECT tgl_pelaksanaan, SUM(tempat) as total_stock FROM kegiatan GROUP BY tgl_pelaksanaan ORDER BY tgl_pelaksanaan ASC
") or die(mysqli_error($conn));
$stock = [];
$tgl= [];

while ($row = mysqli_fetch_assoc($query)) {
    $tgl[] = $row['tgl_pelaksanaan'];
    $stock[] = (int)$row['total_stock'];
}
echo json_encode([
    "tgl" => $tgl,
    "stok"     => $stock
]);
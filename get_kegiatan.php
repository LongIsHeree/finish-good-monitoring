<?php
include 'dbconnect.php';

function formatTanggalIndonesia($date) {
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $pecahkan = explode('-', $date);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

$query = "SELECT nama_pelatihan, kerjasama, tempat, tgl_pelaksanaan FROM kegiatan ORDER BY tgl_pelaksanaan ASC";
$result = mysqli_query($conn, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $formatted_date = formatTanggalIndonesia($row['tgl_pelaksanaan']);
        $data[] = [
            'nama_pelatihan' => htmlspecialchars($row['nama_pelatihan']),
            'kerjasama' => htmlspecialchars($row['kerjasama']),
            'tempat' => htmlspecialchars($row['tempat']),
            'tgl_pelaksanaan' => $formatted_date
        ];
    }
} 
mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($data);
?>

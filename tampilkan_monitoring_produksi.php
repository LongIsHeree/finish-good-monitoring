<?php
include 'dbconnect.php';

// Fungsi untuk mengubah bulan menjadi bahasa Indonesia
function formatTanggalIndonesia($date) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $pecahkan = explode('-', $date);
    // Format menjadi Tanggal Bulan Tahun
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}


function get_output_qc_endline_yesterday($tgl){
    global $conn_produksi;

    $date = new DateTime($tgl);
    $date->modify('-1 day');
    $yesterday = $date->format('Y-m-d');

    $query = "SELECT 
                line, 
                SUM(qty) AS Output_Yesterday
              FROM transaksi_qc_endline
              WHERE tanggal = '$yesterday'
              GROUP BY line
              ORDER BY line";
    return mysqli_query($conn_produksi, $query);
}

function get_output_qc_endline($tgl){
      global $conn_produksi;

    $query = "SELECT 
                line, 
                SUM(qty) AS Output_Today
              FROM transaksi_qc_endline
              WHERE tanggal = '$tgl'
              GROUP BY line
              ORDER BY line";

    return mysqli_query($conn_produksi, $query);
}

function fetch_assoc_all($result){
    $rows = [];
    while($r = mysqli_fetch_assoc($result)){
        $rows[] = $r;
    }
    return $rows;
}
$tgl = '2026-01-23';
// echo "DB: ";
// echo mysqli_fetch_row(mysqli_query($conn_produksi,"SELECT DATABASE()"))[0];
// die();

// echo $tgl;
// die();
$today = fetch_assoc_all(get_output_qc_endline($tgl));
$yesterday = fetch_assoc_all(get_output_qc_endline_yesterday($tgl));


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>SEWING MONITORING</title>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
    .main-header {
        position: relative;
        background: #0a0f1c;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-right: 40px;
        overflow: hidden;
    }

    /* Garis neon jalan */
    .header-line {
        position: absolute;
        bottom: 0;
        left: -50%;
        width: 50%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #00f7ff, #00f7ff, transparent);
        box-shadow: 0 0 10px #00f7ff, 0 0 20px #00f7ff;
        animation: moveLine 3s linear infinite;
    }

    @keyframes moveLine {
        0% {
            left: -50%;
        }

        100% {
            left: 100%;
        }
    }

    /* Glow logo & lottie */
    .main-header img {
        filter: drop-shadow(0 0 6px #00f7ff);
    }

    .main-header lottie-player {
        filter: drop-shadow(0 0 8px #00f7ff);
    }

    /* ================= FOOTER NEON ================= */
    .main-footer {
        background: #0a0f1c;
        color: #00f7ff;
        position: relative;
        overflow: hidden;
        border-top: 1px solid rgba(0, 255, 255, 0.2);
    }

    .main-footer .marquee-text {
        text-shadow: 0 0 5px #00f7ff, 0 0 10px #00f7ff;
    }

    /* Garis neon atas footer */
    .main-footer::before {
        content: "";
        position: absolute;
        top: 0;
        left: -50%;
        width: 50%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #00f7ff, transparent);
        box-shadow: 0 0 10px #00f7ff;
        animation: footerLine 4s linear infinite;
    }

    @keyframes footerLine {
        0% {
            left: -50%;
        }

        100% {
            left: 100%;
        }
    }
    </style>
</head>

<body>
    <header class="main-header">
        <div class="logo" style="margin-left: 50px;">
            <a href="admin/login.php" target="_blank">
                <img src="assets/logo/gi.png" alt="Logo">
            </a>
        </div>


        <div class="header-line"></div>
    </header>
    <main>
        <section>
            <!-- Image Slider -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h3>
                                            <center><strong>Output Today </strong></center>
                                        </h3>
                                        <div id="todayChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h2><i class="fas fa-bullhorn"></i> SEWING & PACKING OUTPUT</h2>
                            <br>
                            <table id="outputTable" class="table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>NO</center>
                                        </th>
                                        <th>
                                            <center>Line</center>
                                        </th>
                                        <th>
                                            <center>Sewing Yesterday</center>
                                        </th>
                                        <th>
                                            <center>Sewing Today</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$no = 1;
$lines = [];

// Gabungkan semua line unik
foreach($today as $t) $lines[$t['line']] = true;
foreach($yesterday as $y) $lines[$y['line']] = true;

foreach(array_keys($lines) as $line){

    $today_qty = 0;
    foreach($today as $t){
        if($t['line'] == $line){
            $today_qty = $t['Output_Today'];
            break;
        }
    }

    $y_qty = 0;
    foreach($yesterday as $y){
        if($y['line'] == $line){
            $y_qty = $y['Output_Yesterday'];
            break;
        }
    }
?>
                                    
                                    <tr>
                                        <td ><center><?= $no++ ?></center></td>
                                        <td><center><?= $line ?></center></td>
                                        <td>
                                            <center><?= $y_qty ?></center>
                                        </td>
                                        <td>
                                            <center><?= $today_qty ?></center>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
    </main>

    <footer class="main-footer">
        <div class="date-time-container">
            <div id="day-date"></div>
            <div id="time"></div>
        </div>
        <div class="marquee-section">
            <lottie-player src="assets/lottie/animation.json" speed="1" style="width: 100px; height: 70px" loop autoplay
                direction="1" mode="normal" class="anim mx-auto">
            </lottie-player>

            <div class="marquee-text">PT. GLOBALINDO INTIMATES, MOHON MAAF DI LARANG MASUK KE AREA FINISH GOOD KECUALI
                YANG MEMILIKI HAK AKSES</div>

        </div>

    </footer>

    <?php
$chart_lines = [];
$chart_today = [];
$chart_yesterday = [];

$mapToday = [];
foreach($today as $t) $mapToday[$t['line']] = (int)$t['Output_Today'];

$mapYesterday = [];
foreach($yesterday as $y) $mapYesterday[$y['line']] = (int)$y['Output_Yesterday'];

$allLines = array_unique(array_merge(array_keys($mapToday), array_keys($mapYesterday)));
sort($allLines);

foreach($allLines as $l){
    $chart_lines[] = $l;
    $chart_today[] = $mapToday[$l] ?? 0;
    $chart_yesterday[] = $mapYesterday[$l] ?? 0;
}
?>


    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
    $('#outputTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[1, 'asc']], // sort berdasarkan line
        columnDefs: [
            { className: "text-center", targets: "_all" }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "→",
                previous: "←"
            },
            zeroRecords: "Tidak ada data ditemukan"
        }
    });
});
   const lines = <?= json_encode($chart_lines) ?>;
const todayData = <?= json_encode($chart_today) ?>;
const yesterdayData = <?= json_encode($chart_yesterday) ?>;

var options = {
    chart: {
        type: 'bar',
        height: 350
    },
    series: [
        {
            name: 'Yesterday',
            data: yesterdayData
        },
        {
            name: 'Today',
            data: todayData
        }
    ],
    xaxis: {
        categories: lines,
        title: { text: 'Line' }
    },
    yaxis: {
        title: { text: 'Output Qty' }
    },
    colors: ['#ff0000', '#008FFB'],
    plotOptions: {
        bar: { horizontal: false }
    }
};

var chart = new ApexCharts(document.querySelector("#todayChart"), options);
chart.render();

    // Fungsi untuk memperbarui waktu secara dinamis
    function updateTime() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Makassar',
            hour12: false
        };
        let timeString = now.toLocaleTimeString('id-ID', options);
        // Mengubah pemisah titik (.) menjadi titik dua (:)
        timeString = timeString.replace(/\./g, ':');
        document.getElementById('time').innerText = timeString;
    }

    // Set interval untuk memperbarui waktu setiap detik
    setInterval(updateTime, 1000);

    // Menampilkan hari, tanggal, bulan, dan tahun secara dinamis
    function updateDate() {
        const now = new Date();
        const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        ];
        const dayName = days[now.getDay()];
        const date = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();
        document.getElementById('day-date').innerText = `${dayName}, ${date} ${monthName} ${year}`;
    }

    updateDate(); // Panggil fungsi untuk memperbarui tanggal

    // Slider otomatis
    let currentIndex = 0;
    const images = document.querySelectorAll('.image-slider img');
    const totalImages = images.length;

    function showNextImage() {
        images[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % totalImages;
        images[currentIndex].classList.add('active');
    }

    setInterval(showNextImage, 3000); // Ganti gambar setiap 3 detik

    // Fungsi untuk memuat status video terbaru
    function loadVideoStatus() {
        fetch('admin/video_status.json')
            .then(response => response.json())
            .then(videoStatus => {
                let videoNameToDisplay = '';
                if (videoStatus['2.mp4'] === 'Active') {
                    videoNameToDisplay = '2.mp4';
                } else if (videoStatus['1.mp4'] === 'Active') {
                    videoNameToDisplay = '1.mp4';
                }

                const videoElement = document.getElementById('video');
                if (videoElement && videoElement.src.includes(videoNameToDisplay)) {
                    return; // Video sudah aktif
                }

                // Update video
                if (videoNameToDisplay) {
                    videoElement.src = 'assets/video/' + videoNameToDisplay;
                    videoElement.play(); // Play video baru
                } else {
                    videoElement.pause(); // Stop video jika tidak ada yang aktif
                    videoElement.src = ''; // Kosongkan sumber video
                }
            })
            .catch(error => console.error('Error fetching video status:', error));
    }

    // Cek status video setiap 3 detik
    setInterval(loadVideoStatus, 3000);
    </script>
</body>

</html>
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
$tgl = date('Y-m-d');
function get_output_qc_endline_yesterday($tgl){
  global $conn_produksi;
  $date = date_create($tgl);
  date_sub($date, date_interval_create_from_date_string("1 day"));
  $strDate = date_format($date, "Y-m-d");

  $query = "SELECT SUM(qty) AS Output_Yesterday, 'line' FROM `transaksi_qc_endline` WHERE tanggal='$strDate' GROUP BY line ORDER BY line";
  $rst = mysqli_query($conn_produksi, $query);
  return $rst;  
}
function get_output_qc_endline($tgl){
  global $conn_produksi;

  $query = "SELECT SUM(qty) AS Output_Today, 'line' 
            FROM view_transaksi_qc_endline WHERE tanggal='$tgl'  AND status='open' ORDER BY jam";

  $rst = mysqli_query($conn_produksi, $query);
  return $rst;

}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                                        <h3><center><strong>Output Yesterday</strong></center></h3>
                                        <div id="shipmentChart"></div>
                                    </div>
                                    <div class="col">
                                        <h3><center><strong>Output Today </strong></center></h3>
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
                                <table class="table-striped table-hover">
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
                                        <?php if (!empty($data)): ?>
                                        <?php foreach ($data as $row): ?>
                                        <tr>
                                            <td><?php echo $row['nama_pelatihan']; ?></td>
                                            <td><?php echo $row['kerjasama']; ?></td>
                                            <td>
                                                <center><?php echo $row['tempat']; ?></center>
                                            </td>
                                            <td><?php echo $row['tgl_pelaksanaan']; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="8">
                                                <center>Belum ada data Barang</center>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
    fetch("get_shipment_chart.php")
        .then(res => res.json())
        .then(data => {

            var options = {
                chart: {
                    type: 'bar',
                    height: 300,
                    width: 700
                },
                series: [{
                    name: 'Total Carton',
                    data: data.stok
                }],
                xaxis: {
                    title: {
                        text: 'Shipment Date'
                    },
                    categories: data.tgl
                },
                yaxis: {
                    title: {
                        text: 'Total Carton'
                    },
                },
                plotOptions: {
                    bar: {
                        distributed: true
                    }
                },
                colors: [
                    '#008FFB',
                    '#00E396',
                ],
            };

            var chart = new ApexCharts(document.querySelector("#shipmentChart"), options);
            chart.render();
        });

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
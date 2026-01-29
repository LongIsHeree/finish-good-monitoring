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

// Query untuk mengambil data kegiatan dan mengurutkan berdasarkan tanggal pelaksanaan
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

// Ambil status video dari file JSON
$statusFile = 'admin/video_status.json'; // Jalur ke file JSON
$videoStatus = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];

$videoNames = ['2.mp4', '1.mp4']; // Daftar nama video yang ingin diperiksa
$videoNameToDisplay = ''; // Variabel untuk menyimpan nama video yang akan ditampilkan

foreach ($videoNames as $videoName) {
    if (isset($videoStatus[$videoName]) && $videoStatus[$videoName] === 'Active') {
        $videoNameToDisplay = $videoName; // Simpan nama video yang aktif
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>DISFO | FINNISH GOOD</title>
     <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
    #unmuteButton:hover #volumeIcon {
        color: red;
        /* Ubah warna ikon menjadi merah saat hover */
}
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
    0% { left: -50%; }
    100% { left: 100%; }
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
    border-top: 1px solid rgba(0,255,255,0.2);
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
    0% { left: -50%; }
    100% { left: 100%; }
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
            --<div class="container">
                <div class="schedule">
                    <h2><i class="fas fa-bullhorn"></i> INFORMASI AREA FINISH GOOD</h2>
                    <br>
                    <table class="table-striped table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <center>NO PO</center>
                                </th>
                                <th>
                                    <center>Style</center>
                                </th>
                                <th>
                                    <center>Quantity Carton</center>
                                </th>
                                <th>
                                    <center>Date Shipment</center>
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
                                <td colspan="4">
                                    <center>Belum ada data Barang</center>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="gallery">
                    
                    <?php if ($videoNameToDisplay): ?>
                    <div class="video-container" style="position: relative; display: inline-block;">
                        <video id="video" src='assets/video/<?php echo htmlspecialchars($videoNameToDisplay); ?>'
                            autoplay loop muted></video>
                        <button id="unmuteButton"
                            style="position: absolute; bottom: 10px; right: 10px; background: none; border: none; cursor: pointer;">
                            <i class="fas fa-volume-up" style="font-size: 20px;" id="volumeIcon"></i>
                        </button>
                    </div>
                    <script>
                    // Menghapus atribut muted setelah video dimulai
                    document.getElementById('video').addEventListener('loadeddata', function() {
                        this.muted = true; // Tetap dimute saat dimuat
                    });

                    // Menambahkan event listener untuk tombol unmute
                    document.getElementById('unmuteButton').addEventListener('click', function() {
                        const video = document.getElementById('video');
                        const volumeIcon = document.getElementById('volumeIcon');
                        video.muted = !video.muted; // Toggle mute
                        volumeIcon.classList.toggle('fa-volume-up'); // Ganti ikon
                        volumeIcon.classList.toggle('fa-volume-mute'); // Ganti ikon
                    });
                    </script>
                    <?php else: ?>
                    <p>Video Tidak Aktif</p>
                    <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h3><strong>Shipment </strong></h3>
                            <div id="shipmentChart"></div>
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
            <lottie-player src="assets/lottie/animation.json" speed="1"
                    style="width: 100px; height: 70px" loop autoplay direction="1" mode="normal" class="anim mx-auto">
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
        chart: { type: 'bar',height : 350, width : 500 },
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
        yaxis : {
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
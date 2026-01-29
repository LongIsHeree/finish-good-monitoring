<?php 
session_start();
include '../dbconnect.php';

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Admin";

if ($_SESSION['log'] !== "Logged") {
    header("location:login.php");
    exit();
}

// Menyiapkan daftar video
$videoDir = '../assets/video/';
$videos = array_diff(scandir($videoDir), array('..', '.'));

// Proses update status video
if (isset($_POST['update_status'])) {
    $videoName = htmlspecialchars($_POST['video_name']);
    $status = htmlspecialchars($_POST['status']);

    // Simpan status video dalam file JSON
    $statusFile = 'video_status.json'; // Pastikan ini mengarah ke file yang benar
    $currentStatus = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];
    $currentStatus[$videoName] = $status;

    try {
        file_put_contents($statusFile, json_encode($currentStatus));
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Membaca status video dari file JSON
$statusFile = 'video_status.json'; // Pastikan ini mengarah ke file yang benar
$videoStatus = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];

// Menyediakan data dalam format HTML
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>FINISH GOOD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/metisMenu.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/css/typography.css">
    <link rel="stylesheet" href="assets/css/default-css.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="assets/js/vendor/modernizr-2.8.3.min.js"></script>
</head>
<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <div class="page-container">
        <!-- sidebar menu area start -->
        <?php include 'includes/sidebar.php'; ?>
        <!-- sidebar menu area end -->

        <div class="main-content">
            <!-- header area start -->
            <?php include 'includes/header.php'; ?>
            <!-- header area end -->

            <div class="main-content-inner">
                <div class="row mt-5 mb-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h2>Daftar Video</h2>
                                <div class="data-tables datatable-dark">
                                    <table id="dataTable4" class="display" style="width:80%">
                                        <thead class="GREEN">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Video</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($videos as $video) {
                                                $status = isset($videoStatus[$video]) ? $videoStatus[$video] : 'Inactive';
                                                ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo htmlspecialchars($video); ?></td>
                                                    <td><?php echo htmlspecialchars($status); ?></td>
                                                    <td>
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="video_name" value="<?php echo htmlspecialchars($video); ?>">
                                                            <select name="status" onchange="this.form.submit()">
                                                                <option value="Active" <?php echo $status == 'Active' ? 'selected' : ''; ?>>Active</option>
                                                                <option value="Inactive" <?php echo $status == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                            </select>
                                                            <input type="hidden" name="update_status" value="1">
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php 
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer area start -->
        <?php include 'includes/footer.php'; ?>
        <!-- Footer area end -->
    </div>

    <!-- Scripts -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/scripts.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable4').DataTable();
        });
    </script>
</body>
</html>
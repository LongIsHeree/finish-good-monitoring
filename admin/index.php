<?php 
session_start();
date_default_timezone_set("Asia/Makassar"); // Set timezone ke WITA
$date_now = date("Y-m-d");
include '../dbconnect.php';

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : "Admin";

if($_SESSION['log'] !== "Logged"){
    header("location:login.php");
    exit();
}

// Fungsi untuk memformat tanggal menjadi "Tanggal Nama Bulan Tahun" dalam bahasa Indonesia
function indonesian_date($date) {
    $bulan = array (
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
}

// Proses tambah kegiatan
if (isset($_POST['add_kegiatan'])) {
    $nama_pelatihan = htmlspecialchars($_POST['nama_pelatihan']);
    $kerjasama = htmlspecialchars($_POST['kerjasama']);
    $tempat = htmlspecialchars($_POST['tempat']);
    $tgl_pelaksanaan = htmlspecialchars($_POST['tgl_pelaksanaan']);

    $query = "INSERT INTO kegiatan (nama_pelatihan, kerjasama, tempat, tgl_pelaksanaan) VALUES ('$nama_pelatihan', '$kerjasama','$tempat', '$tgl_pelaksanaan')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Kegiatan berhasil ditambahkan!');</script>";
        echo "<br><meta http-equiv='refresh' content='3; URL=index.php'> You will be redirected to the form in 3 seconds";
    } else {
        echo "<script>alert('Gagal menambahkan kegiatan!');</script>";
    }
}

// Proses edit kegiatan
if (isset($_POST['edit_kegiatan'])) {
    $id = htmlspecialchars($_POST['id']);
    $nama_pelatihan = htmlspecialchars($_POST['edit_nama_pelatihan']);
    $kerjasama = htmlspecialchars($_POST['edit_kerjasama']);
    $tempat = htmlspecialchars($_POST['edit_tempat']);
    $tgl_pelaksanaan = htmlspecialchars($_POST['edit_tgl_pelaksanaan']);

    $query = "UPDATE kegiatan SET nama_pelatihan='$nama_pelatihan', kerjasama='$kerjasama', tempat='$tempat', tgl_pelaksanaan='$tgl_pelaksanaan' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Kegiatan berhasil diubah!');</script>";
        echo "<br><meta http-equiv='refresh' content='3; URL=index.php'> You will be redirected to the form in 3 seconds";
    } else {
        echo "<script>alert('Gagal mengubah kegiatan!');</script>";
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>INFORMASI FINISH GOOD</title>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
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
                                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                                    <h2>Tambah Barang</h2>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#addKegiatanModal">
                                        <i class="fas fa-plus-circle"></i> Tambah Barang
                                    </button>
                                </div>
                                <div class="data-tables datatable-dark">
                                    <table id="dataTable3" class="display" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>NO PO</th>
                                                <th>Style</th>
                                                <th>Quantity Carton</th>
                                                <th>Date Shipment</th>
                                              
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $query = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY id ASC");
                                            $no = 1;
                                            while ($row = mysqli_fetch_array($query)) {
                                                $id = $row['id'];
                                                $nama_pelatihan = htmlspecialchars($row['nama_pelatihan']);
                                                $kerjasama = htmlspecialchars($row['kerjasama']);
                                                $tempat = htmlspecialchars($row['tempat']);
                                                $tgl_pelaksanaan = $row['tgl_pelaksanaan'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $nama_pelatihan; ?></td>
                                                    <td><?php echo $kerjasama; ?></td>
                                                    <td><?php echo $tempat; ?></td>
                                                    <td><?php echo indonesian_date($tgl_pelaksanaan); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning editBtn" data-toggle="modal" data-target="#editKegiatanModal" 
                                                        data-id="<?php echo $id; ?>" 
                                                        data-nama_pelatihan="<?php echo $nama_pelatihan; ?>" 
                                                        data-kerjasama="<?php echo $kerjasama; ?>" 
                                                        data-tempat="<?php echo $tempat; ?>" 
                                                        data-tgl_pelaksanaan="<?php echo $tgl_pelaksanaan; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="delete_kegiatan.php?id=<?php echo $id; ?>" class="btn btn-sm btn-danger" title="Hapus Kegiatan" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
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

<!-- Modal for Add Kegiatan -->
<div class="modal fade" id="addKegiatanModal" tabindex="-1" role="dialog" aria-labelledby="addKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKegiatanModalLabel">Tambah Barang Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_pelatihan">No PO</label>
                        <input type="text" class="form-control" id="nama_pelatihan" name="nama_pelatihan" required>
                    </div>
                    <div class="form-group">
                        <label for="kerjasama">Style</label>
                        <input type="text" class="form-control" id="kerjasama" name="kerjasama" required>
                    </div>
                    <div class="form-group">
                        <label for="tempat">Quantity Carton</label>
                        <input type="text" class="form-control" id="tempat" name="tempat" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_pelaksanaan">Date Shimpent</label>
                        <input type="date" class="form-control" id="tgl_pelaksanaan" name="tgl_pelaksanaan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="add_kegiatan" class="btn btn-primary">Tambah Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Edit Kegiatan -->
<div class="modal fade" id="editKegiatanModal" tabindex="-1" role="dialog" aria-labelledby="editKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKegiatanModalLabel">Edit Baranga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nama_pelatihan">No PO</label>
                        <input type="text" class="form-control" id="edit_nama_pelatihan" name="edit_nama_pelatihan" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_kerjasama">Style</label>
                        <input type="text" class="form-control" id="edit_kerjasama" name="edit_kerjasama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_tempat">Quantity Carton</label>
                        <input type="text" class="form-control" id="edit_tempat" name="edit_tempat" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_tgl_pelaksanaan">Date Shipmnet</label>
                        <input type="date" class="form-control" id="edit_tgl_pelaksanaan" name="edit_tgl_pelaksanaan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" name="edit_kegiatan" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
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
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script src="assets/js/scripts.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTable3').DataTable();

            // Handle edit button click
        $('.editBtn').on('click', function() {
            var id = $(this).data('id');
            var nama_pelatihan = $(this).data('nama_pelatihan');
            var kerjasama = $(this).data('kerjasama');
            var tempat = $(this).data('tempat');
            var tgl_pelaksanaan = $(this).data('tgl_pelaksanaan');

            $('#edit_id').val(id);
            $('#edit_nama_pelatihan').val(nama_pelatihan);
            $('#edit_kerjasama').val(kerjasama);
            $('#edit_tempat').val(tempat);
            $('#edit_tgl_pelaksanaan').val(tgl_pelaksanaan);
        });
    });
</script>
</body>
</html>

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

if (isset($_POST['add_admin'])) {
    $new_username = htmlspecialchars($_POST['new_username']);
    $new_password = htmlspecialchars($_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepared statement untuk menambahkan admin baru
    $stmt = $conn->prepare("INSERT INTO disfo_login (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $new_username, $hashed_password);

    if ($stmt->execute()) {
        echo "<script>alert('Admin berhasil ditambahkan!');</script>";
        echo "<br><meta http-equiv='refresh' content='3; URL=newadm.php'> You will be redirected to the form in 3 seconds";
    } else {
        echo "<script>alert('Gagal menambahkan admin: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/ico" href="../favicon.ico">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>ADMIN FINISH GOOD</title>
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
                                    <h2>Tambah User</h2>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#addAdminModal">
                                        <i class="fas fa-user-plus"></i> New User
                                    </button>
                                </div>
                                <div class="data-tables datatable-dark">
                                    <table id="dataTable3" class="display" style="width:100%">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Password</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            // Mengambil data dari disfo_login
                                            $user = mysqli_query($conn, "SELECT * FROM disfo_login ORDER BY id ASC");
                                            $no = 1;
                                            while ($p = mysqli_fetch_array($user)) {
                                                $iduser = $p['id'];
                                                $uname = $p['username'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo htmlspecialchars($uname); ?></td>
                                                    <td>********</td>
                                                    <td>
                                                        <a href="delete_admin.php?id=<?php echo $iduser; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
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

    <!-- Modal for Add Admin -->
    <div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="addAdminModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="" autocomplete="off">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAdminModalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new_username">Username</label>
                            <input type="text" class="form-control" id="new_username" name="new_username" required autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="add_admin" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/metisMenu.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.slicknav.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/scripts.js"></script>

    <script>
        $(document).ready(function() {
            if (!$.fn.DataTable.isDataTable('#dataTable3')) {
                $('#dataTable3').DataTable({
                    responsive: true
                });
            }
        });
    </script>

    <!-- Reset modal -->
    <script>
        $('#addAdminModal').on('show.bs.modal', function (e) {
            $(this).find('input').val('');
        });
    </script>
</body>
</html>
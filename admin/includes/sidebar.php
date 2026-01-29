<!-- sidebar menu area start -->
<div class="sidebar-menu">
	<div class="sidebar-header">
		<a href="index.php"><img src="../assets/logo/gi.png" alt="logo" width="100%"></a>
	</div>
	<div class="main-menu">
		<div class="menu-inner">
			<nav>
				<ul class="metismenu" id="menu">
					<li class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">
						<a href="index.php"><i class="fas fa-chalkboard-teacher"></i><span>Kegiatan</span></a>
					</li>
					<li class="<?= (basename($_SERVER['PHP_SELF']) == 'video.php') ? 'active' : '' ?>">
						<a href="video.php"><i class="fas fa-book"></i><span>Pengaturan Media</span></a>
					</li>
					<li class="<?= (basename($_SERVER['PHP_SELF']) == 'newadm.php') ? 'active' : '' ?>">
						<a href="newadm.php"><i class="fas fa-user"></i><span>Pengaturan User</span></a>
					</li>
					<li>
						<a href="#" onclick="confirmLogout(event)"><i class="fas fa-power-off"></i><span>  Log Out</span></a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>

<script type="text/javascript">
	function confirmLogout(event) {
        event.preventDefault();  // Mencegah link dari langsung menavigasi halaman
        var userConfirmed = confirm("Anda ingin keluar?");
        if (userConfirmed) {
            window.location.href = 'logout.php';  // Arahkan ke logout.php jika pengguna mengklik "OK"
        }
    }
</script>


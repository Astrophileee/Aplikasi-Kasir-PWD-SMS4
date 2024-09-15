<div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <div class="logo" type="button">
                <i class="fa-solid fa-store"></i>
                </div>
                <div class="sidebar-logo">
                    <a href="#">Aplikasi Point Of Sale</a>
                </div>
            </div>
            <ul class="sidebar-nav">
            <li class="sidebar-item">
                    <a href="/kasir/view/admin/user/dataUser.php" class="sidebar-link">
                        Kasir : <?= ucwords(strtolower($userAktif['nama'])); ?>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/kasir/view/dashboard.php" class="sidebar-link">
                        <i class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="/kasir/view/admin/barang/index.php" class="sidebar-link">
                        <i class="fa-solid fa-box"></i>
                        <span>Data Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Transaksi</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="/kasir/view/admin/penjualan/penjualan.php" class="sidebar-link">Penjualan</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="/kasir/view/admin/penjualan/laporanPenjualan.php" class="sidebar-link">Laporan Penjualan</a>
                        </li>
                    </ul>
                    <?php if ($_SESSION['user']['level'] == 1): ?>
                        <li class="sidebar-item">
                            <a href="/kasir/view/admin/user/dataUser.php" class="sidebar-link">
                                <i class="fa-solid fa-users"></i>
                                <span>Data User</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </li>
                <li class="sidebar-item">
                    <a href="/kasir/view/admin/user/logout.php" class="sidebar-link">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>
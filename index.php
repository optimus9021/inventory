<?php

require 'function.php';
session_start();

// Cek apakah user sudah login atau belum
if(!isset($_SESSION['log']) || $_SESSION['log'] !== "True") {
    // Jika belum login, redirect ke login.php
    header('location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Inventory Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Inventory Stock</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="listStock.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                                Stock
                            </a>
                            <div class="sb-sidenav-menu-heading">Database</div>
                            <a class="nav-link" href="listSupplier.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                List Supplier
                            </a>
                            <a class="nav-link" href="listProduk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                List Produk
                            </a>
                            <a class="nav-link" href="listToko.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-store"></i></div>
                                List Toko
                            </a>
                            <div class="sb-sidenav-menu-heading">Transaksi</div>
                            <a class="nav-link" href="listMasuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-dolly"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="listKeluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-dolly"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link" href="listPembayaran.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div>
                                Penjualan
                            </a>
                            <a class="nav-link" href="listRetur.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-undo"></i></div>
                                Retur
                            </a>
                        </div>
                    </div>                
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                <div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <!-- Query untuk total stock -->
    <?php
    $resultStock = mysqli_query($conn, "SELECT SUM(stock) as totalStock FROM stock");
    $totalStock = mysqli_fetch_assoc($resultStock)['totalStock'];

    // Query untuk barang keluar bulan ini
    $resultKeluar = mysqli_query($conn, "SELECT SUM(qty) as barangKeluar FROM keluar WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())");
    $barangKeluar = mysqli_fetch_assoc($resultKeluar)['barangKeluar'];

    // Query untuk total pembayaran lunas
    $resultPembayaranLunas = mysqli_query($conn, "SELECT COUNT(*) as pembayaranLunas FROM pembayaran WHERE idtagihan IS NOT NULL");
    $pembayaranLunas = mysqli_fetch_assoc($resultPembayaranLunas)['pembayaranLunas'];

    // Query untuk tagihan belum lunas
    $resultBelumLunas = mysqli_query($conn, "SELECT COUNT(*) as belumLunas FROM tagihan WHERE status = 'Belum Lunas'");
    $belumLunas = mysqli_fetch_assoc($resultBelumLunas)['belumLunas'];
    ?>

    <!-- Widget for total stock -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
        <a href="listStock.php" style="text-decoration: none;">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    Total Stock Barang
                    <h2><?php echo $totalStock; ?></h2>
                </div>
            </div>
        </a>
        </div>
        <div class="col-xl-3 col-md-6">
        <a href="listKeluar.php" style="text-decoration: none;">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    Barang Keluar Bulan Ini
                    <h2><?php echo $barangKeluar; ?></h2>
                </div>
            </div>
        </a>
        </div>
        <div class="col-xl-3 col-md-6">
        <a href="listPembayaran.php" style="text-decoration: none;">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    Pembayaran Lunas
                    <h2><?php echo $pembayaranLunas; ?></h2>
                </div>
            </div>
        </a>
        </div>
        <div class="col-xl-3 col-md-6">
        <a href="listPembayaran.php" style="text-decoration: none;">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    Tagihan Belum Lunas
                    <h2><?php echo $belumLunas; ?></h2>
                </div>
            </div>
        </a>
        </div>
    </div>

    <!-- Chart for incoming and outgoing items -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Grafik Barang Masuk vs Barang Keluar
                </div>
                <div class="card-body">
                    <canvas id="barChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Latest payment log -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Log Pembayaran Terakhir
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal Pembayaran</th>
                                <th>Supplier</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query untuk log pembayaran terakhir
                            $resultLogPembayaran = mysqli_query($conn, "SELECT tanggal_pembayaran, pembayaran, supplier.namasupplier FROM pembayaran 
                                JOIN supplier ON pembayaran.idsupplier = supplier.idsupplier ORDER BY tanggal_pembayaran DESC LIMIT 5");
                            
                            while ($row = mysqli_fetch_assoc($resultLogPembayaran)) {
                                echo "<tr>
                                    <td>" . $row['tanggal_pembayaran'] . "</td>
                                    <td>" . $row['namasupplier'] . "</td>
                                    <td>Rp. " . number_format($row['pembayaran'], 0, ',', '.') . "</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline aktivitas barang keluar/masuk -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            Aktivitas Terbaru
        </div>
        <div class="card-body">
            <!-- Timeline content here -->
        </div>
    </div>
</div>
<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php
// Query untuk mendapatkan data barang masuk per bulan
$queryMasuk = mysqli_query($conn, "
    SELECT MONTH(tanggal) AS bulan, SUM(qty) AS totalMasuk
    FROM masuk
    GROUP BY bulan
    ORDER BY bulan
");
$barangMasuk = array_fill(1, 12, 0); // Inisialisasi array untuk 12 bulan
while ($rowMasuk = mysqli_fetch_assoc($queryMasuk)) {
    $barangMasuk[$rowMasuk['bulan']] = $rowMasuk['totalMasuk'];
}

// Query untuk mendapatkan data barang keluar per bulan
$queryKeluar = mysqli_query($conn, "
    SELECT MONTH(tanggal) AS bulan, SUM(qty) AS totalKeluar
    FROM keluar
    GROUP BY bulan
    ORDER BY bulan
");
$barangKeluar = array_fill(1, 12, 0); // Inisialisasi array untuk 12 bulan
while ($rowKeluar = mysqli_fetch_assoc($queryKeluar)) {
    $barangKeluar[$rowKeluar['bulan']] = $rowKeluar['totalKeluar'];
}

// Convert data PHP ke format JSON untuk Chart.js
$barangMasukData = json_encode(array_values($barangMasuk));
$barangKeluarData = json_encode(array_values($barangKeluar));
?>
<script>
    // Mendapatkan konteks untuk Chart.js
    var ctx = document.getElementById("barChart").getContext("2d");

    // Data untuk chart, diambil dari PHP (array JSON)
    var barangMasuk = <?php echo $barangMasukData; ?>;
    var barangKeluar = <?php echo $barangKeluarData; ?>;

    // Membuat chart menggunakan Chart.js
    var barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            datasets: [{
                label: "Barang Masuk",
                backgroundColor: "rgba(2,117,216,1)",
                data: barangMasuk // Data barang masuk per bulan
            }, {
                label: "Barang Keluar",
                backgroundColor: "rgba(217,83,79,1)",
                data: barangKeluar // Data barang keluar per bulan
            }]
        },
        options: {
            scales: {
                x: { beginAtZero: true },
                y: { beginAtZero: true }
            }
        }
    });
</script>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Inventory Dashboard Pro 2024</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>

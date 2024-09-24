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
                        <h1 class="mt-4">Toko</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Data
                                </button>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Toko</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Inisialisasi variabel $i untuk penomoran
                                        $i = 1;
                                        // Query untuk mendapatkan data stok
                                        $getstock = mysqli_query($conn, 
                                        "SELECT * FROM toko"
                                        );
                                        
                                        // Looping
                                        while($data = mysqli_fetch_array($getstock)) {
                                            // Ambil data dari query
                                            $namatoko = $data['namatoko'];
                                        ?>
                                        <tr>
                                            <td><?php echo $i++;?></td>
                                            <td><?php echo $namatoko;?></td> 
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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
        <script src="js/datatables-simple-demo.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </body>
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Toko</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <form method="post" action = "function.php">
            <div class="modal-body">
                <input type="text" name="namatoko" placeholder="Masukkan Nama Toko" class="form-control" required>
                <br>
                <br>
                <button type="submit" class="btn btn-primary" name="addnewtoko">Submit</button>
            </div>
            </form>
            </div>
        </div>
    </div>
</html>

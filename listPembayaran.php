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
        <style>
            .status-lunas {
                background-color: green;
                color: white;
                font-weight: bold;
                padding: 2px 5px;
                border-radius: 5px;
                display: inline-block;
            }

            .status-belum-lunas {
                background-color: red;
                color: white;
                font-weight: bold;
                padding: 2px 5px;
                border-radius: 5px;
                display: inline-block;
            }
        </style>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Inventory Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <!-- Bootstrap 4 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome Icons -->
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
                        <h1 class="mt-4">Status Pembayaran</h1>
                        <!-- Tabel Tagihan -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Supplier</th>
                                            <th>Nama Barang</th>
                                            <th>Toko</th>
                                            <th>Quantity</th>
                                            <th>Tagihan</th>
                                            <th>Status</th>
                                            <th>Keterangan</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query untuk mendapatkan data tagihan
                                        $getstock = mysqli_query($conn, 
                                        "SELECT item.namabarang, supplier.namasupplier, toko.namatoko,
                                        tagihan.idtagihan, tagihan.tanggal, tagihan.qty, tagihan.tagihan AS tagihan, tagihan.status, tagihan.keterangan 
                                        FROM tagihan
                                        JOIN toko ON tagihan.idtoko = toko.idtoko
                                        JOIN item ON tagihan.idbarang = item.idbarang 
                                        JOIN supplier ON tagihan.idsupplier = supplier.idsupplier"
                                        );
                                        
                                        // Looping
                                        while($data = mysqli_fetch_array($getstock)) {
                                            // Ambil data dari query
                                            $idtagihan = $data['idtagihan'];
                                            $tanggal = $data['tanggal'];
                                            $namasupplier = $data['namasupplier'];
                                            $namabarang = $data['namabarang'];
                                            $namatoko = $data['namatoko'];
                                            $qty = $data['qty'];
                                            $tagihan = $data['tagihan'];
                                            $status = $data['status'];
                                            $keterangan = $data['keterangan'];
                                        ?>
                                        <tr>
                                            <td><?php echo $tanggal; ?></td>
                                            <td><?php echo $namasupplier; ?></td>
                                            <td><?php echo $namabarang; ?></td>
                                            <td><?php echo $namatoko; ?></td>
                                            <td><?php echo $qty; ?></td>
                                            <td><?php echo 'Rp. ' . number_format($tagihan, 0, ',', '.'); ?></td>
                                            <td>
                                                <span class="<?php echo ($status === 'Lunas') ? 'status-lunas' : 'status-belum-lunas'; ?>">
                                                    <?php echo $status; ?>
                                                </span>
                                            </td>
                                            <td><?php echo $keterangan; ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" 
                                                        data-idtagihan="<?php echo $idtagihan; ?>" 
                                                        data-tagihan="<?php echo $tagihan; ?>">
                                                    Update
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tabel Log Pembayaran -->
                        <h1 class="mt-4">Histori Pembayaran</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <table id="datatablesLog" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Tagihan</th>
                                            <th>Supplier</th>
                                            <th>Barang</th>
                                            <th>Toko</th>
                                            <th>Pembayaran</th>
                                            <th>Tanggal Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query untuk mendapatkan data log pembayaran
                                        $getLog = mysqli_query($conn, 
                                        "SELECT pembayaran.tanggal_pembayaran, supplier.namasupplier, item.namabarang, toko.namatoko, pembayaran.pembayaran, tagihan.tanggal AS tanggal_tagihan
                                        FROM pembayaran 
                                        JOIN supplier ON pembayaran.idsupplier = supplier.idsupplier 
                                        JOIN item ON pembayaran.idbarang = item.idbarang 
                                        JOIN toko ON pembayaran.idtoko = toko.idtoko
                                        JOIN tagihan ON pembayaran.idtagihan = tagihan.idtagihan");

                                        // Looping
                                        while($log = mysqli_fetch_array($getLog)) {
                                            $tanggal_tagihan = $log['tanggal_tagihan'];
                                            $namasupplier = $log['namasupplier'];
                                            $namabarang = $log['namabarang'];
                                            $namatoko = $log['namatoko'];
                                            $pembayaran = $log['pembayaran'];
                                            $tanggal_pembayaran = $log['tanggal_pembayaran'];
                                        ?>
                                        <tr>
                                            <td><?php echo $tanggal_tagihan; ?></td>
                                            <td><?php echo $namasupplier; ?></td>
                                            <td><?php echo $namabarang; ?></td>
                                            <td><?php echo $namatoko; ?></td>
                                            <td><?php echo 'Rp. ' . number_format($pembayaran, 0, ',', '.'); ?></td>
                                            <td><?php echo $tanggal_pembayaran; ?></td>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>
<script src="js/datatables-simple-demo.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    console.log("jQuery is ready");

    // Inisialisasi tabel
    new simpleDatatables.DataTable("#datatablesSimple");
    new simpleDatatables.DataTable("#datatablesLog");

    // Ketika modal muncul
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang men-trigger modal
        var idtagihan = button.data('idtagihan'); // Ambil data-idtagihan
        var tagihan = button.data('tagihan');     // Ambil data-tagihan

        // Isi data di dalam modal
        var modal = $(this);
        modal.find('#idtagihan').val(idtagihan);  // Set idtagihan ke input hidden
        modal.find('#tagihan_display').val(formatRupiah(tagihan.toString(), 'Rp.')); // Tampilkan format Rp. di tagihan_display
        modal.find('#tagihan').val(tagihan);      // Set tagihan ke input hidden
    });

    // Ketika pengguna menginput jumlah pembayaran, format ke Rp.
    $('#jumlah_bayar').on('input', function() {
        var value = $(this).val();
        $(this).val(formatRupiah(value, 'Rp.'));
    });

    // Ketika form di-submit, bersihkan format Rp. sebelum dikirim ke server
    $('form').on('submit', function() {
        // Bersihkan format Rp. dari jumlah pembayaran
        $('#jumlah_bayar').val(cleanRupiah($('#jumlah_bayar').val()));

        // Jika ada field lain yang menggunakan format Rp., bersihkan juga
        $('#tagihan').val(cleanRupiah($('#tagihan_display').val()));
    });
});

// Fungsi untuk memformat angka menjadi format "Rp." dengan pemisah ribuan
function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

// Fungsi untuk menghilangkan format Rp. dan mengembalikan angka asli
function cleanRupiah(rupiah) {
    return rupiah.replace(/[^0-9]/g, '');
}
</script>

<!-- Modal Pembayaran -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="function.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Proses Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Input hidden untuk id tagihan -->
                    <input type="hidden" name="idtagihan" id="idtagihan" required>

                    <!-- Tampilkan Nominal Tagihan (readonly, hanya untuk tampilan) -->
                    <div class="form-group">
                        <label for="tagihan_display">Nominal Tagihan</label>
                        <input type="text" id="tagihan_display" class="form-control" readonly>
                    </div>
                    
                    <!-- Input hidden untuk menyimpan nilai nominal tagihan asli -->
                    <input type="hidden" name="tagihan" id="tagihan">

                    <!-- Input Jumlah Pembayaran -->
                    <div class="form-group">
                        <label for="jumlah_bayar">Jumlah Pembayaran</label>
                        <input type="text" class="form-control" id="jumlah_bayar" name="jumlah_bayar" placeholder="Masukkan Jumlah Bayar" min="0" required>
                    </div>

                    <!-- Input Tanggal Pembayaran -->
                    <div class="form-group">
                        <label for="tanggal_pembayaran">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="processPayment" class="btn btn-primary">Bayar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>

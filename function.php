<?php
// Menampilkan semua error
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect('localhost', 'alvinnu7_dashboard', 'dashboard9021', 'alvinnu7_dashboard');

// Logika untuk menangani permintaan AJAX check supplier
if(isset($_POST['checksupplier'])) {
    $idsupplier = $_POST['checksupplier'];
    
    // Menggunakan prepared statements untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM item WHERE idsupplier = ? ORDER BY namabarang ASC");
    $stmt->bind_param("i", $idsupplier); // Mengikat parameter
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            echo '<option value="">Pilih Barang</option>';
            while($data = $result->fetch_assoc()){
                echo '<option value="'.$data['idbarang'].'">'.$data['namabarang'].'</option>';
            }
        } else {
            echo '<option value="">Barang tidak ditemukan</option>';
        }
    } else {
        error_log("SQL Error: " . $stmt->error); // Logging error SQL
        echo '<option value="">Gagal mengambil data barang</option>';
    }
    
    $stmt->close();
    exit();
}

// Logika untuk menangani permintaan AJAX check harga
if(isset($_POST['checkharga'])){
    $idbarang = $_POST['checkharga'];

    // Menggunakan prepared statements untuk keamanan
    $stmt = $conn->prepare("SELECT harga FROM item WHERE idbarang = ?");
    $stmt->bind_param("i", $idbarang); // Mengikat parameter
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $data = $result->fetch_assoc();
            echo json_encode(['harga' => $data['harga']]); // Kirim JSON murni
        } else {
            echo json_encode(['error' => 'Harga tidak ditemukan']);
        }
    } else {
        error_log("SQL Error: " . $stmt->error); // Logging error SQL
        echo json_encode(['error' => 'Gagal menjalankan query']);
    }

    $stmt->close();
    exit();
}

// Cek tagihan berdasarkan idtagihan
if (isset($_POST['checktagihan'])) {
    $idtagihan = $_POST['checktagihan'];
    $query = mysqli_query($conn, "SELECT tagihan FROM tagihan WHERE idtagihan = '$idtagihan'");
    if ($query) {
        $data = mysqli_fetch_assoc($query);
        echo json_encode(['tagihan' => $data['tagihan']]);
    } else {
        echo json_encode(['error' => 'Tagihan tidak ditemukan']);
    }
    exit();
}


// Tambah Supplier
if(isset($_POST['addnewsupplier'])){
    $namasupplier = $_POST['namasupplier'];

    // Query INSERT dengan memasukkan idSupplier
    $query = mysqli_query($conn, "INSERT INTO `supplier` (`namasupplier`) VALUES ('$namasupplier')");
    
    if($query){
        header('location: listSupplier.php');
        echo "<script>alert('Data berhasil disimpan!');</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}

// Tambah Toko
if(isset($_POST['addnewtoko'])){
    $namatoko = $_POST['namatoko'];

    // Query INSERT dengan memasukkan idSupplier
    $query = mysqli_query($conn, "INSERT INTO `toko` (`namatoko`) VALUES ('$namatoko')");
    
    if($query){
        echo "<script>alert('Data berhasil disimpan!');</script>";
        header('location: listToko.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}

// Tambah item
if(isset($_POST['addnewitem'])){
    $namabarang = $_POST['namabarang'];
    $hargabarang = $_POST['harga'];
    $idsupplier = $_POST['idsupplier']; // Menyimpan idSupplier yang dipilih dari dropdown

    // Query INSERT dengan memasukkan idSupplier
    $query = mysqli_query($conn, "INSERT INTO `item` (`namabarang`, `harga`, `idsupplier`) VALUES ('$namabarang', '$hargabarang', '$idsupplier')");
    
    if($query){
        echo "<script>alert('Data berhasil disimpan!');</script>";
        header('location: listProduk.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}

if (isset($_POST['addnewout'])) {
    $idsupplier = $_POST['idsupplier'];
    $idbarang = $_POST['idbarang'];
    $idtoko = $_POST['idtoko'];
    $tanggal = $_POST['tanggal'];
    $qty = $_POST['qty'];
    $tagihan = $_POST['tagihan'];
    $keterangan = $_POST['keterangan'];

    // Validasi tagihan
    if (empty($tagihan) || $tagihan <= 0) {
        die('Error: Harga total tidak valid.');
    }

    // Cek stok yang ada
    $checkstock = mysqli_query($conn, "SELECT * FROM `stock` WHERE `idbarang` = '$idbarang'");
    if (mysqli_num_rows($checkstock) > 0) {
        // Jika barang sudah ada di tabel stock, update stok
        $datastock = mysqli_fetch_array($checkstock);
        $stock = $datastock['stock'];

        // Validasi apakah stok cukup
        if ($stock < $qty) {
            die('Error: Stok barang tidak mencukupi.');
        }

        $newstock = $stock - $qty;
        $updatestock = mysqli_query($conn, "UPDATE `stock` SET `stock` = '$newstock' WHERE `idbarang` = '$idbarang'");
        if (!$updatestock) {
            die('Error: ' . mysqli_error($conn));
        }
    } else {
        // Jika barang belum ada di tabel stock, tambahkan sebagai data baru
        $updatestock = mysqli_query($conn, "INSERT INTO `stock` (`idbarang`, `idsupplier`, `stock`) VALUES ('$idbarang', '$idsupplier', '-$qty')");
        if (!$updatestock) {
            die('Error: ' . mysqli_error($conn));
        }
    }

    // Insert data ke tabel keluar
    $query = mysqli_query($conn, "INSERT INTO `keluar` (`idsupplier`, `idbarang`, `idtoko`, `tanggal`, `qty`, `tagihan`, `keterangan`) VALUES ('$idsupplier', '$idbarang', '$idtoko', '$tanggal', '$qty', '$tagihan', '$keterangan')");
    if (!$query) {
        die('Error: ' . mysqli_error($conn));
    }

    // Dapatkan idkeluar dari insert terakhir
    $idkeluar = mysqli_insert_id($conn);

    // Insert data ke tabel tagihan dengan idkeluar
    $querytagihan = mysqli_query($conn, "INSERT INTO `tagihan` (`idkeluar`, `idsupplier`, `idbarang`, `idtoko`, `tanggal`, `qty`, `tagihan`, `keterangan`) VALUES ('$idkeluar', '$idsupplier', '$idbarang', '$idtoko', '$tanggal', '$qty', '$tagihan', '$keterangan')");
    if (!$querytagihan) {
        die('Error: ' . mysqli_error($conn));
    }

    // Redirect setelah berhasil
    echo "<script>alert('Data berhasil disimpan!');</script>";
    header('location: listKeluar.php');
    exit();
}


// Tambah barang masuk
if(isset($_POST['addnewin'])){
    $idsupplier = $_POST['idsupplier'];
    $idbarang = $_POST['idbarang'];
    $tanggal = $_POST['tanggal'];
    $qty = $_POST['qty'];
    $keterangan = $_POST['keterangan'];

    // Cek stok yang ada
    $checkstock = mysqli_query($conn, "SELECT * FROM `stock` WHERE `idbarang` = '$idbarang'");
    if (mysqli_num_rows($checkstock) > 0) {
        // Jika barang sudah ada di tabel stock, update stok
        $datastock = mysqli_fetch_array($checkstock);
        $stock = $datastock['stock'];
        $addstock = $stock + $qty;
        $updatestock = mysqli_query($conn, "UPDATE `stock` SET `stock` = '$addstock' WHERE `idbarang` = '$idbarang'");
    } else {
        // Jika barang belum ada di tabel stock, tambahkan sebagai data baru
        $updatestock = mysqli_query($conn, "INSERT INTO `stock` (`idbarang`, `idsupplier`, `stock`) VALUES ('$idbarang', '$idsupplier', '$qty')");
    }

    // Insert data ke tabel masuk
    $query = mysqli_query($conn, "INSERT INTO `masuk` (`idsupplier`, `idbarang`, `tanggal`, `qty`, `keterangan`) VALUES ('$idsupplier', '$idbarang', '$tanggal', '$qty', '$keterangan')");

    if($query && $updatestock){
        echo "<script>alert('Data berhasil disimpan!');</script>";
        header('location: listMasuk.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}

// Tambah barang retur
if(isset($_POST['addnewretur'])){
    $idsupplier = $_POST['idsupplier'];
    $idbarang = $_POST['idbarang'];
    $idtoko = $_POST['idtoko'];
    $tanggal = $_POST['tanggal'];
    $qty = $_POST['qty'];
    $keterangan = $_POST['keterangan'];

    // Cek stok yang ada
    $checkstock = mysqli_query($conn, "SELECT * FROM `stock` WHERE `idbarang` = '$idbarang'");
    if (mysqli_num_rows($checkstock) > 0) {
        // Jika barang sudah ada di tabel stock, update stok 
        $datastock = mysqli_fetch_array($checkstock);
        $stock = $datastock['stock'];
        $addstock = $stock + $qty;
        $updatestock = mysqli_query($conn, "UPDATE `stock` SET `stock` = '$addstock' WHERE `idbarang` = '$idbarang'");
    } else {
        // Jika barang belum ada di tabel stock, tambahkan sebagai data baru
        $updatestock = mysqli_query($conn, "INSERT INTO `stock` (`idbarang`, `idsupplier`, `stock`) VALUES ('$idbarang', '$idsupplier', '$qty')");
    }

    // Query INSERT dengan memasukkan idSupplier
    $query = mysqli_query($conn, "INSERT INTO `retur` (`idsupplier`, `idbarang`, `idtoko`, `tanggal`, `qty`, `keterangan`) VALUES ('$idsupplier', '$idbarang', '$idtoko', '$tanggal', '$qty', '$keterangan')");
    
    if($query && $updatestock){
        echo "<script>alert('Data berhasil disimpan!');</script>";
        header('location: listRetur.php');
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
        echo "Error: " . mysqli_error($conn);
    }
    exit();
}

if(isset($_POST['processPayment'])){
    $idtagihan = $_POST['idtagihan'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $tanggal_pembayaran = $_POST['tanggal_pembayaran'];  // Ambil tanggal pembayaran

    // Validasi jika jumlah bayar lebih dari 0
    if($jumlah_bayar > 0){
        // Update nominal tagihan sesuai pembayaran yang diterima
        $updateTagihan = mysqli_query($conn, "UPDATE tagihan SET tagihan = tagihan - '$jumlah_bayar' WHERE idtagihan='$idtagihan'");

        // Masukkan log pembayaran ke tabel pembayaran (tetap simpan log pembayaran)
        $insertPembayaran = mysqli_query($conn, 
        "INSERT INTO pembayaran (idtagihan, idsupplier, idbarang, idtoko, pembayaran, tanggal_pembayaran) 
        SELECT tagihan.idtagihan, tagihan.idsupplier, tagihan.idbarang, tagihan.idtoko, '$jumlah_bayar', '$tanggal_pembayaran' 
        FROM tagihan WHERE idtagihan='$idtagihan'");

        // Cek apakah tagihan sudah lunas
        $checkTagihan = mysqli_query($conn, "SELECT tagihan FROM tagihan WHERE idtagihan='$idtagihan'");
        $dataTagihan = mysqli_fetch_array($checkTagihan);
        if ($dataTagihan['tagihan'] == 0) {
            // Jika lunas, update status menjadi 'Lunas'
            $updateStatus = mysqli_query($conn, "UPDATE tagihan SET status='Lunas' WHERE idtagihan='$idtagihan'");
        }

        if($updateTagihan && $insertPembayaran){
            echo "<script>alert('Data berhasil disimpan!');</script>";
            header("Location: listPembayaran.php"); // Redirect setelah sukses
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Gagal menyimpan data! Jumlah pembayaran harus lebih dari 0.');</script>";
    }
}

?>

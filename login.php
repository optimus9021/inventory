<?php
session_start();

// Cek apakah user sudah login, jika ya, redirect ke index.php
if(isset($_SESSION['log']) && $_SESSION['log'] === "True") {
    header('location: index.php');
    exit();
}

// Jika user men-submit form login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Koneksi ke database
    require 'function.php';

    // Cek username dan password
    $checkdb = mysqli_query($conn, "SELECT * FROM login WHERE username = '$username' AND password = '$password'");
    $hitung = mysqli_num_rows($checkdb);

    if ($hitung > 0) {
        // Jika login berhasil, set session
        $_SESSION['log'] = "True";
        header('location: index.php');
        exit();
    } else {
        // Jika login gagal, kembali ke login.php
        echo "<p>Username atau Password salah!</p>";
        header('location: login.php');
        exit();
    }
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
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="username" id="inputUsername" type="username" placeholder="Username" />
                                                <label for="inputUsername">User Name</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Password" />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button class="btn btn-primary" name="login">Login</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

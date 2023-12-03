<?php
session_start();
require "../koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <nav>
        <?php require "navbar-login.php" ?>
    </nav>
    <div class="tubuh">
        <div class="bungkus">
            <div class="main d-flex flex-column justify-content-center align-items-center bungkus">
                <div class="login-box p-5 col-lg-4 shadow">
                    <form action="" method="post">
                        <div>
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" required>
                        </div>
                        <div>
                            <label for="sandi">Sandi</label>
                            <input type="password" class="form-control" name="sandi" id="sandi" required>
                        </div>
                        <div>
                            <button class="btn btn-success form-control mt-3" type="submit"
                                name="loginbtn">Login</button>

                        </div>
                    </form>
                    <form>
                    <input class="btn btn-warning mt-3 form-control" type="button" value="Daftar" onclick="window.location.href='daftar.php'" />
                </form>
                </div>
                <div>
                <?php
if (isset($_POST['loginbtn'])) {
    $username = htmlspecialchars($_POST['username']);
    $sandi = htmlspecialchars($_POST['sandi']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $countdata = mysqli_num_rows($query);
    $data = mysqli_fetch_array($query);

    if ($countdata > 0) {
        $role = $data['role']; // Assuming your role column is 'role' in the users table

        if (password_verify($sandi, $data['sandi'])) {
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $role; // Assign the role retrieved from the database
            $_SESSION['login'] = true;

            if ($role == 'admin') {
                header('location: ../adminpanel');
            } elseif ($role == 'tamu') {
                header('location: ../toko.php?halaman=1');
            } else {
                // Handle other roles if needed
            }
        } else {
            ?>
            <div class="alert alert-warning" role="alert">Sandi salah</div>
            <?php
        }
    } else {
        ?>
        <div class="alert alert-warning" role="alert">Akun tidak tersedia</div>
        <?php
    }
}
?>

                </div>
            </div>
        </div>
        <footer>
            <?php require "admin-footer.php" ?>
        </footer>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
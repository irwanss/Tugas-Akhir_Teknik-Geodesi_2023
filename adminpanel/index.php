<?php
require "../koneksi.php";
require "session.php";

$queryProduk = mysqli_query($conn,"SELECT id FROM produk");
$jumlahProduk = mysqli_num_rows($queryProduk);

$queryKategori = mysqli_query($conn,"SELECT id FROM kategori");
$jumlahKategori = mysqli_num_rows($queryKategori);


 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../fontawsome/css/all.min.css">

</head>

<body>
    <div class="tubuh">
        <div>
            <nav>
                <?php require "admin-navbar.php" ?>
            </nav>

            <div class="d-flex mb-5 justify-content-center mt-5">
                <div class="badan mt-5">
                    <h2>Halo
                        <?php echo $_SESSION['username']; ?>
                    </h2>
                    <p>
                        Selamat datang di halaman adminpanel. Berikut yang dapat anda lakukan di halaman adminpanel :
                        <br>
                        1. Melakukan perubahan, menambahkan dan menghapus kategori dengan memilih opsi <a
                            href="kategori.php">kategori</a><br>
                        2. Melakukan perubahan, menambahkan dan menghapus produk dengan memilih opsi <a
                            href="produk.php">produk</a>
                    </p>
                    <div class="d-flex flex-row justify-content-center gap-3">
                        <div class="p-2"><i class="fa-regular fa-clipboard fa-xl"></i> Jumlah Kategori : <?php echo $jumlahKategori ?></div>
                        <div class="p-2"><i class="fa-solid fa-list fa-xl"></i> Jumlah Produk : <?php echo $jumlahProduk ?></div>
                        
                    </div>
</div>
                  
                </div>
            </div>
        </div>
        <footer>
            <?php require "admin-footer.php" ?>
        </footer>
    </div>
    <script src="../fontawsome/js/all.min.js"></script>
</body>

</html>
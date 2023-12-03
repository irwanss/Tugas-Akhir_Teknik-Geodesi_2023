<?php
require "koneksi.php";

$nama = htmlspecialchars($_GET['nama']);
$queryProduk = mysqli_query($conn, "SELECT * FROM produk WHERE nama='$nama'");
$produk = mysqli_fetch_array($queryProduk);

$queryProdukTerkait = mysqli_query($conn, "SELECT * FROM produk WHERE kategori_id='$produk[kategori_id]' AND id!='$produk[id]' LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="fontawsome/css/all.min.css">
</head>

<body>
    <div class="tubuh">
        <div>
            <nav>
                <?php require "navbar.php" ?>
            </nav>
            <div class="container-fluid py-5">
                <div class="container">
                    <div class="row p-3" style="background-color: #fff;">
                        <div class="col-md-6">
                            <div class=" imgpr p-2">
                                <img src="images/produk/<?php echo $produk['foto']; ?>" alt="ambatuka"
                                    class="objectfit-img img-thumnail gambar rounded">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h1 class="mw-100">
                                <?php echo $produk['nama']; ?>
                            </h1>
                            <?php
                            $queryToko = mysqli_query($conn, "SELECT * FROM toko WHERE id = {$produk['toko_id']}");
                            $namaToko = mysqli_fetch_array($queryToko);
                            $queryKategori = mysqli_query($conn, "SELECT kategori FROM kategori WHERE id = {$produk['kategori_id']}");
                            $kategori = mysqli_fetch_array($queryKategori);
                            ?>
                            <p class="mw-100">
                                <i class="fa-solid fa-shop"></i>
                                <?php echo $namaToko['nama']; ?> <i class="fa-regular fa-clipboard"></i>
                                <?php echo $kategori['kategori']; ?>
                            </p>
                            <p class="harga fs-4">
                                Rp
                                <?php echo $produk['harga'] ?>
                            </p>
                            <p class="fs-5">
                            <i class="fa-solid fa-box"></i> Stok:
                                <?php echo $produk['stok']; ?>
                            </p>
                            
                            <p class="fs-5">
                                <a class="no-decoration" href="tel:<?php echo $namaToko['kontak']; ?> "><i class="fa-solid fa-address-book"></i> Hubungi Penjual</a>
                                
                            </p>

                            <div>
                                <p class="fs-5 mw-100">
                                    Deskripsi produk : <br><?php echo $produk['detail']; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <!-- produk terkait -->
        <div class="container-fluid py-5 clr-1">
            <div class="container">
                <h2 class="text-center text-white mb-3">Produk Terkait</h2>
                <div class="row">
                    <?php while ($data = mysqli_fetch_array($queryProdukTerkait)) { ?>
                        <div class="col-sm-6 col-lg-3 mb-3 imgpt">
                            <a href="produk-detail.php?nama=<?php echo $data['nama']; ?>">
                                <img src="images/produk/<?php echo $data['foto']; ?>" alt="<?php echo $data['nama']; ?>"
                                    class="img-fluid img-thumbnail objectfit-img">
                            </a>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
        <?php require "footer.php" ?>
    </footer>
    </div>
</body>

</html>
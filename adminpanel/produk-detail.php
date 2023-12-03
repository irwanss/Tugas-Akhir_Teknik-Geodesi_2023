<?php
require "session.php";
require "../koneksi.php";

$id = $_GET['p'];
$queryDetail = mysqli_query($conn, "SELECT a.*, b.kategori AS nama_kategori, c.nama AS nama_toko
FROM produk a
INNER JOIN kategori b ON a.kategori_id = b.id
INNER JOIN toko c ON a.toko_id = c.id
WHERE a.id = $id");
$data = mysqli_fetch_assoc($queryDetail);

$queryKategori = mysqli_query($conn, "SELECT * FROM kategori WHERE id != {$data['kategori_id']}");

$queryToko = mysqli_query($conn, "SELECT * FROM toko WHERE id != {$data['toko_id']}");

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $kategori = htmlspecialchars($_POST['kategori']);
    $harga = htmlspecialchars($_POST['harga']);
    $stok = htmlspecialchars($_POST['stok']);
    $detail = htmlspecialchars($_POST['detail']);
    $IdTokoNuser = htmlspecialchars($_POST['username']);

    // Menghandle gambar produk
    $nama_file = basename($_FILES["foto"]["name"]);
    if ($nama_file != '') {
        $target_dir = "../images/produk/";
        $target_file = $target_dir . $nama_file;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $image_size = $_FILES["foto"]["size"];
        $random_name = generateRandomString(20);
        $newImgName = $random_name . "." . $imageFileType;

        if ($nama == '' || $kategori == '' || $harga == '' || $stok == '') {
            $error_message = "Nama, Kategori, atau Harga Produk Tidak Boleh Kosong!";
        } else {
            if ($image_size > 5000000) {
                $error_message = "Ukuran File Maksimal 5 MB";
            } elseif (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
                $error_message = "Tipe Gambar Harus Jpg/PNG";
            } else {
                move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $newImgName);
                $queryUpdate = mysqli_query($conn, "UPDATE produk SET kategori_id='$kategori', toko_id='$IdTokoNuser', user_id='$IdTokoNuser', harga='$harga', nama='$nama', stok='$stok', detail='$detail', foto='$newImgName' WHERE id='$id'");

                if ($queryUpdate) {
                    $success_message = "Produk berhasil diupdate";
                    header("refresh:1;URL='produk.php'");
                }
            }
        }
    } else {
        // Jika tidak ada gambar yang diunggah
        if ($nama == '' || $kategori == '' || $harga == '' || $stok == '') {
            $error_message = "Nama, Kategori, atau Harga Produk Tidak Boleh Kosong!";
        } else {
            $queryUpdate = mysqli_query($conn, "UPDATE produk SET kategori_id='$kategori', toko_id='$IdTokoNuser', user_id='$IdTokoNuser', harga='$harga', nama='$nama', stok='$stok', detail='$detail' WHERE id='$id'");

            if ($queryUpdate) {
                $success_message = "Produk berhasil diupdate";
                header("refresh:1;URL='produk.php'");
            }
        }
    }
}
            if(isset($_POST['hapus'])) {
                $queryHapus = mysqli_query($conn,"DELETE FROM produk WHERE id='$id'");

                if($queryHapus) {
                    $success_message = "Produk berhasil dihapus";
                    header("refresh:1;URL='produk.php'");
                }
            }
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawsome/css/all.min.css">
</head>

<body class="body">
<div class="tubuh">
        <div>
            <nav><?php require "admin-navbar.php" ?></nav>
    <div class="container mt-5">
        <div class="d-flex justify-content-center">
            <div class="my-5 col-12 col-lg-6 shadow p-3" style="border-radius: 15px; background-color: #fff;">
                <div class="d-flex justify-content-center">
                    <h4>Edit Produk</h4>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="container mt-2">
                        <div class="row">
                            <div class="col">
                                <input type="text" id="nama" name="nama" value="<?= $data['nama'] ?>" class="form-control" placeholder="Nama Produk" autocomplete="off" required>
                            </div>
                            <div class="col">
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="<?= $data['kategori_id'] ?>"><?= $data['nama_kategori'] ?></option>
                                    <?php
                                    while ($dataKategori = mysqli_fetch_array($queryKategori)) {
                                        ?>
                                        <option value="<?= $dataKategori['id'] ?>">
                                            <?= $dataKategori['kategori'] ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <input type="number" name="harga" class="form-control" placeholder="Harga" step="1000" value="<?= $data['harga'] ?>" autocomplete="off" required>
                            </div>
                            <div class="col">
                                <input type="number" name="stok" class="form-control" placeholder="Stok" autocomplete="off" value="<?= $data['stok'] ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="container mt-2">
                        <div class="container mt-2 border rounded">
                            <div class="container mt-2">
                                <div class="d-flex justify-content-center align-items-center">
                                    <div class="text-center mb-2">Foto Produk Sekarang</div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <img src="../images/produk/<?= $data['foto'] ?>" alt="foto produk" class="img-fluid mb-3" style="max-width: 25%;">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <input type="file" name="foto" id="foto" class="form-control">
                        </div>
                        <div class="col mt-3">
                                    <select class="form-select" id="username" name="username" required>
                                    <option value="<?= $data['toko_id'] ?>"><?= $data['nama_toko'] ?></option>
                                        <?php
                                        while ($dataToko = mysqli_fetch_array($queryToko)) {
                                            ?>
                                            <option value="<?php echo $dataToko['id']; ?>">
                                            <?php echo $dataToko['nama']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                            </div>
                        <div class="mt-3">
                            <textarea name="detail" id="detail" cols="30" rows="10" class="form-control"><?= $data['detail'] ?></textarea>
                        </div>
                        <div class="mt-3 d-flex justify-content-center gap-3">
                            <button class="btn btn-success" type="submit" name="simpan">Update Produk</button>
                            <button class="btn btn-danger" type="submit" name="hapus">Hapus Produk</button>
                            </div>
                    </div>
                </form>
                <?php
                if (isset($error_message)) {
                    echo '<div class="alert alert-warning mt-3" role="alert" style="text-align: center;">' . $error_message . '</div>';
                } elseif (isset($success_message)) {
                    echo '<div class="alert alert-success mt-3" role="alert" style="text-align: center;">' . $success_message . '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    </div>
        <footer>
            <div class="mt-5">
            <?php require "admin-footer.php" ?>
            </div>
        </footer>
    </div>
</body>
</html>

<?php
require "session.php";
require "../koneksi.php";

$query = mysqli_query($conn, "SELECT a.*, b.kategori AS nama_kategori, c.nama AS nama_toko  FROM produk a JOIN  kategori b ON a.kategori_id=b.id JOIN toko c ON a.toko_id=c.id");
$jumlahProduk = mysqli_num_rows($query);

$queryKategori = mysqli_query($conn, "SELECT * FROM kategori");

$queryToko = mysqli_query($conn, "SELECT users.id AS user_id, toko.*
FROM users
INNER JOIN toko ON toko.user_id = users.id");

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
    <link rel="stylesheet" href="../fontawsome/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../js/jquery-3.7.0.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
    <div class="tubuh">
        <div>
            <nav>
                <?php require "admin-navbar.php" ?>
            </nav>

            <div class="container mt-5">
                <div class="d-flex justify-content-center">
                    <h3>List Produk</h3>
                </div>
                <div class="table-responsive mt-5 bg-putih p-3">
                <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Nama</th>
                                <th class="text-center align-middle">Kategori</th>
                                <th class="text-center align-middle">Harga</th>
                                <th class="text-center align-middle">Stok</th>
                                <th class="text-center align-middle">Nama Toko</th>
                                <th class="text-center align-middle">Edit Produk</th>
                                <th class="text-center align-middle">Hapus Produk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($jumlahProduk == 0) {
                                ?>
                                <tr>
                                    <td colspan=7 class=text-center>Data Produk Tidak tersedia</td>
                                    <td class=text-center></td>
                                    <td class=text-center></td>
                                    <td class=text-center></td>
                                    <td class=text-center></td>
                                    <td class=text-center></td>
                                    <td class=text-center></td>
                                </tr>
                                <?php
                            } else {
                                $jumlah = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                    ?>
                                    <tr>
                                        <td class="text-center align-middle">
                                            <?php echo $jumlah; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php echo $data['nama']; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php echo $data['nama_kategori']; ?>
                                        </td>
                                        <td class="text-center align-middle">Rp
                                            <?php echo $data['harga']; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php echo $data['stok']; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php echo $data['nama_toko']; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="produk-detail.php?p=<?php echo $data['id']; ?>" class="btn btn-info"><i
                                                    class="fa-regular fa-pen-to-square"></i></a>

                                        </td>
                                        <td class="text-center align-middle">
                                            <form method="post">
                                                <button class="btn btn-danger" type="submit" name="btndelete"
                                                    value="<?php echo $data['id']; ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                    $jumlah++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if (isset($_POST['btndelete'])) {
                        $categoryId = $_POST['btndelete'];

                        $querydelete = mysqli_query($conn, "DELETE FROM produk WHERE id='$categoryId'");

                        if ($querydelete) {
                            ?>
                            <div class="alert alert-success mt-3" role="alert" style="text-align: center;">
                                Produk Berhasil dihapus
                            </div>

                            <meta http-equiv="refresh" content="1;URL='produk.php'" />
                        <?php
                        } else {
                            echo mysqli_error($conn);
                        }
                    }

                    ?>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="my-5 col-12 col-md-6 shadow p-3" style="border-radius: 15px; background-color: #fff;">
                        <div class="d-flex justify-content-center">
                            <h4>Tambah Produk</h4>
                        </div>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col">
                                    <input type="text" id="nama" name="nama" class="form-control"
                                        placeholder="Nama Produk" autocomplete="off" required>
                                </div>
                                <div class="col">
                                    <select class="form-select" id="kategori" name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <?php
                                        while ($dataKategori = mysqli_fetch_array($queryKategori)) {
                                            ?>
                                            <option value="<?php echo $dataKategori['id']; ?>">
                                                <?php echo $dataKategori['kategori'] ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <input type="number" name="harga" class="form-control" placeholder="Harga"
                                        autocomplete="off" min="0" required>
                                </div>
                                <div class="col">
                                    <input type="number" name="stok" class="form-control" placeholder="Stok"
                                        autocomplete="off" min="0" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                            <div class="col">
                                    <select class="form-select" id="username" name="username" required>
                                        <option value="">Nama Toko</option>
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
                            </div>
                            <div class="mt-3">
                                <input type="file" name="foto" id="foto" class="form-control" required>
                            </div>
                            <div class="mt-3">
                                <textarea name="detail" id="detail" cols="30" rows="10"
                                    placeholder="Masukan Deskripsi Produk" class="form-control"></textarea>
                            </div>
                            <div class="mt-3 d-flex justify-content-center">
                                <button class="btn btn-success" type="submit" name="simpan">Tambah Produk</button>
                            </div>
                        </form>

                        <?php
                        if (isset($_POST['simpan'])) {
                            $nama = htmlspecialchars($_POST['nama']);
                            $kategori = htmlspecialchars($_POST['kategori']);
                            $harga = htmlspecialchars($_POST['harga']);
                            $stok = htmlspecialchars($_POST['stok']);
                            $detail = htmlspecialchars($_POST['detail']);
                            $IdTokoNuser = htmlspecialchars($_POST['username']);

                            $target_dir = "../images/produk/";
                            $nama_file = basename($_FILES["foto"]["name"]);
                            $target_file = $target_dir . $nama_file;
                            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                            $image_size = $_FILES["foto"]["size"];
                            $random_name = generateRandomString(20);
                            $newImgName = $random_name . "." . $imageFileType;

                            if ($nama == '' || $kategori == '' || $harga == '' || $stok == '') {
                                ?>
                                <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                    Nama, Kategori, atau Harga Produk Tidak Boleh Kosong!
                                </div>
                                <?php
                            } elseif ($nama_file != '') {
                                if ($image_size > 50000000) {
                                    ?>
                                    <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                        Ukuran File Maksimal 5 mb
                                    </div>
                                    <?php
                                } else {
                                    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
                                        ?>
                                        <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                            Tipe Gambar Harus Jpg/PNG
                                        </div>
                                        <?php
                                    } else {
                                        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $newImgName);
                                        // query insert ke tabel produk
                                        $queryTambah = mysqli_query($conn, "INSERT INTO produk (kategori_id, toko_id, user_id, nama, harga, foto, detail, stok) VALUES ('$kategori', '$IdTokoNuser', '$IdTokoNuser', '$nama', '$harga', '$newImgName', '$detail', '$stok')");

                                        if ($queryTambah) {
                                            ?>
                                            <div class="alert alert-success mt-3" role="alert" style="text-align: center;">
                                                Produk Berhasil Ditambahkan
                                            </div>

                                            <meta http-equiv="refresh" content="1;URL='produk.php'" />
                                            <?php
                                        } else {
                                            echo mysqli_error($conn);
                                        }
                                    }
                                }
                            }
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
    <script>
        new DataTable('#example');
    </script>
</body>

</html>
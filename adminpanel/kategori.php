<?php
require "session.php";
require "../koneksi.php";

$queryKategori = mysqli_query($conn, "SELECT * FROM kategori");
$jumlahKategori = mysqli_num_rows($queryKategori);






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
                <div class="mt-3">
                    <center><h3>List Kategori</h3></center>
                </div>

                <div class="table-responsive mt-5 shadow bg-putih p-3">
                    <table id="example" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Nama Kategori</th>
                                <th class="text-center align-middle">Edit Kategori</th>
                                <th class="text-center align-middle">Hapus Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($jumlahKategori == 0) {
                                ?>
                                <tr>
                                    <td colspan=3 class=text-center>Data Kategori Tidak tersedia</td>
                                </tr>
                                <?php
                            } else {
                                $jumlah = 1;
                                while ($data = mysqli_fetch_array($queryKategori)) {
                                    ?>
                                    <tr>
                                        <td class="text-center align-middle">
                                            <?php echo $jumlah; ?>
                                        </td>
                                        <td class="text-center align-middle">
                                            <?php echo $data['kategori']; ?>
                                        </td>
                                        <td class="text-center align-middle"><a
                                                href="kategori-detail.php?p=<?php echo $data['id']; ?>" class="btn btn-info"><i
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

                        // Cek apakah kategori terkait dengan produk
                        $querycek = mysqli_query($conn, "SELECT * FROM produk WHERE kategori_id='$categoryId'");
                        $dataAda = mysqli_num_rows($querycek);

                        if ($dataAda > 0) {
                            ?>
                            <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                Kategori Sudah Ada!!!
                            </div>
                            <?php
                            die();
                        }

                        $querydelete = mysqli_query($conn, "DELETE FROM kategori WHERE id='$categoryId'");

                        if ($querydelete) {
                            ?>
                            <div class="alert alert-success mt-3" role="alert" style="text-align: center;">
                                Kategori Berhasil dihapus
                            </div>

                            <meta http-equiv="refresh" content="1;URL='kategori.php'" />
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
                            <h4>Tambah Kategori</h4>
                        </div>

                        <form action="" method="post">
                            <div>
                                <input type="text" id="kategori" name="kategori" placeholder="Masukan Kategori Baru"
                                    class="form-control" autocomplete="off" required>
                            </div>
                            <div class="mt-3 d-flex justify-content-center">
                                <button class="btn btn-success" type="submit" name="simpan_kategori">Tambah
                                    Kategori</button>
                            </div>
                        </form>
                        <?php
                        if (isset($_POST['simpan_kategori'])) {
                            $kategori = htmlspecialchars($_POST['kategori']);

                            $queryExist = mysqli_query($conn, "SELECT kategori FROM kategori WHERE kategori='$kategori'");
                            $jumlahDataKategoriBaru = mysqli_num_rows($queryExist);

                            if ($jumlahDataKategoriBaru > 0) {
                                ?>
                                <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                    Kategori Sudah Ada!!!
                                </div>
                                <?php
                            } else {
                                $querysimpan = mysqli_query($conn, "INSERT INTO kategori (kategori) VALUES ('$kategori')");
                                if ($querysimpan) {
                                    ?>
                                    <div class="alert alert-success mt-3" role="alert" style="text-align: center;">
                                        Kategori Berhasil Ditambahkan
                                    </div>

                                    <meta http-equiv="refresh" content="1;URL='kategori.php'" />
                                    <?php
                                } else {
                                    echo mysqli_error($conn);
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <?php require "admin-footer.php" ?>
        </footer>
    </div>
    <script>
    new DataTable('#example');
    </script>
</body>

</html>
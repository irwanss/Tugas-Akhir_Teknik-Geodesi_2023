<?php
require "session.php";
require "../koneksi.php";

$id = $_GET['p'];

$query = mysqli_query($conn, "SELECT * FROM kategori WHERE id='$id'");
$data = mysqli_fetch_array($query);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <div class="tubuh">
        <div>
            <nav>
                <?php require "admin-navbar.php" ?>
            </nav>
            <div class="container mt-5 d-flex justify-content-center" style="height:70vh; align-items: center;">


                <div class="col-12 col-md-6 shadow p-3 rounded-4" style="background-color: #fff">
                    <div class="d-flex justify-content-center">
                        <h4>Ubah Kategori</h4>
                    </div>
                    <form action="" method="post">
                        <div class="d-flex justify-content-center">
                            <input type="text" name="kategori" id="kategori" value="<?php echo $data['kategori']; ?>">
                        </div>

                        <div class="mt-3 d-flex justify-content-center gap-2">
                            <button type="submit" class="btn btn-primary" name="editbtn">Ubah</button>
                            <button type="submit" class="btn btn-danger" name="deletebtn">Hapus</button>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['editbtn'])) {
                        $kategori = htmlspecialchars($_POST['kategori']);

                        if ($data['kategori'] == $kategori) {
                            ?>
                            <meta http-equiv="refresh" content="2;URL='kategori.php'" />
                        <?php
                        } else {
                            $query = mysqli_query($conn, "SELECT * FROM kategori WHERE kategori='$kategori'");
                            $jumalahdata = mysqli_num_rows($query);

                            if ($jumalahdata > 0) {
                                ?>
                                <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                    Kategori Sudah Ada!!!
                                </div>
                            <?php
                            } else {
                                $querysimpan = mysqli_query($conn, "UPDATE kategori SET  kategori='$kategori' WHERE id='$id'");
                                if ($querysimpan) {
                                    ?>
                                    <div class="alert alert-success mt-3" role="alert" style="text-align: center;">
                                        Kategori Berhasil Diubah
                                    </div>

                                    <meta http-equiv="refresh" content="1;URL='kategori.php'" />
                                <?php
                                } else {
                                    echo mysqli_error($conn);
                                }

                            }
                        }
                    }
                    if (isset($_POST['deletebtn'])) {
                        $querycek = mysqli_query($conn, "SELECT * FROM produk WHERE kategori_id='$id'");
                        $dataAda = mysqli_num_rows($querycek);

                        if ($dataAda > 0) {
                            ?>
                            <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                Kategori tidak dapat dihapus jika tidak terdaftar di produk!!!
                            </div>
                            <?php
                            die();
                        }

                        $querydelete = mysqli_query($conn, "DELETE FROM kategori WHERE id='$id'");

                        if ($querydelete) {
                            ?>
                            <div class="alert alert-success mt-3" role="alert" style="text-align: center;">
                                Kategori Berhasil Dihapus
                            </div>

                            <meta http-equiv="refresh" content="2;URL='kategori.php'" />

                        <?php
                        } else {
                            echo mysqli_error($conn);
                        }
                    }
                    ?>
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
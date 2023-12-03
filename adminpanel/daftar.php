<?php
require "../koneksi.php";

$queryToko = mysqli_query($conn, "SELECT * FROM toko");
$queryUser = mysqli_query($conn, "SELECT * FROM users");

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
    <link rel="stylesheet" href="../leaflet/leaflet/leaflet.css">
    <script src="../leaflet/leaflet/leaflet.js"></script>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <div class="tubuh">
        <div class="bungkus">
            <nav>
                <?php require "admin-navbar.php" ?>
            </nav>
            <div class="ajust">
                <div class="form col-lg-9 shadow">
                    <h2 class="mt-3 mb-5">Buat akun Anda</h2>
                    <form action="" method="post" enctype="multipart/form-data"
                        class="d-flex flex-column align-items-center mb-5">
                        <table class="table no-border-table">
                            <tr>
                                <td>Username</td>
                                <td><input type="text" class="form-control" id="username" autocomplete="off"
                                        name="username" required></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td><input type="password" class="form-control" id="pass" name="pass" required></td>
                            </tr>
                            <tr>
                                <td>Nama Toko</td>
                                <td><input type="text" class="form-control" id="nama_toko" name="nama_toko" required>
                                </td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td><input type="text" class="form-control" id="alamat" name="alamat" required></td>
                            </tr>
                            <tr>
                                <td>Kontak</td>
                                <td><input type="text" inputmode="numeric" class="form-control" id="kontak"
                                        name="kontak" required></td>
                            </tr>
                            <tr>
                                <td>Foto Toko</td>
                                <td><input type="file" name="foto" id="foto" class="form-control" required></td>
                            </tr>
                        </table>
                        <!-- tampilan map -->
                        <div class="kotak">
                            <div id="map" style="height: 300px;"></div>
                            <p>(Pindah marker sesuai lokasi toko anda)</p>
                        </div>
                        <!-- untuk koordinat -->
                        <input type="text" id="latitude" name="latitude" />
                        <input type="text" id="longitude" name="longitude" />
                        <button type="submit" class="btn btn-primary mt-3" name="tambahAkun">Buat akun Anda</button>
                    </form>
                    <?php
                    if (isset($_POST['tambahAkun'])) {
                        $username = htmlspecialchars($_POST['username']);
                        $pass = htmlspecialchars($_POST['pass']);
                        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

                        $nama_toko = htmlspecialchars($_POST['nama_toko']);
                        $alamat = htmlspecialchars($_POST['alamat']);
                        $kontak = htmlspecialchars($_POST['kontak']);
                        $latitude = htmlspecialchars($_POST['latitude']);
                        $longitude = htmlspecialchars($_POST['longitude']);

                        $target_dir = "../images/toko/";
                        $nama_file = basename($_FILES["foto"]["name"]);
                        $target_file = $target_dir . $nama_file;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                        $image_size = $_FILES["foto"]["size"];
                        $random_name = generateRandomString(20);
                        $newImgName = $random_name . "." . $imageFileType;

                        // Cek keberadaan username dan nama toko
                        $queryCekUser = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
                        $queryCekToko = mysqli_query($conn, "SELECT * FROM toko WHERE nama='$nama_toko'");

                        $userAda = mysqli_num_rows($queryCekUser);
                        $tokoAda = mysqli_num_rows($queryCekToko);

                        if ($userAda > 0 or $tokoAda > 0) {
                            ?>
                            <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                Username atau nama toko sudah digunakan, coba gunakan nama lain!!!
                            </div>
                            <?php
                            die();
                        } elseif ($nama_file != '') {
                            if ($image_size > 50000000) {
                                ?>
                                <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                    Ukuran File Maksimal 5 mb
                                </div>
                                <?php
                            } else {
                                if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                                    ?>
                                    <div class="alert alert-warning mt-3" role="alert" style="text-align: center;">
                                        Tipe Gambar Harus Jpg/PNG
                                    </div>
                                    <?php
                                } else {
                                    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $newImgName);
                                    // query insert ke tabel user
                                    $queryTambahUser = mysqli_query($conn, "INSERT INTO users (username, sandi) VALUES ('$username', '$hashedPassword')");
                                    if ($queryTambahUser) {
                                        // Get the auto-incremented ID from the last insert operation
                                        $userId = mysqli_insert_id($conn);
                                        // insert tabel toko
                                        $queryTambahToko = mysqli_query($conn, "INSERT INTO toko (user_id, nama, alamat, kontak, gambar, latitude, longitude) VALUES ('$userId', '$nama_toko', '$alamat', '$kontak','$newImgName', '$latitude', '$longitude')");
                                    }

                                    if ($queryTambahUser && $queryTambahToko) {
                                        ?>
                                        <script>
                                            // Check if the Notification API is supported
                                            if ('Notification' in window) {
                                                // Check the current permission status
                                                if (Notification.permission === 'granted') {
                                                    // The user has already granted permission, show the notification
                                                    var notification = new Notification("Akun berhasil dibuat");
                                                } else if (Notification.permission !== 'denied') {
                                                    // The user hasn't made a choice yet or permission is default
                                                    // Request permission
                                                    Notification.requestPermission().then(function (permission) {
                                                        if (permission === 'granted') {
                                                            // The user has now granted permission, show the notification
                                                            var notification = new Notification("Akun berhasil dibuat");
                                                        }
                                                    });
                                                }
                                            } else {
                                                // Fallback for browsers that don't support the Notification API
                                                alert("Akun berhasil dibuat");
                                            }

                                            // Redirect after 5 seconds
                                            setTimeout(function () {
                                                window.location.href = 'login.php';
                                            }, 5000);
                                        </script>
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
        <?php require "admin-footer.php" ?>
    </footer>
    <script>
        // Initialize the map
        var map = L.map('map').setView([0, 0], 13);

        // Add a tile layer (you can choose a different one)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Add a marker for location
        var marker = L.marker([0, 0], { draggable: true }).addTo(map);

        // Handle drag event to update coordinates
        marker.on('dragend', function (event) {
            var markerLatLng = marker.getLatLng();
            document.getElementById('latitude').value = markerLatLng.lat;
            document.getElementById('longitude').value = markerLatLng.lng;
        });

        // Get geolocation and update the map
        map.locate({ setView: true, maxZoom: 16 });

        // Handle location found event
        map.on('locationfound', function (event) {
            var location = event.latlng;
            document.getElementById('latitude').value = location.lat;
            document.getElementById('longitude').value = location.lng;
            marker.setLatLng(location);
        });

        // Handle location error event
        map.on('locationerror', function () {
            alert("Unable to retrieve your location. Please enter manually.");
        });
    </script>
</body>

</html>
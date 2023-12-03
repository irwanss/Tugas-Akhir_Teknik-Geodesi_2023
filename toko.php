<?php
require "koneksi.php";

// Start or resume the session
session_start();
$queryKategori = mysqli_query($conn, "SELECT * FROM kategori");

$start = 0;
$produkPerHalaman = 9;

// Check if the seed is set in the session, otherwise generate a new one
$seed = isset($_SESSION['seed']) ? $_SESSION['seed'] : rand();

if (isset($_GET['keyword']) || isset($_GET['kategori'])) {
    $condition = "";

    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        $condition .= "toko.nama LIKE '%$keyword%' OR kategori.kategori LIKE '%$keyword%' OR produk.nama LIKE '%$keyword%'";
    }

    if (isset($_GET['kategori'])) {
        $kategoriTerpilih = $_GET['kategori'];
        $queryGetKategoriId = mysqli_query($conn, "SELECT id FROM kategori WHERE kategori='$kategoriTerpilih'");
        $kategoriId = mysqli_fetch_array($queryGetKategoriId);
        $condition .= $condition ? " AND " : "";
        $condition .= "kategori_id='$kategoriId[id]'";
    }

    // Generate a random order once and use it for both row count and data retrieval
    $randomOrder = "ORDER BY RAND($seed)";
    $queryRow = mysqli_query($conn, "SELECT produk.*, toko.nama AS nama_toko, kategori.kategori AS nama_kategori FROM produk 
                                    INNER JOIN toko ON produk.toko_id = toko.id 
                                    INNER JOIN kategori ON produk.kategori_id = kategori.id 
                                    WHERE $condition ORDER BY produk.harga ASC");
} else {
    // Generate a random order once and use it for both row count and data retrieval
    $randomOrder = "ORDER BY RAND($seed)";
    $queryRow = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 $randomOrder");
}

$JumlahRow = mysqli_num_rows($queryRow);
$Pages = ceil($JumlahRow / $produkPerHalaman);

if (isset($_GET['halaman'])) {
    $Page = $_GET['halaman'] - 1;
    $start = $Page * $produkPerHalaman;
}

if (isset($_GET['keyword']) || isset($_GET['kategori'])) {
    $queryProduk = mysqli_query($conn, "SELECT produk.*, toko.nama AS nama_toko, kategori.kategori AS nama_kategori FROM produk 
                                        INNER JOIN toko ON produk.toko_id = toko.id 
                                        INNER JOIN kategori ON produk.kategori_id = kategori.id 
                                        WHERE $condition ORDER BY produk.harga ASC LIMIT $start, $produkPerHalaman");
} else {
    // Use the same random order for both row count and data retrieval
    $queryProduk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0 $randomOrder LIMIT $start, $produkPerHalaman");
}

$countData = mysqli_num_rows($queryProduk);

// Store the updated seed in the session for consistent random ordering across requests
$_SESSION['seed'] = $seed;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawsome/css/all.min.css">
    <link rel="stylesheet" href="leaflet/leaflet/leaflet.css" />
    <script src="leaflet/leaflet/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Leaflet Routing Machine -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <!-- Leaflet Acurate Position -->
    <script src="leaflet/Leaflet.AccuratePosition-master/Leaflet.AccuratePosition.js"></script>
</head>

<body>
    <div class="tubuh">
        <div>
            <?php require "navbar.php" ?>
            <!-- banner -->
            <div class="container-fluid banner d-flex align-items-center">
                <div class="container">
                    <h1 class="text-white text-center">Temukan Kebutuhan Pertanian Anda Sekarang</h1>
                </div>
            </div>
            <!-- body -->
            <div class="container py-5">
                <div class="d-flex flex-column border rounded p-5" style="background-color: #fff;">
                    <div>
                        <div class="d-flex flex-row mb-3 justify-content-end">
                            <div class="col-md-6">
                                <form method="get" action="toko.php" id="searchForm">
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="halaman" value="1">
                                        <input type="text" class="form-control" id="searchInput" name="keyword"
                                            placeholder="Cari disini" aria-label="Cari" aria-describedby="basic-addon2"
                                            value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
                                        <button type="submit" class="btn telusuri">Telusuri</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-lg-3 mb-5">
                                <h3 class="text-center pt-3"><i class="fa-regular fa-clipboard"></i> Kategori</h3>
                                <ul class="list-group">
                                    <?php while ($kategori = mysqli_fetch_array($queryKategori)) { ?>
                                        <a href="toko.php?halaman=1&kategori=<?php echo $kategori['kategori']; ?>">
                                            <li class="list-group-item">
                                                <?php echo $kategori['kategori']; ?>
                                            </li>
                                        </a>
                                    <?php } ?>
                                    <a href="toko.php?halaman=1">
                                        <li class="list-group-item">
                                            Semua Kategori
                                        </li>
                                    </a>
                                </ul>
                                <div class="tambah mt-3">
                            <?php 
                session_start();
                if($_SESSION['login']==false){
                    ?> 
                    <div class="d-flex flex-column align-items-center"> 
                    <p style="text-align: center">Login untuk tambahkan produk anda</p>
                    <a href="adminpanel/login.php"><button class="btn btn-ijo">Login</button></a>
                    </div>
                    <?php
                }else{
                    ?>  
                    <div class="d-flex flex-column align-items-center">
                    <span class="pb-3">Login sebagai: <?php echo $_SESSION['username']; ?></span> 
                    <a href="tambah-produk.php"><button class="btn btn-primary mb-3">Tambahkan Produk</button></a>
                    <a href="adminpanel/logout.php"><button class="btn btn-danger">Logout</button></a>
                    </div>
                    <?php
                }
                ?>
                            </div>
                            </div>
                            <div class="col-lg-9">
                                <h3 class="text-center pt-3">Daftar Produk</h3>
                                <div class="hasil mt-3">
                                    <?php
                                    if (isset($_GET['kategori'])) {
                                        $kategoriTerpilih = $_GET['kategori'];
                                        $totalProduk = mysqli_query($conn, "SELECT produk.id, kategori.kategori FROM `produk` 
                                            INNER JOIN kategori ON produk.kategori_id = kategori.id WHERE kategori.kategori = '$kategoriTerpilih'");
                                        $jumlahProduk = mysqli_num_rows($totalProduk);
                                    } elseif (isset($_GET['keyword'])) {
                                        $kategoriTerpilih = $_GET['keyword'];
                                        $totalProduk = mysqli_query($conn, "SELECT produk.*, toko.nama AS nama_toko, kategori.kategori AS nama_kategori FROM produk 
                                        INNER JOIN toko ON produk.toko_id = toko.id 
                                        INNER JOIN kategori ON produk.kategori_id = kategori.id WHERE toko.nama LIKE '%$keyword%' OR kategori.kategori LIKE '%$keyword%' OR produk.nama LIKE '%$keyword%'");
                                        $jumlahProduk = mysqli_num_rows($totalProduk);}
                                else {
                                        $totalProduk = mysqli_query($conn, "SELECT id FROM produk");
                                        $jumlahProduk = mysqli_num_rows($totalProduk);
                                        $kategoriTerpilih = "Semua Kategori";
                                    }
                                    ?>
                                    <p>Menampilkan
                                        <?php echo $jumlahProduk; ?> hasil dari
                                        <?php echo $kategoriTerpilih; ?>
                                    </p>
                                </div>
                                <div class="row pt-3">
                                    <?php while ($produk = mysqli_fetch_array($queryProduk)) { ?>
                                        <div class="col-sm-4 mb-3">
                                            <div class="card">
                                                <div class="image-box imghe p-2">
                                                    <img src="images/produk/<?php echo $produk['foto']; ?>"
                                                        class="objectfit-img img-thumnail gambar"
                                                        alt="<?php echo $produk['foto']; ?>">
                                                </div>
                                                <div class="card-body">
                                                    <h4 class="card-title">
                                                        <?php echo $produk['nama']; ?>
                                                    </h4>
                                                    <p class="card-text text-harga">Rp
                                                        <?php echo $produk['harga']; ?>
                                                    </p>
                                                    <?php
                                                    $queryToko = mysqli_query($conn, "SELECT * FROM toko WHERE id = {$produk['toko_id']}");
                                                    $toko = mysqli_fetch_array($queryToko);

                                                    // Display store name
                                                    ?>
                                                    <p>
                                                        <i class="fa-solid fa-shop"></i>
                                                        <?php echo $toko['nama']; ?>
                                                    </p>

                                                    <!-- Create a unique map container for each product -->
                                                    <div id="map-<?php echo $produk['id']; ?>" style="display: none;"></div>
                                                    <div class="d-flex align-items-center gap-1 mb-3">
                                                        <i class="fa-solid fa-location-crosshairs"></i>
                                                        <div id="outputDiv-<?php echo $produk['id']; ?>"></div>
                                                    </div>
                                                    <!-- Leaflet Routing Machine -->
                                                    <script>
                                                        var map<?php echo $produk['id']; ?> = L.map('map-<?php echo $produk['id']; ?>').setView([-7.280073, 110.133208], 13);

                                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map<?php echo $produk['id']; ?>);

                                                        // Get geolocation and update the map
                                                        map<?php echo $produk['id']; ?>.locate({ setView: true, maxZoom: 16 });

                                                        // Handle location found event
                                                        map<?php echo $produk['id']; ?>.on('locationfound', function (event) {
                                                            var location = event.latlng;

                                                            // Log the latitude value to the console
                                                            console.log('Latitude:', location.lat);

                                                            // Set the latitude and longitude values to form fields
                                                            document.getElementById('latitude').value = location.lat;
                                                            document.getElementById('longitude').value = location.lng;

                                                            // Create a routing control with dynamic initial coordinates
                                                            var initialLatitude = location.lat;
                                                            var initialLongitude = location.lng;

                                                            var control<?php echo $produk['id']; ?> = L.Routing.control({
                                                                waypoints: [
                                                                    L.latLng([initialLatitude, initialLongitude]), // User's location (dynamic coordinates)
                                                                    L.latLng(<?php echo $toko['latitude']; ?>, <?php echo $toko['longitude']; ?>) // Store's location
                                                                ],
                                                                routeWhileDragging: false
                                                            }).addTo(map<?php echo $produk['id']; ?>);

                                                            // Listen for the routes event
                                                            control<?php echo $produk['id']; ?>.on('routesfound', function (e) {
                                                                // Get the route information
                                                                var route = e.routes[0];

                                                                // Get the distance in meters
                                                                var distanceMeters = route.summary.totalDistance;

                                                                // Convert meters to kilometers
                                                                var distanceKm = distanceMeters / 1000;

                                                                // Display the distance on your HTML page
                                                                document.getElementById('outputDiv-<?php echo $produk['id']; ?>').innerHTML = distanceKm.toFixed(2) + ' km';
                                                            });
                                                        });

                                                    </script>
                                                    <?php
                                                    $queryCategory = mysqli_query($conn, "SELECT kategori FROM kategori WHERE id = {$produk['kategori_id']}");
                                                    $category = mysqli_fetch_array($queryCategory);
                                                    ?>
                                                    <p>
                                                        <i class="fa-regular fa-clipboard"></i>
                                                        <?php echo $category['kategori']; ?>
                                                    </p>
                                                    <a href="produk-detail.php?nama=<?php echo $produk['nama']; ?>"
                                                        class="btn btn-ijo">Lihat Detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- Pagination -->
                                <nav aria-label="tombol" class="d-flex justify-content-center mt-3">
                                    <?php require "pagination.php" ?>
                                </nav>
                                <!-- End Pagination -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="" method="get">
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </form>

        <footer>
            <?php require "footer.php" ?>
        </footer>
        <script src="fontawesome/js/all.min.js"></script>
</body>

</html>
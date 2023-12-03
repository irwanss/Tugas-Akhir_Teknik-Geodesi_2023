<?php require "koneksi.php";

$query = "SELECT * FROM toko";
$result = $conn->query($query);

$locations = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $locations[] = $row;
  }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/map.css">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/js/bootstrap.min.js"></script>

  <!--Leaflet Utama-->
  <link rel="stylesheet" href="leaflet/leaflet/leaflet.css" />
  <script src="leaflet/leaflet/leaflet.js"></script>

  <!--JQuery-->
  <script src="leaflet/jquery-3.7.1/dist/jquery.min.js"></script>

  <!--Lokasi-->
  <link rel="stylesheet" href="leaflet/controlLocate/L.Control.Locate.min.css">
  <script src="leaflet/controlLocate/L.Control.Locate.min.js"></script>

  <!--Scale-->
  <link rel="stylesheet" href="leaflet/leaflet-betterscale-master/L.Control.BetterScale.css" />
  <script src="leaflet/leaflet-betterscale-master/L.Control.BetterScale.js"></script>

  <!--ResetView-->
  <link rel="stylesheet" href="leaflet/ResetView/L.Control.ResetView.min.css">
  <script type="text/javascript" src="leaflet/ResetView/L.Control.ResetView.min.js"></script>

  <!-- Icon -->
  <link rel="stylesheet" href="leaflet/awesome-markers/leaflet.awesome-markers.css">
  <script src="leaflet/awesome-markers/leaflet.awesome-markers.js"></script>
  <link rel="stylesheet" href="fontawsome/css/all.min.css">

</head>

<body class="d-flex flex-column h-100">
  <nav>
    <?php require "navbar.php" ?>
  </nav>
  <!-- Modal -->
  <div class="modal fade" id="petaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <?php require "modalPeta.php" ?>
  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Gunakan setTimeout untuk menunda tampilan modal selama 2000 milidetik (2 detik)
      setTimeout(function () {
        var modal = new bootstrap.Modal(document.getElementById('petaModal'));
        modal.show();
      }, 1000);
    });
  </script>


  <div id="map">
    <script>

      // Posisi pusat peta dan zoom level untuk layar kecil
      var smallScreenOptions = {
        center: [-7.274294694421051, 110.13662530501584],
        zoom: 13
      };

      // Posisi pusat peta dan zoom level untuk layar besar
      var largeScreenOptions = {
        center: [-7.274294694421051, 110.13662530501584],
        zoom: 15
      };

      // Membuat peta
      var map = L.map('map').setView(largeScreenOptions.center, largeScreenOptions.zoom);

      // Menambahkan kontrol reset view ke peta
      var resetControl = L.control.resetView({
        position: "topleft",
        title: "Reset view",
        latlng: L.latLng(largeScreenOptions.center[0], largeScreenOptions.center[1]), // Menggunakan pusat peta untuk posisi reset
        zoom: largeScreenOptions.zoom,
      }).addTo(map);

      // Fungsi untuk menyesuaikan peta dan kontrol reset view berdasarkan ukuran layar
      function adjustToScreenSize() {
        var screenSize = window.innerWidth <= 767 ? 'small' : 'large';

        // Menyesuaikan peta berdasarkan ukuran layar
        var options = (screenSize === 'small') ? smallScreenOptions : largeScreenOptions;
        map.setView(options.center, options.zoom);

        // Menghapus kontrol reset view yang sudah ada
        map.removeControl(resetControl);

        // Membuat kontrol reset view baru dengan opsi yang diperbarui
        resetControl = L.control.resetView({
          position: "topleft",
          title: "Reset view",
          latlng: L.latLng(options.center[0], options.center[1]),
          zoom: options.zoom,
        }).addTo(map);
      }

      // Memanggil fungsi untuk menyesuaikan peta saat memuat halaman
      window.onload = adjustToScreenSize;

      // Responsif saat ukuran layar berubah
      window.onresize = adjustToScreenSize;




      // Lokasi
      L.control.locate().addTo(map);

      //Skala
      L.control.betterscale().addTo(map);

      //BaseMaps
      var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        subdomains: ['a', 'b', 'c']
      });

      var ESRISatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; <a href="https://www.esri.com/en-us/home">Esri</a>',
        subdomains: ['a', 'b', 'c']
      }).addTo(map);

      // Layer Control
      var baseMaps = {
        "Esri Satelit": ESRISatellite,
        "OpenStreetMap": osm,
      };


      L.control.layers(baseMaps).addTo(map);


      //Marker
      //Marker
      <?php foreach ($locations as $location) { ?>
        L.marker([<?php echo $location['latitude']; ?>, <?php echo $location['longitude']; ?>], {
          icon: L.AwesomeMarkers.icon({
            icon: 'shop', // Ganti dengan 'shop'
            prefix: 'fa',
            markerColor: 'darkblue'
          })
        }).addTo(map).bindPopup(
          "<div style='text-align: center;'>" +
          "<img src='images/toko/<?php echo $location['gambar']; ?>' width='150px'><br>" +
          "Nama Toko: <?php echo $location['nama']; ?><br>" +
          "Alamat: <?php echo $location['alamat']; ?><br>" +
          "Kontak: <?php echo $location['kontak']; ?>" +
          "</div>"
        );
      <?php } ?>
    </script>
  </div>
  <footer>
    <?php require "footer.php" ?>
  </footer>
</body>

</html>
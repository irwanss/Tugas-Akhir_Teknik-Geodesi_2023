<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Webgis Persebaran Fasilitas Penunjang Pertanian</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
</head>

<body>
  <div class="tubuh">
    <div>
      <nav class="navbar navbar-expand-lg p-md-3" id="navbar">
        <div class="container">
          <a class="navbar-brand" href="tentang.php">WEBGIS</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" id="navbar-toggler">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarNav">
            <div class="mx-auto"></div>
            <ul class="navbar-nav nav-itemm">
              <li class="nav-itemm">
                <a class="nav-link" href="index.php">Beranda</a>
              </li>
              <li class="nav-itemm">
                <a class="nav-link" href="peta.php">Peta</a>
              </li>
              <li class="nav-itemm">
                <a class="nav-link" href="toko.php?halaman=1">Toko</a>
              </li>
              <li class="nav-itemm">
                <a class="nav-link" href="tentang.php">Tentang</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="content">
        <main>
          <main>
            <div>
              <div class="kotak">
                <img src="images/petani.png">
              </div>
            </div>
            <div class="kanan">
              <h1>WEBGIS PERTANIAN<span class="temukan"> Cari Kebutuhan Pertanian Anda</span><span class="ganti"></span>
              </h1>

            </div>
            <div class="tombol">
              <div>
                <a href="toko.php?halaman=1&kategori=Alat Tani"><img src="images/alat tani.svg" alt="Alat Tani">
                  <div>
                    <figcaption>Alat Tani</figcaption>
                  </div>
                </a>
              </div>
              <div>
                <a href="toko.php?halaman=1&kategori=Bibit"><img src="images/bibit.svg" alt="Bibit">
                  <div>
                    <figcaption>Bibit</figcaption>
                  </div>
                </a>
              </div>
              <div>
                <a href="toko.php?halaman=1&kategori=Pupuk"><img src="images/pupuk.png" alt="Pupuk">
                  <div>
                    <figcaption>Pupuk</figcaption>
                  </div>
                </a>
              </div>
              <div>
                <a href="toko.php?halaman=1&kategori=Obat"><img src="images/pestisida.png" alt="obat">
                  <div>
                    <figcaption>Obat</figcaption>
                  </div>
                </a>
              </div>
            </div>
          </main>
      </div>
    </div>
    <footer>
      <div class="mt-5">
        <?php require "footer.php" ?>
      </div>
    </footer>
  </div>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript">
    var navbar = document.getElementById('navbar');
    var navbarToggler = document.getElementById('navbar-toggler');

    navbarToggler.addEventListener('click', function () {
      navbar.classList.toggle('bg-dark');
      navbar.classList.toggle('shadow');
    });
  </script>
</body>

</html>
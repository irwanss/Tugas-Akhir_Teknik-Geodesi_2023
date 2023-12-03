<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navbar</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary warna-bg">
<div class="container">
        <a class="navbar-brand" href="#" style="color: white !important;">WEBGIS</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
          id="navbar-toggler"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="mx-auto"></div>
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link text-white" href="index.php">Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="peta.php">Peta</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="toko.php?halaman=1">Toko</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="tentang.php">Tentang</a>
            </li>
          </ul>
        </div>
      </div>
</nav>
<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
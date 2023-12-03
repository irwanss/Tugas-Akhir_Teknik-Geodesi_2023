<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modal peta</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-inner">
                    <div class="carousel-item active image-box imghe">
                        <img src="images/modalImg/modal1.png" class="objectfit-img img-thumnail gambar" alt="...">
                    </div>
                    <div class="carousel-item image-box imghe">
                        <img src="images/modalImg/modal2.png" class="objectfit-img img-thumnail gambar" alt="...">
                    </div>
                    <div class="carousel-item image-box imghe">
                        <img src="images/modalImg/modal3.png" class="objectfit-img img-thumnail gambar" alt="...">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</body>

</html>
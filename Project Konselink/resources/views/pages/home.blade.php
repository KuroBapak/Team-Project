<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico')}}">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <script type="text/javascript" src="{{ ('jquery/jquery.min.js') }}"></script>
    <title>Konselink</title>
    <style>
        @media only screen and (max-width: 992px) {
            #hide {
                display: none;
            }
        }
    </style>
</head>
<body style="font-family: 'Poppins';">
<div class="container-fluid">
    <!-- navbar -->
<nav class="mt-5">
    <div class="row text-center">
        <div class="col-4">
                <div class="d-flex justify-content-center">
                <a href="ig"><img src="assets/ig.png" style="width: 25px; height: 25px;"></a>
                <a href="fb"><img src="assets/fb.png" class="ms-3 me-3" style="width: 25px; height: 25px;"></a>
                <a href="gmail"><img src="assets/m.png" style="width: 25px; height: 25px;"></a>
                </div>
        </div>
        <div class="col-4">
            <img class="img-fluid" src="assets/konselor.png" style="height: 30px; width: 30px;">
            <img class="img-fluid" src="assets/NESKAR.png" style="height: 30px; width: 30px;">
            KonseLink
        </div>
        <div class="col-4"><button type="button" class="btn btn-primary">login</button></div>
    </div>
</nav>
<!-- navbar end -->

<div class="container-fluid" style="height: 100vh">
    <div class="row">
        <div class="col-xl-6">
            <div class="container-fluid">
                <div style="margin-top: 220px; margin-left: 35px">
                <h1 class="fw-bold">JADWALKAN PERTEMUAN</h1>
                <p>Pilih waktu yang sesuai dengan Anda untuk bertemu dengan<br> guru BK tanpa perlu menunggu. Dengan fleksibilitas yang<br> ditawarkan situs kami, Anda dapat mengatur sesi konseling<br> sesuai kebutuhan Anda sendiri.</p>
                <button type="button" class="btn btn-primary rounded-5 ms-3" style="width: 200px">Buat Jadwal</button>
            </div>
            </div>
        </div>
        <div id="hide" class="col-6" style="background-color: ">
            <img class="img-fluid ps-5 ms-5 mt-5 pt-5" src="assets/imgh.png">
        </div>
    </div>
</div>
</div>
<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="assets/img1.png" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="assets/img2.png" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="assets/img3.png" class="d-block w-100" alt="...">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
<div class="container-fluid" style="height: 90vh">
    <div class="row">
        <div class="col-5 text-end">
            <img class="img-fluid" src="assets/neskar.png" style="width: 200px; height: 200px; margin-top: 200px">
        </div>
        <div class="col-7" style="margin-top: 200px">
            <span class="fw-bold" style="color: #323A7F">Tentang Konselink</span>
            <p>KonseLink, solusi unggulan untuk penjadwalan appointment layanan<br> Bimbingan Konseling (BK) di Sekolah SMK Negeri 1 Karawang,<br> memberikan kemudahan kepada siswa dan orang tua dalam<br> menentukan waktu pertemuan dengan guru BK. Dengan antarmuka<br> yang user-friendly, KonseLink meningkatkan aksesibilitas layanan BK<br> secara online, memastikan pengalaman yang efisien dan terkoordinasi<br> di lingkungan pendidikan Sekolah SMK Negeri 1 Karawang.</p>
        </div>
    </div>
</div>


  <div class="container-fluid p-5 text-white" style="background-color: #132043;">
    <div class="container-fluid " style="">
        <div class="row">
            <div class="col-1">
            </div>
            <div class="col-6">
                <div class="row" style="font-size: 20px">
                <img src="assets/neskar.png" class="img-fluid text-end" style="height: 108px; width: 130px;" alt="...">
                SMK Negeri 1<br>Karawang
            </div>
                <p>Jl. Pangkal Perjuangan, Karawang Barat,<br> Karawang, Jawa Barat, Indonesia - 41361</p>
                <p>smknegeri1karawang@gmail.com</p>
            </div>
            <div class="col-4 text-end">
                <img src="assets/ttc.png" class="img-fluid text-end" alt="...">
            </div>
            <div class="col-1">
            </div>
        </div>
<hr class="rounded-1" style="border: 1px solid white;">
<p class="text-center">@ Copyright TechTastic 2024</p>
    </div>
</div>


<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>
</html>

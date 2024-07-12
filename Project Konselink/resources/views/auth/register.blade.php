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

        .login-page{
            background-image: url('./assets/bulet.png');
            background-position: top-left;
            background-repeat: no-repeat;
            background-size: 500px;
        }

        @media (max-width: 960px) {
            .form-login{
                display: none;
            }
        }
    </style>
</head>
<body style="background-color: #F4F4F4; font-family: 'Poppins';">
<!-- Content -->
<div class="login-page">
<div class="container-fluid">
    <div class="row">
        <div class="col-8 form-login">
            <div class="container mt-5 pt-4" style="margin-left: 120px">
                <h1 class="text-white fw-bold" style="font-size: 90px; text-shadow: 1px 1px 3px rgba(0,0,0,0.6);">KONSEL<span style="color: #1E2772">INK<img src="/assets/toga.png" class="img-fluid mb-3" style="width: 60px;" alt="..."></span></h1>
            </div>
            <div class="row">
                <div class="col d-flex justify-content-center" style="padding-top: 0px;">
                    <img src="assets/bk.png" alt="..." style="width: 750px;" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="col-xl-4" style="background-color: white; height: 100vh;">
            <div class="container">
            <div class="row mt-5">
                <div class="col-6 text-end">
                    <img class="img-fluid" src="assets/konselor.png" style="height: 120px; width: 120px;">
                </div>
                <div class="col-6 text-start">
                    <img class="img-fluid" src="assets/NESKAR.png" style="height: 120px; width: 120px;">
                </div>
            </div>
            <div class="container-fluid mt-5">
                <p class="text-center">Daftarkan akun guru BK</p>
                <form>
                    <label for="form-nama" class="form-label">Nama</label>
                <div class="input-group mb-3">
                    <input type="text" style="background-color: #EEEEEE" class="form-control" placeholder="Masukkan nama Guru BK" id="form-nama" aria-label="Username" aria-describedby="basic-addon1">
                    <span class="input-group-text" style="background-color: #1E2772" id="basic-addon1"><img src="assets/profile.png" alt=""></span>
                  </div>
                  <label for="form-jurusan" class="form-label">Jurusan</label>
                  <div class="input-group mb-3" style="background-color: #EEEEEE">
                    <select class="form-select" style="background: none" placeholder="Pilih jurusan untuk Guru BK" id="form-jurusan" aria-label="Username" aria-describedby="basic-addon1">
                    <option selected disabled>Pilih jurusan untuk Guru BK</option>
                    <option>asui</option>
                    <option>asau</option>
                    </select>
                    <span class="input-group-text" data-bs-toggle="form-select" aria-expanded="false" style="background-color: #1E2772;" id="basic-addon1" onclick=""><img src="assets/arrow-down.png" alt=""></span>
                </div>
                  <label for="form-email" class="form-label">Email</label>
                  <div class="input-group mb-3">
                    <input type="text" style="background-color: #EEEEEE" class="form-control" placeholder="Masukkan email  Guru BK" id="form-email" aria-label="Username" aria-describedby="basic-addon1">
                    <span class="input-group-text" style="background-color: #1E2772" id="basic-addon1"><img src="assets/mail.png" alt=""></span>
                  </div>
                  <label for="form-password" class="form-label">Password</label>
                  <div class="input-group mb-3">
                    <input type="text" style="background-color: #EEEEEE" class="form-control" placeholder="Masukkan password untuk Guru BK" id="form-password" aria-label="Username" aria-describedby="basic-addon1">
                    <span class="input-group-text" style="background-color: #1E2772" id="basic-addon1"><img src="assets/lock.png" alt=""></span>
                  </div>
                  <div class="d-flex justify-content-end">
                    <a class="text-end" href="">Lupa Password?</a>
                  </div>
                  <div class="d-flex justify-content-center">
                      <button type="button" style="background-color: #1E2772;" class="btn w-100 mt-3 text-white">Daftar</button>
                  </div>
                </form>
            </div>
            </div>
        </div>
    </div>

</div>
</div>
<!-- Content END -->
<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>
</html>

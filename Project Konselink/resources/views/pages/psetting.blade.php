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
    .hidden {
            display: none;
        }
</style>
</head>
<body style="background-color: #F4F4F4; font-family: 'Poppins';">
<div class="container-fluid">
    <div class="row">
        <div id="hide" class="col-2" style="background-color: #323A7F; height: 130vh;">
            <div class="row ps-3">
                <img src="assets/gbulet.png" style="height: 20px; width: 100px;" class="img-fluid mt-3 mb-3">
            </div>
            <div class="row text-white mb-1">
                <p class="text-center fw-bold" style="font-size: 30px;">KonseLink</p>
            </div>
            <div class="row">
                <div class="container">
                <table class="table text-white">
                    <tbody>
                      <tr>
                        <a href="youtube.com">
                        <td><img src="assets/home.png" class="img-fluid me-2" alt="..."><a class="text-decoration-none text-white" href="url">BERANDA</a></td>
                        <td><div class="text-end"><img src="assets/arrow-right.png" class="img-fluid text-end" alt="..."></div></td>
                        </a>
                      </tr>
                    </tbody>
                    <tbody>
                        <tr>
                          <td><img src="assets/calendar.png" class="img-fluid me-2" alt="..."><a class="text-decoration-none text-white" href="url">DATA PERMINTAAN</a></td>
                          <td><div class="text-end"><img src="assets/arrow-right.png" class="img-fluid text-end" alt="..."></div></td>
                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          <td><img src="assets/profile2.png" class="img-fluid me-2" alt="..."><a class="text-decoration-none text-white" href="url">ACCOUNT</a></td>
                          <td><div class="text-end"><img src="assets/arrow-right.png" class="img-fluid text-end" alt="..."></div></td>
                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          <td><img src="assets/log-out.png" class="img-fluid me-2" alt="..."><a class="text-decoration-none text-white" href="url">LOGOUT</a></td>
                          <td><div class="text-end"><img src="assets/arrow-right.png" class="img-fluid text-end" alt="..."></div></td>
                        </tr>
                      </tbody>
                  </table>
                </div>
            </div>
        </div>
        <!-- navbar -->
        <div class="col-lg-10">
            <div class="row">
                <div class="container-fluid border-bottom border-dark border-1" style="background-color: white;">
                    <div class="row">
                    <div id="hide" class="col-5">
                        <div class="row mt-2 mb-2">
                            <img class="img-fluid" src="assets/konselor.png" style="height: 50px; width: 75px;">
                            <img class="img-fluid" src="assets/NESKAR.png" style="height: 50px; width: 75px;">
                    </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="text-end mt-2 mb-2">
                            <div class="row">
                                <div class="dropdown">
                                <img src="assets/pp.jpeg" class="rounded-5" style="width: 60px; height: 60px;" alt="...">
                                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  Nama Guru BK
                                </button>
                                <ul class="dropdown-menu">
                                  <li class="text-center p-1">
                                  <img class="img-fluid" src="assets/konselor.png" style="height: 20px; width: 25px;">
                                  <img class="img-fluid" src="assets/NESKAR.png" style="height: 20px; width: 25px;">
                                  </li>
                                  <li><a class="dropdown-item" href="#">Beranda</a></li>
                                  <li><a class="dropdown-item" href="#">Data Permintaan</a></li>
                                  <li><a class="dropdown-item" href="#">Account</a></li>
                                  <li><hr class="dropdown-divider"></li>
                                  <li><a class="dropdown-item" href="#">LOGOUT</a></li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- navbar END -->
    <!-- Content -->
    <div class="container-fluid">
        <h1 class="bold mt-3 mb-3">Pengaturan Akun</h1>
        <div style="background-color: white" class="container border border-black border-1 rounded-5 mb-5">
            <div class="row">
                <div class="col-lg-2">
                    <div class="container-fluid text-center mt-4 mb-4">
                        <button type="button" class="btn btn-outline-primary mb-2" onclick="showForm('umum')">Umum</button><br>
                        <button type="button" class="btn btn-outline-primary mb-2" onclick="showForm('akun')">Akun</button><br>
                        <button type="button" class="btn btn-outline-primary" onclick="showForm('kataSandi')">Kata Sandi</button>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center mb-3 mt-3">
                                <img src="assets/pp.jpeg" class="img-fluid rounded-circle border border-dark border-2" style="width: 200px; height: 200px;" alt="...">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-end">
                                <button type="button" class="btn btn-primary">Upload Foto</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger">Hapus Foto</button>
                            </div>
                        </div>

                        <!-- Umum Form -->
                        <div id="umumForm" class="mt-4 mb-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="nama" class="form-label ps-2">Nama</label>
                                        <input type="text" class="form-control border-0" id="nama" placeholder="Nama">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="nip1" class="form-label ps-2">NIP 1</label>
                                        <input type="text" class="form-control border-0" id="nip1" placeholder="NIP 1">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="nip2" class="form-label ps-2">NIP 2</label>
                                        <input type="text" class="form-control border-0" id="nip2" placeholder="NIP 2">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="jurusan" class="form-label ps-2">Jurusan</label>
                                        <select class="form-select border-0" id="jurusan">
                                            <option selected>Pilih Jurusan</option>
                                            <option value="RPL">RPL</option>
                                            <option value="TKJ">TKJ</option>
                                            <option value="MM">MM</option>
                                            <option value="BC">BC</option>
                                            <option value="TEI">TEI</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Akun Form -->
                        <div id="akunForm" class="mt-4 mb-4 hidden">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="username" class="form-label ps-2">Username</label>
                                        <input type="text" class="form-control border-0" id="username" placeholder="Username">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kata Sandi Form -->
                        <div id="kataSandiForm" class="mt-4 mb-4 hidden">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="currentPassword" class="form-label ps-2">Password Saat Ini</label>
                                        <input type="password" class="form-control border-0" id="currentPassword" placeholder="Password Saat Ini">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="newPassword" class="form-label ps-2">Password Baru</label>
                                        <input type="password" class="form-control border-0" id="newPassword" placeholder="Password Baru">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3 border rounded-2">
                                        <label for="confirmPassword" class="form-label ps-2">Konfirmasi Password</label>
                                        <input type="password" class="form-control border-0" id="confirmPassword" placeholder="Konfirmasi Password">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary mb-5">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content END -->
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
<script>
    function showForm(formId) {
        // Hide all forms
        document.getElementById('umumForm').classList.add('hidden');
        document.getElementById('akunForm').classList.add('hidden');
        document.getElementById('kataSandiForm').classList.add('hidden');

        // Show the selected form
        document.getElementById(formId + 'Form').classList.remove('hidden');
    }
</script>
</body>
</html>

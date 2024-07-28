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
        #hidephone {
            display: none;
        }
    }
</style>
<style>
    @media only screen and (min-width: 992px) {
        #hidepc {
            display: none;
        }
    }
</style>
</head>
<body style="background-color: #F4F4F4; font-family: 'Poppins';">
<div class="container-fluid">
    <div class="row">
        <div id="hide" class="col-2" style="background-color: #323A7F; height: 170vh;">
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
                        <td><img src="assets/home.png" class="img-fluid me-2" alt="..."><a class="text-decoration-none text-white" href="url">DASHBOARD</a></td>
                        <td><div class="text-end"><img src="assets/arrow-right.png" class="img-fluid text-end" alt="..."></div></td>
                        </a>
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
                          <td><img src="assets/admin.png" class="img-fluid me-2" alt="..."><a class="text-decoration-none text-white" href="url">ADMIN</a></td>
                          <td><div class="text-end"><img src="assets/arrow-right.png" class="img-fluid text-end" alt="..."></div></td>
                        </tr>
                      </tbody>
                      <tbody>
                        <tr>
                          <td><img src="assets/editp.png" class="img-fluid me-2" alt="..."><a class="text-decoration-none text-white" href="url">DATA GURU BK</a></td>
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
            <!-- content start-->
            <div class="row">
                <div class="container mt-2">
                    <p class="fw-bold ms-3" style="font-size: 30px;">Menu Admin</p>
                    <div class="container mt-4 mb-4">
                        <div class="container-fluid rounded-2 border border-dark pb-2 pt-2" style="background-color: white;">
                            <table class="table text-dark border-dark">
                                <tbody>
                                  <tr>
                                    <td><a class="text-decoration-none text-dark" href="url">JURUSAN</a></td>
                                    </a>
                                  </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                      <td><a class="text-decoration-none text-dark" href="url">KELAS</a></td>
                                    </tr>
                                  </tbody>
                                  <tbody>
                                    <tr>
                                      <td><a class="text-decoration-none text-dark" href="url">TAHUN AJARAN</a></td>
                                      </a>
                                    </tr>
                                  </tbody>
                                  <tbody>
                                      <tr>
                                        <td><a class="text-decoration-none text-dark" href="url">TINGKAT</a></td>
                                      </tr>
                                    </tbody>
                                    <tbody>
                                        <tr>
                                          <td><a class="text-decoration-none text-dark" href="url">BK JURUSAN</a></td>
                                          </a>
                                        </tr>
                                      </tbody>
                                      <tbody>
                                          <tr>
                                            <td><a class="text-decoration-none text-dark" href="url">DATA SISWA</a></td>
                                          </tr>
                                        </tbody>
                              </table>
                        </div>
                    </div>

            <!-- content end-->
        </div>
    </div>
</div>
<div class="container-fluid p-5 text-white" style="background-color: #132043">
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

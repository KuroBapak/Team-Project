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
        .form-group-custom {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body style="background-color: #F4F4F4; font-family: 'Poppins';">
<div class="container-fluid">
    <!-- navbar start -->
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
        <!-- Navbar end -->
<!-- Content -->
<div class="col-3"></div>
<div class="col-lg-6 rounded-3 border" style="background-color: white;">
    <h3 class="mt-4 mb-3 ms-3 fw-bold">Konseling Kelompok</h3>
    <div class="container">
        <div class="row">
            <div>
                <div class="mb-3 border rounded-2">
                    <label for="studentCount" class="form-label ps-2">Jumlah Siswa</label>
                    <select class="form-select border-0" id="studentCount" onchange="updateForm()">
                        <option selected>Pilih Jumlah Siswa</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                        <option value="4">Four</option>
                        <option value="5">Five</option>
                        <option value="6">Six</option>
                        <option value="7">Seven</option>
                        <option value="8">Eight</option>
                        <option value="9">Nine</option>
                        <option value="10">Ten</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" id="studentFormsContainer"></div><!-- ini muncul input siswa 1-10 pi -->

        <div class="row">
            <div class="col-6">
                <div class="mb-3 border rounded-2">
                    <label for="exampleInputEmail1" class="form-label ps-2">Tanggal</label>
                    <input type="date" class="form-control border-0" id="exampleInputEmail1" placeholder="Pilih Tanggal">
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3 border rounded-2">
                    <label for="exampleInputEmail1" class="form-label ps-2">Tempat</label>
                    <input type="text" class="form-control border-0" id="exampleInputEmail1" placeholder="Tempat Konseling">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="mb-3 border rounded-2">
                    <label for="exampleInputEmail1" class="form-label ps-2">Waktu Mulai</label>
                    <input type="time" class="form-control border-0" id="exampleInputEmail1" placeholder="Pilih Waktu Mulai">
                </div>
            </div>
            <div class="col-6">
                <div class="mb-3 border rounded-2">
                    <label for="exampleInputEmail1" class="form-label ps-2">Waktu Selesai</label>
                    <input type="time" class="form-control border-0" id="exampleInputEmail1" placeholder="Pilih Waktu Selesai">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Deskripsi Permasalahan" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">Tindak lanjut</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Tindak Lanjut" rows="3"></textarea>
        </div>

        <div class="text-end mb-3">
            <button type="button" class="btn btn-outline-primary">Kembali</button>
            <button type="button" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>
<div class="col-3"></div>
<!-- Content end -->

<script>
    function updateForm() {
        var studentCount = document.getElementById('studentCount').value;
        var studentFormsContainer = document.getElementById('studentFormsContainer');
        studentFormsContainer.innerHTML = '';

        for (var i = 1; i <= studentCount; i++) {
            var formGroup = document.createElement('div');
            formGroup.className = 'col-6 mb-3 border rounded-2 p-2';

            var label = document.createElement('label');
            label.className = 'form-label ps-2';
            label.textContent = 'Nama Siswa ' + i;

            var input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control border-0';
            input.placeholder = 'Nama Siswa ' + i;

            formGroup.appendChild(label);
            formGroup.appendChild(input);
            studentFormsContainer.appendChild(formGroup);
        }
    }
</script>


        <div class="container-fluid p-5 text-white" style="background-color: #132043;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-1"></div>
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
                    <div class="col-1"></div>
                </div>
                <hr class="rounded-1" style="border: 1px solid white;">
                <p class="text-center">@ Copyright TechTastic 2024</p>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>
</html>

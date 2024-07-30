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

        #drop-area {
            border: 2px dashed #ccc;
            border-radius: 20px;
            width: 350px;
            height: 200px;
            padding: 20px;
            font-family: sans-serif;
            margin: 20px auto;
            text-align: center;
        }

        #drop-area.highlight {
            border-color: #132043;
        }

        #fileElem {
            display: none;
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
                <div class="col-3">
                </div>
                <div class="col-lg-6 rounded-3 border" style="background-color: white;">
                    <h3 class="mt-4 mb-3 ms-3 fw-bold">Konseling Individu</h3>
                    <div class="container">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3 border rounded-2">
                                    <label for="exampleInputEmail1" class="form-label ps-2">Nama Siswa</label>
                                    <select class="form-select border-0" aria-label="Default select example">
                                        <option selected>Pilih Siswa</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                      </select>
                                  </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3 border rounded-2">
                                    <label for="exampleInputEmail1" class="form-label ps-2">Kelas</label>
                                    <select class="form-select border-0" aria-label="Default select example">
                                        <option selected>Pilih Kelas</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                      </select>
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3 border rounded-2">
                                    <label for="exampleInputEmail1" class="form-label ps-2">Tanggal</label>
                                    <input type="date" class="form-control border-0" id="exampleInputEmail1" placeholder="Pilih Tanggal" aria-describedby="emailHelp">
                                  </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3 border rounded-2">
                                    <label for="exampleInputEmail1" class="form-label ps-2">Tempat</label>
                                    <input type="email" class="form-control border-0" id="exampleInputEmail1" placeholder="Tempat Konseling" aria-describedby="emailHelp">
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3 border rounded-2">
                                    <label for="exampleInputEmail1" class="form-label ps-2">Waktu Mulai</label>
                                    <input type="time" class="form-control border-0" id="exampleInputEmail1" placeholder="Pilih Waktu Mulai" aria-describedby="emailHelp">
                                  </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3 border rounded-2">
                                    <label for="exampleInputEmail1" class="form-label ps-2">Waktu Selesai</label>
                                    <input type="time" class="form-control border-0" id="exampleInputEmail1" placeholder="Pilih Waktu Selesai" aria-describedby="emailHelp">
                                  </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3 border rounded-2">
                                    <label for="exampleInputEmail1" class="form-label ps-2">Nama Guru</label>
                                    <input type="email" class="form-control border-0" id="exampleInputEmail1" placeholder="Nama Guru BK" aria-describedby="emailHelp">
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

                          <div class="container fluid">
                            <p>Foto Kegiatan (png, jpg, jpeg)</p>
                              <div id="drop-area">
                                <p>Drag & Drop your files here</p>
                                <img src="assets/img3.png" class="img-fluid" style="height: 100px" alt="...">
                                <input type="file" id="fileElem" multiple>
                            </div>
                            <p class="text-center">Atau</p>
                          </div>

                        <div class="container-fluid text-center">
                            <button id="fileSelect" type="button" class="btn btn-light shadow p-3 mb-5 bg-body-tertiary rounded border">Pilih Gambar</button>
                        </div>



                          <div class="text-end mb-3">
                              <button type="button" class="btn btn-outline-primary">Kembali</button>
                              <button type="button" class="btn btn-primary">Simpan</button>
                          </div>
                    </div>
                </div>
                <div class="col-3">

                </div>
        <!-- Content end -->
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
</div>

<script>
    let dropArea = document.getElementById('drop-area');
    let fileElem = document.getElementById('fileElem');
    let fileSelect = document.getElementById('fileSelect');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add('highlight'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove('highlight'), false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        let dt = e.dataTransfer;
        let files = dt.files;

        handleFiles(files);
    }

    function handleFiles(files) {
        ([...files]).forEach(uploadFile);
    }

    function uploadFile(file) {
        let url = 'urlnya';//up disini pi
        let formData = new FormData();
        formData.append('file', file);

        fetch(url, {
            method: 'POST',//pake method lu ae pi
            body: formData
        }).then(() => {
            console.log('File uploaded successfully');
        }).catch(() => {
            console.log('File upload failed');
        });
    }

    fileSelect.addEventListener('click', () => fileElem.click());

    fileElem.addEventListener('change', (e) => {
        let files = e.target.files;
        handleFiles(files);
    });
</script>
<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
</body>
</html>

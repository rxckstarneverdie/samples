
<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doktor') {
    header('Location: ./auth/login.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Hastane Randevu Sistemi</title>
    <link href="../../assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://kit.fontawesome.com/285e1ad898.js" crossorigin="anonymous"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">Hastane Sistemi <sub>Yönetici</sub> </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./index.php">Anasayfa</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="hastalar.php">Hasta Listesi <sup><b class="text-success">Aktif</b></sup></a>

            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Çıkış Yap</a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="./auth/logout.php">Çıkış Yap</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <div class="container-fluid">
                <p class="mt-4 text-muted">• Hastane Yönetim / Yönetici / Hasta Listesi/ Doktor Profili</p>
                <h2 class="mt-4">Doktor Profili</h2>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Doktor Profili</h5>
                                <p class="card-text"><b>TC Kimlik Numarası:</b> <span id="tcnoo"></span></p>
                                <p class="card-text"><b>İsim Soyisim:</b> <span id="adsoyad"></span></p>
                                <p class="card-text"><b>Uzmanlık Alanı:</b> <span id="uzmanlikalani"></span></p>
                                <p class="card-text"><b>Çalıştığı Hastane:</b> <span id="calistigihastane"></span></p>
                                <button type="button" class="btn btn-primary" onclick="bilgiDuzenle();">Bilgileri Düzenle</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered" id="randevuTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tarih</th>
                                            <th>Hasta Ad/Soyad</th>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../../assets/js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        function DoktorBilgileriGetir() {
            var tcno = localStorage.getItem("tcno");
            $.ajax({
                url: "../../system/functions.php",
                type: "POST",
                data: { tcno: <?php echo $_SESSION['tcno']; ?>, action: "DoktorBilgileriGetir" },
                success: function (response) {
                    var hasta = JSON.parse(response);
                    $("#tcnoo").text(hasta[0].tcno);
                    $("#adsoyad").text(hasta[0].ad + " " + hasta[0].soyad);
                    $("#uzmanlikalani").text(hasta[0].uzmanlikalani);
                    $("#calistigihastane").text(hasta[0].calistigihastane);
                },
            });

        }
        
        function randevuBilgileriGetirDoktor() {
            var tcno = localStorage.getItem("tcno");
            $.ajax({
                url: "../../system/functions.php",
                type: "POST",
                data: { tcno: <?php echo $_SESSION['tcno']; ?>, action: "randevuBilgileriGetirDoktor" },
                success: function (response) {
                    var randevular = JSON.parse(response);
                    var table = $("#randevuTable").DataTable();
                    for (var i = 0; i < randevular.length; i++) {
                        table.row.add([
                            randevular[i].id,
                            randevular[i].tarih,
                            randevular[i].ad + " " + randevular[i].soyad,
                            randevular[i].uzmanlikalani,
                            randevular[i].calistigihastane,
                        ]);

                    }
                    table.draw();
                },
            });
        }

        function randevuSil(id) {
            $.ajax({
                url: "../../system/functions.php",
                type: "POST",
                data: { id: id, action: "randevuSil" },
                success: function (response) {
                    if (response == "success") {
                        Toastify({
                            text: "Randevu başarıyla silindi.",
                            duration: 3000,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #007bb5, #00d4a1)",
                        }).showToast();

                        var table = $("#randevuTable").DataTable();
                        table.clear().draw();
                        randevuBilgileriGetirDoktor();

                    } else {
                        alert("Randevu silinirken bir hata oluştu.");
                    }
                },
            });
        }

                    
        function raporBilgileriGetir() {
            var tcno = localStorage.getItem("tcno");
            $.ajax({
                url: "../../system/functions.php",
                type: "POST",
                data: { tcno: <?php echo $_SESSION['tcno']; ?>, action: "raporBilgileriGetir" },
                success: function (response) {
                    var raporlar = JSON.parse(response);
                    var table = $("#raporTable").DataTable();
                    for (var i = 0; i < raporlar.length; i++) {
                        table.row.add([
                            raporlar[i].id,
                            raporlar[i].tarih,
                            raporlar[i].ad + " " + raporlar[i].soyad,
                            '<button type="button" class="btn btn-danger" onclick="raporGoruntule(\'' + raporlar[i].rapor + '\')"><i class="fa-solid fa-eye"></i></button> <button type="button" class="btn btn-danger" onclick="raporSil(' + raporlar[i].id + ')"><i class="fa-solid fa-trash"></i></button>',
                        ]); 
                    }
                    table.draw();
                },
            });
        }

        function raporSil(id) {
            $.ajax({
                url: "../../system/functions.php",
                type: "POST",
                data: { id: id, action: "raporSil" },
                success: function (response) {
                    if (response == "success") {
                        Toastify({
                            text: "Rapor başarıyla silindi.",
                            duration: 3000,
                            gravity: "bottom",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #007bb5, #00d4a1)",
                        }).showToast();

                        var table = $("#raporTable").DataTable();
                        table.clear().draw();
                        raporBilgileriGetir();

                    } else {
                        alert("Rapor silinirken bir hata oluştu.");
                    }
                },
            });
        }


        function raporGoruntule(rapor) {
            Swal.fire({
                title: 'Tıbbi Rapor',
                html: '<img src="' + rapor + '" style="width: 100%;">',
                confirmButtonText: 'Kapat',
            });
            
        }

        function yeniRapor() {
            Swal.fire({
                title: 'Yeni Rapor Oluştur',
                html: '<input type="file" id="rapor" class="form-control">',
                showCancelButton: true,
                confirmButtonText: 'Kaydet',
                cancelButtonText: 'İptal',
                preConfirm: () => {
                    var file = document.getElementById('rapor').files[0];
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function () {
                        $.ajax({
                            url: "../../system/functions.php",
                            type: "POST",
                            data: { tcno: <?php echo $_SESSION['tcno']; ?>, rapor: reader.result, action: "yeniRapor" },
                            success: function (response) {
                                if (response == "success") {
                                    Toastify({
                                        text: "Rapor başarıyla oluşturuldu.",
                                        duration: 3000,
                                        gravity: "bottom",
                                        position: "right",
                                        backgroundColor: "linear-gradient(to right, #007bb5, #00d4a1)",
                                    }).showToast();

                                    var table = $("#raporTable").DataTable();
                                    table.clear().draw();
                                    raporBilgileriGetir();
                                } else {
                                    alert("Rapor oluşturulurken bir hata oluştu.");
                                }
                            },
                        });
                    };
                }
            });
        }

        function randevuOlustur() {
    // doktor listesini çek
    $.ajax({
        url: "../../system/functions.php",
        type: "POST",
        data: { action: "doktorListesiGetir" },
        success: function (response) {
            var doktorlar = JSON.parse(response);
            Swal.fire({
                title: 'Yeni Randevu Oluştur',
                html: '<input type="date" id="tarih" class="form-control" placeholder="Tarih"><br><select id="doktor" class="form-control"><option value="0">Doktor Seçiniz</option></select><br>',
                showCancelButton: true,
                confirmButtonText: 'Kaydet',
                cancelButtonText: 'İptal',
                preConfirm: () => {
                    var tarih = document.getElementById('tarih').value;
                    $.ajax({
                        url: "../../system/functions.php",
                        type: "POST",
                        data: { tcno: <?php echo $_SESSION['tcno']; ?>, tarih: tarih, doktorid: document.getElementById('doktor').value, action: "randevuOlustur" },
                        success: function (response) {
                            if (response == "success") {
                                Toastify({
                                    text: "Randevu başarıyla oluşturuldu.",
                                    duration: 3000,
                                    gravity: "bottom",
                                    position: "right",
                                    backgroundColor: "linear-gradient(to right, #007bb5, #00d4a1)",
                                }).showToast();

                                var table = $("#randevuTable").DataTable();
                                table.clear().draw();
                                randevuBilgileriGetirDoktor();
                            } else {
                                alert("Randevu oluşturulurken bir hata oluştu.");
                            }
                        },
                    });
                }
            });

            var doktorSelect = document.getElementById('doktor');
            for (var i = 0; i < doktorlar.length; i++) {
                var option = document.createElement('option');
                option.value = doktorlar[i].id;
                option.text = doktorlar[i].ad + " " + doktorlar[i].soyad;
                doktorSelect.appendChild(option);
            }
        },
    });
}

function bilgiDuzenle() {
    $.ajax({
        url: "../../system/functions.php",
        type: "POST",
        data: { tcno: <?php echo $_SESSION['tcno']; ?>, action: "DoktorBilgileriGetir" },
        success: function (response) {
            var doktor = JSON.parse(response);
            Swal.fire({
                title: 'Doktor Bilgileri Düzenle',
                html: '<input type="text" id="ad" class="form-control" placeholder="Ad" value="' + doktor[0].ad + '"><br><input type="text" id="soyad" class="form-control" placeholder="Soyad" value="' + doktor[0].soyad + '"><br><input type="text" id="uzmanlikalanii" class="form-control" placeholder="Uzmanlık Alanı" value="' + doktor[0].uzmanlikalani + '"><br><input type="text" id="calistigihastanee" class="form-control" placeholder="Çalıştığı Hastane" value="' + doktor[0].calistigihastane + '"><br><input type="password" id="sifre" class="form-control" placeholder="Şifre"><br>',
                showCancelButton: true,
                confirmButtonText: 'Kaydet',
                cancelButtonText: 'İptal',
                preConfirm: function() {
                    var ad = document.getElementById('ad').value;
                    var soyad = document.getElementById('soyad').value;
                    var uzmanlikalani = document.getElementById('uzmanlikalanii').value;
                    var calistigihastane = document.getElementById('calistigihastanee').value;
                    var sifre = document.getElementById('sifre').value;

                    $.ajax({
                        url: "../../system/functions.php",
                        type: "POST",
                        data: { tcno: <?php echo $_SESSION['tcno']; ?>, ad: ad, soyad: soyad, uzmanlikalani: uzmanlikalani, calistigihastane: calistigihastane, sifre: sifre, action: "doktorBilgileriGuncelle" },
                        success: function (response) {
                            if (response == "success") {
                                Toastify({
                                    text: "Doktor bilgileri başarıyla güncellendi.",
                                    duration: 3000,
                                    gravity: "bottom",
                                    position: "right",
                                    backgroundColor: "linear-gradient(to right, #007bb5, #00d4a1)",
                                }).showToast();
                                DoktorBilgileriGetir();
                            } else {
                                Toastify({
                                    text: "Doktor bilgileri güncellenirken bir hata oluştu.",
                                    duration: 3000,
                                    gravity: "bottom",
                                    position: "right",
                                    backgroundColor: "linear-gradient(to right, #ff0000, #ff5e00)",
                                }).showToast();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                            Toastify({
                                text: "Bir hata oluştu. Daha fazla bilgi için konsolu kontrol edin.",
                                duration: 3000,
                                gravity: "bottom",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff0000, #ff5e00)",
                            }).showToast();
                        }
                    });
                }
            });

        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            Toastify({
                text: "Bir hata oluştu. Daha fazla bilgi için konsolu kontrol edin.",
                duration: 3000,
                gravity: "bottom",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ff0000, #ff5e00)",
            }).showToast();
        }
    });
}



        
        $(document).ready(function () {
            DoktorBilgileriGetir();
            randevuBilgileriGetirDoktor();
        });


    </script>
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    </head>
    <body>
        <div class="d-flex" id="wrapper">
            <!-- Sidebar-->
            <div class="border-end bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom bg-light">Hastane Sistemi <sub>Yönetici</sub> </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./index.php">Anasayfa</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="hastalar.php">Hasta Listesi </a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="#!">Doktor Listesi <sup><b class="text-success">Aktif</b></sup></a>
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
                    <p class="mt-4 text-muted">• Hastane Yönetim / Yönetici / Doktor Listesi</p>
                    <h2 class="mt-4">Doktor Listesi</h1>
                    <button class="btn btn-primary" style="margin-bottom: 20px; float: right;" onclick="DoktorEkle()">Doktor Ekle</button>
                    <table id="example" class="display" style="width:100%">
    <thead>
        <tr>
            <th>TC</th>
            <th>Ad</th>
            <th>Soyad</th>
            <th>Uzmanlık Alanı</th>
            <th>Çalıştığı Hastane</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <!-- Burası API yanıtıyla doldurulacak -->
    </tbody>
</table>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="../../assets/js/script.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script>
            $(document).ready(function() {
                $('#example').DataTable({
        ajax: {
            url: '../../system/functions.php',
            type: 'POST',
            data: {
                action: 'getDoktorlar'
            },
            dataSrc: '', // Since your API returns an array directly
            success: function(data) {
                populateDataTable(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching data:', textStatus, errorThrown);
                $('#example').DataTable().clear().draw(); 
                $('#example').append('<tr><td colspan="5">Error retrieving data.</td></tr>'); 
            }
        },
        columns: [
            { data: 'tcno' }, // Assuming 'id' is unique and needed for identification
            { data: 'ad' },
            { data: 'soyad' },
            { data: 'uzmanlikalani' },
            { data: 'calistigihastane' },
            {
                data: 'id',
                render: function(data, type, row) {
                    return '<button class="btn btn-primary" onclick="window.location.href=\'doktor.php?tcno=' + row.tcno + '\'">Düzenle</button>' +
                        '<button class="btn btn-danger" onclick="DoktorSil(\'' + row.tcno + '\')">Sil</button>';
                }
            }
        ]
    });
});

function populateDataTable(data) {
  $('#example').DataTable().clear().draw(); 
  $('#example').DataTable().rows.add(data).draw(); 
}

function DoktorSil(tcno) {
    if (confirm('Doktor silinecek. Emin misiniz?')) {
        $.ajax({
            url: '../../system/functions.php',
            type: 'POST',
            data: {
                action: 'DoktorSil',
                tcno: tcno
            },
            success: function(data) {
                if (data == 'success') {
                    Toastify({
                        text: 'Doktor başarıyla silindi.',
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'right',
                        backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                        stopOnFocus: true,
                    }).showToast();

                    $('#example').DataTable().ajax.reload();
                } else {
                    Toastify({
                        text: data,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'right',
                        backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                        stopOnFocus: true,
                    }).showToast();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error deleting data:', textStatus, errorThrown);
                Toastify({
                    text: 'Doktor silinirken bir hata oluştu. Hata: ' + textStatus,
                    duration: 3000,
                    gravity: 'bottom',
                    position: 'right',
                    backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                    stopOnFocus: true,
                }).showToast();
            }
        });
    }
}

function DoktorEkle() {
    Swal.fire({
        title: 'Doktor Ekle',
        html: '<input id="tcno" class="swal2-input" placeholder="TC Kimlik No" type="text">' +
            '<input id="ad" class="swal2-input" placeholder="Ad" type="text">' +
            '<input id="soyad" class="swal2-input" placeholder="Soyad" type="text">' +
            '<input id="uzmanlikalani" class="swal2-input" placeholder="Uzmanlık Alanı" type="text">' +
            '<input id="calistigihastane" class="swal2-input" placeholder="Çalıştığı Hastane" type="text">' +
            '<input id="sifre" class="swal2-input" placeholder="Şifre" type="password">',

        showCancelButton: true,
        confirmButtonText: 'Ekle',
        cancelButtonText: 'İptal',
        preConfirm: () => {
            return [
                document.getElementById('tcno').value,
                document.getElementById('ad').value,
                document.getElementById('soyad').value,
                document.getElementById('uzmanlikalani').value,
                document.getElementById('calistigihastane').value,
                document.getElementById('sifre').value
            ]
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../system/functions.php',
                type: 'POST',
                data: {
                    action: 'DoktorEkle',
                    tcno: result.value[0],
                    ad: result.value[1],
                    soyad: result.value[2],
                    uzmanlikalani: result.value[3],
                    calistigihastane: result.value[4],
                    sifre: result.value[5]
                },
                success: function(data) {
                    if (data == 'success') {
                        Toastify({
                            text: 'Doktor başarıyla eklendi.',
                            duration: 3000,
                            gravity: 'bottom',
                            position: 'right',
                            backgroundColor: 'linear-gradient(to right, #00b09b, #96c93d)',
                            stopOnFocus: true,
                        }).showToast();

                        $('#example').DataTable().ajax.reload();
                    } else {
                        Toastify({
                            text: data,
                            duration: 3000,
                            gravity: 'bottom',
                            position: 'right',
                            backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                            stopOnFocus: true,
                        }).showToast();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error adding data:', textStatus, errorThrown);
                    Toastify({
                        text: 'Doktor eklenirken bir hata oluştu. Hata: ' + textStatus,
                        duration: 3000,
                        gravity: 'bottom',
                        position: 'right',
                        backgroundColor: 'linear-gradient(to right, #ff5f6d, #ffc371)',
                        stopOnFocus: true,
                    }).showToast();

                }
            });
        }
    });
}

        </script>

    </body>
</html>

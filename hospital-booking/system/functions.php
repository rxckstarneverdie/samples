<?php

require_once '../server/db.php';


$action = isset($_POST['action']) ? $_POST['action'] : '';

if (function_exists($action)) {
    echo $action();
} else {
    header('HTTP/1.1 403 Forbidden');
}

function loginAdmin() {

    global $conn;
    
    $tcno = $_POST['tcno'];
    $password = $_POST['password'];

    if (empty($tcno) || empty($password)) {
        return 'error';
    }

    $sql = "SELECT * FROM yonetici WHERE tcno = '$tcno' AND sifre = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['tcno'] = $tcno;
        $_SESSION['doktorid'] = $result->fetch_assoc()['id'];
        $_SESSION['role'] = 'admin';
        $_SESSION['loggedin'] = true;

        echo 'success';
    } else {
        echo 'error';
    }

}

function loginUser() {

    global $conn;
    
    $tcno = $_POST['tcno'];
    $password = $_POST['password'];

    if (empty($tcno) || empty($password)) {
        return 'error';
    }

    $sql = "SELECT * FROM hastalar WHERE tcno = '$tcno' AND sifre = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['tcno'] = $tcno;
        $_SESSION['role'] = 'user';
        $_SESSION['loggedin'] = true;

        echo 'success';
    } else {
        echo 'error';
    }

}

function loginDoktor() {

    global $conn;
    
    $tcno = $_POST['tcno'];
    $password = $_POST['password'];

    if (empty($tcno) || empty($password)) {
        return 'error';
    }

    $sql = "SELECT * FROM doktorlar WHERE tcno = '$tcno' AND sifre = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['tcno'] = $tcno;
        $_SESSION['doktorid'] = $result->fetch_assoc()['id'];
        $_SESSION['role'] = 'doktor';
        $_SESSION['loggedin'] = true;

        echo 'success';
    } else {
        echo 'error';
    }

}



function getHastalar() {
    
        global $conn;
    
        $sql = "SELECT tcno, ad, soyad, telefonnumarasi FROM hastalar";
        $result = $conn->query($sql);

        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        echo json_encode($data);
}

function hastaBilgileriGetir() {
    
        global $conn;
    
        $tcno = $_POST['tcno'];

        $sql = "SELECT * FROM hastalar WHERE tcno = '$tcno'";
        $result = $conn->query($sql);

        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        echo json_encode($data);
}

function doktorListesiGetir() {
    
        global $conn;
    
        $sql = "SELECT id, ad, soyad, uzmanlikalani, calistigihastane FROM doktorlar";
        $result = $conn->query($sql);

        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        echo json_encode($data);
}


function randevuBilgileriGetir() {
    
        global $conn;
    
        $tcno = $_POST['tcno'];
        $sql = "SELECT doktorlar.ad, doktorlar.soyad, doktorlar.uzmanlikalani, randevular.tarih, doktorlar.calistigihastane, randevular.id
        FROM hastalar
        JOIN randevular ON hastalar.id = randevular.hastaid
        JOIN doktorlar ON randevular.doktorid = doktorlar.id
        WHERE hastalar.tcno = '$tcno'";

        

        $result = $conn->query($sql);

        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        echo json_encode($data);
}

function randevuSil() {
    
        global $conn;
    
        $id = $_POST['id'];

        $sql = "DELETE FROM randevular WHERE id = $id";
        $result = $conn->query($sql);

        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
}


function raporBilgileriGetir() {
    
    global $conn;

    $tcno = $_POST['tcno'];
    
    $sql = "SELECT doktorlar.ad, doktorlar.soyad, tibbiraporlar.tarih, tibbiraporlar.id, tibbiraporlar.rapor
    FROM hastalar
    JOIN tibbiraporlar ON hastalar.id = tibbiraporlar.hastaid
    JOIN doktorlar ON tibbiraporlar.doktorid = doktorlar.id
    WHERE hastalar.tcno = '$tcno' AND tibbiraporlar.isadmin = 0";

    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Resetting $sql for the second query
    $sql = "SELECT yonetici.ad, yonetici.soyad, tibbiraporlar.tarih, tibbiraporlar.id, tibbiraporlar.rapor
    FROM hastalar
    JOIN tibbiraporlar ON hastalar.id = tibbiraporlar.hastaid
    JOIN yonetici ON tibbiraporlar.doktorid = yonetici.id
    WHERE hastalar.tcno = '$tcno' AND tibbiraporlar.isadmin = 1";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
}

function yeniRapor() {
    
    global $conn;

    session_start();

    $tcno = $_POST['tcno'];
    $doktorid = $_SESSION['doktorid'];
    $tarih = date('Y-m-d');
    $rapor = $_POST['rapor'];
    $role = $_SESSION['role'];
    
    if ($role == 'admin') {
        $sql = "INSERT INTO tibbiraporlar (hastaid, doktorid, tarih, rapor, isadmin) VALUES ((SELECT id FROM hastalar WHERE tcno = '$tcno'), $doktorid, '$tarih', '$rapor', 1)";
    } else {
        $sql = "INSERT INTO tibbiraporlar (hastaid, doktorid, tarih, rapor, isadmin) VALUES ((SELECT id FROM hastalar WHERE tcno = '$tcno'), $doktorid, '$tarih', '$rapor', 0)";
    }
    
    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}

function raporSil() {
    
    global $conn;

    $id = $_POST['id'];

    $sql = "DELETE FROM tibbiraporlar WHERE id = $id";
    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}

function randevuOlustur() {
    
    global $conn;

    session_start();

    $tcno = $_POST['tcno'];
    $doktorid = $_POST['doktorid'];
    $tarih = $_POST['tarih'];
    
    $sql = "INSERT INTO randevular (hastaid, doktorid, tarih) VALUES ((SELECT id FROM hastalar WHERE tcno = '$tcno'), $doktorid, '$tarih')";

    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}

function hastaBilgileriDuzenle() {
    
    global $conn;

    $tcno = $_POST['tcno'];
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $telno = $_POST['telno'];
    $dogumtarihi = $_POST['dogumtarihi'];
    $adres = $_POST['adres'];
    $cinsiyet = $_POST['cinsiyet'];

    $sql = "UPDATE hastalar SET ad = '$ad', soyad = '$soyad', telefonnumarasi = '$telno', dogumtarihi = '$dogumtarihi', adres = '$adres', cinsiyet = '$cinsiyet' WHERE tcno = '$tcno'";
    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }

}

function hastaSil() {
    
    global $conn;

    $tcno = $_POST['tcno'];

    $sql = "SELECT * FROM randevular WHERE hastaid = (SELECT id FROM hastalar WHERE tcno = '$tcno')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo 'Randevusu olan hastaları silemezsiniz.';
        return;
    }

    $sql = "DELETE FROM hastalar WHERE tcno = '$tcno'";
    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'Hasta silinemedi.';
    }
}

function hastaEkle() {

    $tcno = $_POST['tcno'];
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $telno = $_POST['telefonnumarasi'];
    $dogumtarihi = $_POST['dogumtarihi'];
    $adres = $_POST['adress'];
    $cinsiyet = $_POST['cinsiyet'];
    $sifre = $_POST['sifre'];

    global $conn;

    $sql = "INSERT INTO hastalar (tcno, ad, soyad, telefonnumarasi, dogumtarihi, adres, cinsiyet, sifre) VALUES ('$tcno', '$ad', '$soyad', '$telno', '$dogumtarihi', '$adres', '$cinsiyet', '$sifre')";
    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }

}

function getDoktorlar() {
    
    global $conn;

    $sql = "SELECT tcno, ad, soyad, uzmanlikalani, calistigihastane FROM doktorlar";
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
}

function DoktorBilgileriGetir() {
    
    global $conn;

    $tcno = $_POST['tcno'];

    $sql = "SELECT tcno, ad, soyad, uzmanlikalani, calistigihastane FROM doktorlar WHERE tcno = '$tcno'";

    $result = $conn->query($sql);

    $data = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);

    
}

function doktorBilgileriGuncelle() {
    
    global $conn;

    $tcno = $_POST['tcno'];
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $uzmanlikalani = $_POST['uzmanlikalani'];
    $calistigihastane = $_POST['calistigihastane'];

    if(isset($_POST['sifre'])){
        $sifre = $_POST['sifre'];
        $sql = "UPDATE doktorlar SET sifre = '$sifre', ad = '$ad', soyad = '$soyad', uzmanlikalani = '$uzmanlikalani', calistigihastane = '$calistigihastane' WHERE tcno = '$tcno'";
    } else {
        $sql = "UPDATE doktorlar SET ad = '$ad', soyad = '$soyad', uzmanlikalani = '$uzmanlikalani', calistigihastane = '$calistigihastane' WHERE tcno = '$tcno'";
    }

    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }

}

function DoktorSil() {
    
    global $conn;

    $tcno = $_POST['tcno'];

    $sql = "SELECT * FROM randevular WHERE doktorid = (SELECT id FROM doktorlar WHERE tcno = '$tcno')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo 'Randevusu olan doktorları silemezsiniz.';
        return;
    }

    $sql = "DELETE FROM doktorlar WHERE tcno = '$tcno'";
    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'Doktor silinemedi.';
    }
}

function DoktorEkle() {


    $tcno = $_POST['tcno'];
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $uzmanlikalani = $_POST['uzmanlikalani'];
    $calistigihastane = $_POST['calistigihastane'];
    $sifre = $_POST['sifre'];

    global $conn;

    $sql = "INSERT INTO doktorlar (tcno, ad, soyad, uzmanlikalani, calistigihastane, sifre) VALUES ('$tcno', '$ad', '$soyad', '$uzmanlikalani', '$calistigihastane', '$sifre')";
    $result = $conn->query($sql);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }

}

function randevuBilgileriGetirDoktor() {
    
    global $conn;

    $tcno = $_POST['tcno'];
    $sql = "SELECT hastalar.ad, hastalar.soyad, hastalar.tcno, randevular.tarih, randevular.id
    FROM doktorlar
    JOIN randevular ON doktorlar.id = randevular.doktorid
    JOIN hastalar ON randevular.hastaid = hastalar.id
    WHERE doktorlar.tcno = '$tcno'";

    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    echo json_encode($data);
}


?>

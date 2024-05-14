<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-primary {
            border-radius: 20px;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Hastane Girişi</h2>
            <form>
                <div class="form-group">
                    <input type="text" class="form-control" id="tcno" placeholder="TC Kimlik Numarası" autofocus autocomplete="off">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" placeholder="Şifre">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
            </form>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.js"></script>

<script>
const tcno = document.getElementById('tcno');
const password = document.getElementById('password');

document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();

    if (tcno.value.trim() === '' || password.value.trim() === '') {
        Toastify({
            text: "Lütfen tüm alanları doldurun.",
            duration: 3000,
            close: true,
            gravity: "top",
            position: 'right',
            backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)"
        }).showToast();
    } else {
        $.ajax({
            url: '../../../system/functions.php',
            type: 'POST',
            data: {
                tcno: tcno.value,
                password: password.value,
                action: 'loginAdmin'
            },
            success: function(response) {
                if (response === 'success') {
                    Toastify({
                        text: "Giriş başarılı, yönlendiriliyorsunuz...",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                    }).showToast();
                    setTimeout(() => {
                        window.location.href = '../index.php';
                    }, 3000);
                } else {
                    Toastify({
                        text: "Giriş bilgileriniz hatalı.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)"
                    }).showToast();
                }
            }
        });
    }
});
</script>
</html>
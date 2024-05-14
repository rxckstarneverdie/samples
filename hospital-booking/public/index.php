<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hoşgeldiniz</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h3>Hoşgeldiniz</h3>
            <p>Giriş yapacağınız alanı seçiniz:</p>
          </div>
          <div class="card-body text-center">
            <div class="row">
              <div class="col-md-4">
                <a href="./doktor/auth/login.php" class="btn btn-primary btn-block">Doktor</a>
              </div>
              <div class="col-md-4">
                <a href="./hasta/auth/login.php" class="btn btn-success btn-block">Hasta</a>
              </div>
              <div class="col-md-4">
                <a href="./admin/auth/login.php" class="btn btn-info btn-block">Yönetici</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

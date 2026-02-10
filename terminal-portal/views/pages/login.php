<?php
start_session();
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Terminal Portal | Login</title>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"
    />
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <b>Terminal Portal</b> Giriş
        </div>
        <div class="card-body">
          <?php if ($error): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
          <?php endif; ?>
          <form method="post" action="/login">
            <div class="input-group mb-3">
              <input type="email" name="email" class="form-control" placeholder="E-posta" required />
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" class="form-control" placeholder="Şifre" required />
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style_login.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="cdnjs.cloudflare.com">
    <title>Login</title>
  </head>

  <body>
    <div class="top-bar"></div>

    <div class="container">
      <i class="fa-regular fa-circle-user fa-8x"></i>

      <h2 class="title">Login</h2>

      <form class="login-form" action="php/p_login.php" method="POST">
        <div class="form-group">
          <label>Email</label>
          <input type="text" name="email" placeholder="Masukkan email anda" required />
        </div>

        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Masukkan password" required />
        </div>

        <button type="submit" class="btn-login" name="login">Login</button>
      </form>


      <p class="register-text">
        Belum Punya Akun? <a href="register.php">Daftar Sekarang</a>
      </p>
    </div>
  </body>
</html>
<script src="https://kit.fontawesome.com/571d257fa4.js" crossorigin="anonymous"></script>
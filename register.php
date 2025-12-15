<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_register.css">
    <title>Register</title>
</head>
<body>

<div class="register-container">

    <div class="header-bar"></div>

    <div class="register-card">
        <i class="fa-solid fa-user-plus fa-5x"></i>

        <h2>Daftar Akun</h2>

<form action="php/p_register.php" method="POST">
    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username" required>
    </div>

    <div class="input-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email" required>
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>
    </div>

    <div class="input-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Ulangi password" required>
    </div>

    <button type="submit" class="btn-register" name="register">Register</button>
</form>


</div>

</body>
</html>
<script src="https://kit.fontawesome.com/571d257fa4.js" crossorigin="anonymous"></script>
<?php
session_start();

include 'php/koneksi.php'; // koneksi ke database
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Last Watch - Super Sale</title>
</head>

<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <img src="img/logo_toko.png" class="logo" />
        <input type="text" class="search" placeholder="Cari jam...">
        <div>
        <a href="index.php">Beranda</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="pesanan.php">Pesanan</a>
    <?php if (!isset($_SESSION['id_user'])): ?>
        <!-- JIKA BELUM LOGIN -->
        <a href="login.php" class="btn-login">Login</a>
    <?php else: ?>
        <!-- JIKA SUDAH LOGIN -->
        <a href="logout.php" class="btn-logout">Logout</a>
    <?php endif; ?>
    </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-text">
            <h1>SUPER SALE!!!</h1>
            <h2>12.12</h2>
            <p>Just in Last Watch</p>
        </div>
        <div class="hero-image">
            <img src="img/rolex.png" alt="">
        </div>
    </section>

    <!-- PRODUK GRID -->
    <section class="produk-section">
        <h2 class="section-title">Produk Terbaru</h2>

        <div class="produk-grid">

        <?php
        // Ambil semua produk
        $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");

        while ($row = mysqli_fetch_assoc($query)) {
            ?>

        <div class="card">
    <div class="card-img-wrapper">
        <img src="img/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_produk']; ?>">
    </div>

    <div class="card-body">
        <h3><?php echo $row['nama_produk']; ?></h3>
        
        <p class="harga">IDR <?php echo number_format($row['harga_produk'], 0, ',', '.'); ?></p>

        <div class="card-actions">
            <button class="btn-outline" onclick="location.href='produk.php?id=<?php echo $row['id']; ?>'">
                Detail
            </button>
            
            <button class="btn-fill" onclick="location.href='php/tambah_keranjang.php?id=<?php echo $row['id']; ?>'">
                + Keranjang
            </button>
        </div>
    </div>
</div>

        <?php } ?>

        </div>
    </section>

</body>

<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="#">Home</a>
            <a href="#">Collection</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </div>
        <p class="copyright">&copy; 2025 Last Watch. All rights reserved.</p>
    </div>
</footer>
</html>

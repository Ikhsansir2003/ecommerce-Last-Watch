<?php
include "php/koneksi.php";

// Ambil ID produk dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query data produk
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id LIMIT 1");

if (mysqli_num_rows($query) === 0) {
    die("Produk tidak ditemukan");
}

$produk = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <title><?php echo $produk['nama']; ?> - Last Watch</title>

  <meta name="description" content="<?php echo substr($produk['deskripsi'], 0, 150); ?>">
  <meta name="keywords" content="Rolex, luxury watch, premium, last watch">

  <link rel="stylesheet" href="style_produk.css">
</head>

<body>
  <main class="main-container">

    <!-- HEADER -->
   <header class="navbar">
        <img src="img/logo_toko.png" class="logo" />
        <input type="text" class="search" placeholder="Cari jam...">
        <div>
        <a href="index.php">Beranda</a>
        <a href="keranjang.php">Keranjang</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="logout.php">Logout</a>
    </div>
    </header>

    <!-- PRODUCT SECTION -->
    <section class="product-section">

      <div class="product-header">
        <a href="index.php">
            <img src="img/back.png" alt="Back" class="back-icon">
        </a>
        <h1 class="product-title">Produk</h1>
      </div>

      <div class="product-line"></div>

      <div class="product-content">

        <!-- LEFT COLUMN -->
        <div class="product-images">

           <!-- MAIN IMAGE -->
          <div class="main-image-container">
            <img id="mainImage" src="img/<?= $produk['gambar']; ?>" class="main-product-image">
          </div>

          <!-- THUMBNAILS -->
          <div class="thumbnail-gallery">
              <?php 
              // contoh 4 thumbnail sama (jika ingin multi gambar, cukup ganti kolom database)
              for($i=0; $i<4; $i++): ?>
                <div class="thumbnail-item">
                    <img src="img/<?= $produk['gambar']; ?>" class="thumbnail-image">
                </div>
              <?php endfor; ?>
          </div>

          <!-- REVIEWS (tetap statis sesuai template Anda) -->
          <div class="reviews-section">
            <div class="reviews-header">
              <div class="reviews-title-section">
                <h2 class="reviews-title">Ulasan</h2>
                <div class="rating-display">
                  <img src="img/star.png" class="star-icon">
                  <span class="rating-text">4.0/5.0</span>
                </div>
              </div>
            </div>

            <div class="reviews-divider"></div>

            <div class="review-item">
              <div class="review-content">
                <div class="reviewer-info">
                  <!-- <img src="../assets/images/img_image_25.png" class="reviewer-avatar"> -->
                  <div class="reviewer-details">
                    <div class="reviewer-name-section">
                      <h3 class="reviewer-name">Anton</h3>
                      <p class="review-date">2025-11-05</p>
                    </div>
                    <!-- <img src="../assets/images/img_image_26.png" class="verified-badge"> -->
                  </div>
                </div>

                <p class="review-text">
                  Jamnya bagus banget! Responnya cepat, rekomen banget untuk dibeli.
                </p>
              </div>
            </div>

          </div>
        </div>

      <div class="product-details">
    <div class="product-info">
        <h1 class="product-name"><?= $produk['nama_produk']; ?></h1>

        <p class="product-price">
            Rp <span id="displayPrice"><?= number_format($produk['harga_produk'], 0, ',', '.'); ?></span>
        </p>
        <input type="hidden" id="basePrice" value="<?= $produk['harga_produk']; ?>">

        <div class="product-meta">
            <div class="condition-info">
                <span class="condition-label">Kondisi</span>
                <span class="colon">:</span>
                <span class="condition-text"><?php echo $produk['kondisi']; ?></span>
            </div>
            <div class="stock-info">
                <span class="stock-label">Stok</span>
                <span class="colon">:</span>
                <span class="stock-text">Tersedia</span> </div>
        </div>
        
        <h3 class="specs-title">Deskripsi Produk</h3>
        <div class="specs-box">
            <p class="specs-text"><?= nl2br($produk['deskripsi']); ?></p>
        </div>
    </div>

    <div class="purchase-section">
        <div class="purchase-controls">
            <div class="quantity-wrapper">
                <span class="quantity-label">Jumlah:</span>
                <div class="quantity-selector">
                    <button id="minusBtn" class="qty-btn">-</button>
                    <span id="qtyValue" class="qty-value">1</span>
                    <button id="plusBtn" class="qty-btn">+</button>
                </div>
            </div>

            <div class="action-buttons">
                <button class="cart-button" onclick="location.href='php/tambah_keranjang.php?id=<?php echo $produk['id']; ?>'">
                    <i class="fas fa-shopping-cart"></i> Tambah Keranjang
                </button>

                <form action="php/p_beli2.php" method="POST" class="buy-form">
                    <input type="hidden" name="produk_id" value="<?= $produk['id']; ?>">
                    <input type="hidden" id="jumlahInput" name="jumlah" value="1">
                    <button type="submit" class="buy-button">Beli Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>

        </div>
      </div>
    </section>



    <!-- RELATED PRODUCTS (tidak diubah) -->
    <section class="related-products">
      <div class="products-grid">
        <!-- tetap gunakan template Anda -->
      </div>
    </section>

    <!-- FOOTER -->
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
  </main>


  
  <script>
/* ========== GANTI GAMBAR UTAMA SAAT THUMBNAIL DIKLIK ========== */
document.querySelectorAll(".thumbnail-image").forEach(img => {
    img.addEventListener("click", function() {
        document.getElementById("mainImage").src = this.src;

        // highlight thumbnail aktif
        document.querySelectorAll(".thumbnail-item").forEach(item => item.classList.remove("active"));
        this.parentElement.classList.add("active");
    });
});

/* ========== UPDATE QUANTITY & HARGA OTOMATIS ========== */
let qty = 1;
const qtyValue = document.getElementById("qtyValue");
const basePrice = parseInt(document.getElementById("basePrice").value);
const displayPrice = document.getElementById("displayPrice");

document.getElementById("plusBtn").onclick = function() {
    qty++;
    qtyValue.textContent = qty;
    updatePrice();
    document.getElementById("jumlahInput").value = qty;
};

document.getElementById("minusBtn").onclick = function() {
    if (qty > 1) {
        qty--;
        qtyValue.textContent = qty;
        updatePrice();
        document.getElementById("jumlahInput").value = qty;
    }
};


function updatePrice() {
    let newPrice = basePrice * qty;
    displayPrice.textContent = newPrice.toLocaleString("id-ID");
}
</script>


</body>
</html>

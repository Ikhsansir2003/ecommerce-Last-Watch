<?php
session_start();
include '../php/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

/* DATA STATISTIK */
$totalProduk   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM produk"))[0];
$totalOrder    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM `order`"))[0];
$orderPending  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM `order` WHERE status='Pending'"))[0];
$totalIncome   = mysqli_fetch_row(mysqli_query($conn, "SELECT SUM(total) FROM `order` WHERE status='Success'"))[0] ?? 0;

/* DATA GRAFIK BULANAN */
$bulan = [];
$jumlah = [];

$qChart = mysqli_query($conn, "
SELECT MONTH(created_at) bulan, COUNT(*) total
FROM `order`
GROUP BY MONTH(created_at)
");

while ($c = mysqli_fetch_assoc($qChart)) {
    $bulan[]  = date("M", mktime(0,0,0,$c['bulan'],1));
    $jumlah[] = $c['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background:#f4f6f8;
}

/* NAVBAR */
.navbar {
    background:#bf9455;
    padding:15px 30px;
    color:#fff;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar a {
    color:white;
    text-decoration:none;
    margin-left:20px;
    font-weight:bold;
}
.container {
    padding:30px;
}

/* CARD */
.cards {
    display:grid;
    grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card {
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.card h2 {
    margin:0;
    font-size:32px;
    color:#bf9455;
}

.card p {
    margin:5px 0 0;
    color:#555;
}

/* CHART */
.chart-box {
    background:#fff;
    padding:25px;
    border-radius:12px;
    margin-top:30px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    height: 400px;
}

.chart-box canvas {
    max-height: 300px;
}


.menu a {
    color:#fff;
    text-decoration:none;
    margin-left:20px;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="navbar">
    <h3>Admin Panel</h3>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="produk.php">Produk</a>
        <a href="pesanan.php">Pesanan</a>
         <?php if (!isset($_SESSION['id_user'])): ?>
        <!-- JIKA BELUM LOGIN -->
        <a href="login.php" class="btn-login">Login</a>
    <?php else: ?>
        <!-- JIKA SUDAH LOGIN -->
        <a href="../logout.php" class="btn-logout">Logout</a>
    <?php endif; ?>
    </div>
</div>

<div class="container">

    <!-- CARD STATISTIK -->
    <div class="cards">
        <div class="card">
            <h2><?= $totalProduk ?></h2>
            <p>Total Produk</p>
        </div>
        <div class="card">
            <h2><?= $totalOrder ?></h2>
            <p>Total Pesanan</p>
        </div>
        <div class="card">
            <h2><?= $orderPending ?></h2>
            <p>Pesanan Pending</p>
        </div>
        <div class="card">
            <h2>Rp <?= number_format($totalIncome,0,',','.') ?></h2>
            <p>Total Pendapatan</p>
        </div>
    </div>

    <!-- GRAFIK -->
    <div class="chart-box">
        <h3>Grafik Pesanan Bulanan</h3>
        <canvas id="orderChart"></canvas>
    </div>

</div>

<script>
const ctx = document.getElementById('orderChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($bulan) ?>,
        datasets: [{
            label: 'Jumlah Pesanan',
            data: <?= json_encode($jumlah) ?>,
            backgroundColor: 'rgba(191,148,85,0.8)',
            borderRadius: 8,
            barThickness: 40
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                top: 20,
                left: 10,
                right: 10,
                bottom: 10
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: '#333',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 10
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 13
                    }
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: '#eee'
                },
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});
</script>


</body>
</html>

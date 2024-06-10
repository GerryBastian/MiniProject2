<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nial Horan - Live On Tour</title>
    <link rel="stylesheet" href="detail.css">
</head>

<body>
<?php
include 'detail.php'; // Asumsikan file koneksi.php telah dibuat sebelumnya

// Ambil data konser dari database
$konser_id = isset($_GET['konser_id']) ? $_GET['konser_id'] : 1 ;// Ganti dengan ID konser yang sesuai
$sql = "SELECT * FROM detail WHERE konser_id = $konser_id";
$result = $conn->query($sql);

if ($result === false) {
    die("Error: Could not execute $sql. " . $conn->error);
}

if ($result->num_rows > 0) {
    $konser = $result->fetch_assoc();
} else {
    die("Data konser tidak ditemukan.");
}

// Periksa apakah tanggal konser telah lewat
// $current_date = date('Y-m-d');
// $konser_date = $konser['tanggal'];
// $konser_passed = $konser_date < $current_date;

// Ambil data tiket dari database
$sql_tiket = "SELECT * FROM tiket WHERE konser_id = $konser_id";
$result_tiket = $conn->query($sql_tiket);

if ($result_tiket === false) {
    die("Error: Could not execute $sql_tiket. " . $conn->error);
}

// Ambil data aturan dan lagu dari database
$aturan_list = explode(';', $konser['aturan']);
$lagu_list = explode(';', $konser['Lagu']);
?>

<!-- Kode HTML dan PHP yang lain... -->
<header>
        <div class="container">
            <div class="header-content">
                <h1 class="hyper-tix" data-text="HyperTIX">HyperTIX</h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="HalPemesanan.php">PEMESANAN</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="event-details">
                <div class="event-image">
                    <img src="<?php echo $konser['gambar']; ?>" alt="Gambar">
                </div>
                <div class="event-info">
                    <h2 class="event-title"><?php echo $konser['judul']; ?></h2>
                    <p class="event-date"><?php echo date("F j • l • h:i A • Y", strtotime($konser['tanggal'] . ' ' . $konser['waktu'])); ?></p>
                    <p class="event-location"><?php echo $konser['tempat']; ?></p>
                    <p class="event-price">RP <?php echo number_format($konser['harga_min']); ?> - Rp <?php echo number_format($konser['harga_max']); ?></p>
                </div>
            </section>

            <section class="description">
                <div class="container">
                    <div class="description-content">
                        <div class="description-text">
                            <h2 class="section-title">Deskripsi</h2>
                            <p class="event-description"><?php echo $konser['deskripsi']; ?></p>
                        </div>
                        <div class="description-image">
                            <!-- Optional: Add any image related to the description -->
                        </div>
                    </div>
                </div>
            </section>

            <section class="seat-selection">
                <div class="container">
                    <div class="seat-selection-content">
                        <div class="seat-selection-image">
                            <img src="Gambar/planseating.png" alt="Denah Kursi">
                        </div>
                        <div class="seat-selection-text">
                            <h2 class="section-title">Seat</h2>
                            <div class="ticket-items">
                                <?php while($tiket = $result_tiket->fetch_assoc()): ?>
                                <div class="ticket-item">
                                    <h3><?php echo $tiket['kategori']; ?></h3>
                                    <p>Ticket Price Excludes 15% Government Tax 5% Additional Fee</p>
                                    <p>Under 12 years old are not allowed to purchase tickets</p>
                                    <p><?php echo $tiket['kategori']; ?></p>
                                    <p class="price">RP <?php echo number_format($tiket['harga']); ?></p>
                                    <p class="<?php echo $tiket['stock'] > 0 ? 'stock-available' : 'stock-sold-out'; ?>">Stock: <?php echo $tiket['stock'] > 0 ? $tiket['stock'] : 'Habis'; ?></p>
                                    <?php if ($tiket['stock'] > 0): ?>
                                    <form action="HalPemesanan.php" method="get">
                                        <input type="hidden" name="tiket_id" value="<?php echo $tiket['id']; ?>">
                                        <label for="quantity">Jumlah:</label>
                                        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $tiket['stock']; ?>" value="1">
                                        <button type="submit">Beli Tiket</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="event-rules">
                <div class="container">
                    <h2 class="section-title">Aturan Konser</h2>
                    <ul>
                        <li>
                            <span class="text">Tiket hanya dapat digunakan untuk masuk ke acara konser Niall Horan The Show Live on Tour di Jakarta</span>
                        </li>
                        <li>
                            <span class="text">Nama Pembeli Tiket harus sesuai dengan KTp/SIM</span>
                        </li>
                        <li>
                            <span class="text">Satu tiket berlaku untuk satu pemegang tiket untuk satu kali masuk ke lokasi acara</span>
                        </li>
                        <li>
                            <span class="text">Wanita hamil tidak diperbolehkan berada di area berdiri bebas</span>
                        </li>
                        <li>
                            <span class="text">Tiket tidak dapat digunakan untuk tujuan komersial</span>
                        </li>
                        <li>
                            <span class="text">Tiket yang terjual tidak dapat ditukar dan tidak dapat diuangkan</span>
                        </li>
                    </ul>
                </div>
            </section>

            <section class="lagu-lagu">
                <div class="container">
                    <h3 class="List1">Lagu yang Dibawakan</h3>
                    <ul>
                        <li><span class="text">This Town</span></li>
                        <li><span class="text">Slow Hands</span></li>
                        <li><span class="text">Too Much to Ask</span></li>
                        <li><span class="text">On the Loose</span></li>
                        <li><span class="text">Nice to Meet Ya</span></li>
                        <li><span class="text">No Judgement</span></li>
                        <li><span class="text">Black and White</span></li>
                        <li><span class="text">Put a Little Love on Me</span></li>
                    </ul>
                </div>
            </section>
        </div>
    </main>
    <footer>
        <div class="container">
            <div class="footer-info">
                <h2>HyperTIX</h2>
                <p>&copy;2024 HyperTIX. All rights reserved.</p>
            </div>
            <div class="footer-nav">
                <ul>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Events</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>

</html>
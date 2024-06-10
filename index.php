<?php
// Koneksi ke database
$host = 'localhost';
$db = 'cari';
$user = 'root';
$pass = '';

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data konser
$sql = "SELECT * FROM konser";
if (isset($_GET["search"])) {
    $keyword = mysqli_real_escape_string($conn, $_GET["search"]);
    $sql .= " WHERE lokasi LIKE '%$keyword%' OR nama_artis LIKE '%$keyword%' OR tanggal LIKE '%$keyword%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HyperTIX</title>
    <link rel="stylesheet" href="Halaman.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">    
                <h1 class="hyper-tix" data-text="HyperTIX">HyperTIX</h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="login.php">LOGIN</a></li>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="HalPemesanan.php">PEMESANAN</a></li>
                    <li class="search-bar">
                        <form action="index.php" method="get" class="pencarianForm">
                            <input type="text" class="formPencarian" name="search" placeholder="Cari Konser">
                            <button type="submit">Cari</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </header>  

    <div class="promo-slider">
        <input type="radio" name="slider" id="slide1" checked>
        <input type="radio" name="slider" id="slide2">
        <input type="radio" name="slider" id="slide3">

        <div class="slides">
            <div class="slide">
                <img src="Gambar/promosi1.jpg" alt="Promo 1">
            </div>
            <div class="slide">
                <img src="Gambar/Promosi2.jpg" alt="Promo 2">
            </div>
            <div class="slide">
                <img src="Gambar/Promosi3.jpg" alt="Promo 3">
            </div>
            <div class="slide">
                <img src="Gambar/Promosi1.jpg" alt="Promo 4">
            </div>
            <div class="slide">
                <img src="Gambar/Promosi2.jpg" alt="Promo 5">
            </div>
        </div>
    
        <div class="slider-controls">
            <label for="slide1"></label>
            <label for="slide2"></label>
            <label for="slide3"></label>
        </div>
    </div>
    
    <section class="featured-events">
        <div class="container">
            <h2>KONSER YANG AKAN DATANG</h2>
            <div class="event-grid">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="event-item">';
                        echo '<img src="Gambar/' . $row["gambar"] . '" alt="Event 1">';
                        echo '<p class="artist-name">' . $row["nama_artis"] . '</p>';
                        echo '<div class="overlay">';
                        echo '<div class="event-info">';
                        echo '<h3>' . $row["judul_konser"] . '</h3>';
                        echo '<p>Tanggal: ' . $row["tanggal"] . '</p>';
                        echo '<p>Lokasi : ' . $row["lokasi"] . '</p>';
                        echo '<p class="price">Rp ' . number_format($row["harga_tiket"], 0, ',', '.') . '</p>';
                        echo '<a href="detailkonser.php?id=' . $row["id"] . '" class="btn">Read More</a>';
                        echo '</div></div></div>';
                    }
                
                } else {
                    echo "<p>No concerts found.</p>";
                }
                ?>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="footer-info">
                <h2>HyperTIX</h2>
                <p>&copy; 2024 HyperTIX. Danke Banya Tete Manis Memberkati.</p>
            </div>
            <div class="footer-nav">
                <ul>
                    <li><p>Contact Us :</p></li>
                    <li><img src="Gambar/facebook.png" alt=""><a href="#"></a></li>
                    <li><img src="Gambar/instagram.png" alt=""><a href="#"></a></li>
                    <li><img src="Gambar/twitter.png" alt=""><a href="#"></a></li>
                </ul>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchForm = document.querySelector('.pencarianForm');
            const searchInput = document.querySelector('.formPencarian');
            const concertBoxes = document.querySelectorAll('.event-item');

            searchForm.addEventListener('submit', (e) => {
                const searchTerm = searchInput.value.toLowerCase();
                filterConcerts(searchTerm);
            });

            function filterConcerts(searchTerm) {
                const concerts = Array.from(concertBoxes);
                concerts.forEach(concert => {
                    const artistName = concert.querySelector('.artist-name').textContent.toLowerCase();
                    const location = concert.querySelector('.event-info p:nth-child(3)').textContent.toLowerCase();
                    const date = concert.querySelector('.event-info p:nth-child(2)').textContent.toLowerCase();
                    if (artistName.includes(searchTerm) || location.includes(searchTerm) || date.includes(searchTerm)) {
                        concert.style.display = '';
                    } else {
                        concert.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>

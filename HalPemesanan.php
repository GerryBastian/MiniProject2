<?php

// session_start();
// if (!isset($_SESSION['loggedin'])) {
//     header("Location: login.php");
//     exit();
// }

// // Periksa apakah ada data yang dikirimkan melalui metode POST
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Di sini Anda dapat menambahkan logika autentikasi pengguna, seperti memeriksa username dan password di database

//     // Misalnya, untuk tujuan demonstrasi, kita akan asumsikan bahwa pengguna selalu berhasil login
//     $_SESSION['logged_in'] = true;

//     // Setelah berhasil login, arahkan ke halaman pemesanan
//     header("Location: HalPemesanan.php");
//     exit;
// }


$host = 'localhost';
$db = 'cari';
$user = 'root';
$pass = '';

// Create a new connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $concert_id = 1; // Replace with dynamic value if needed
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $jmltiket = $_POST['jmltiket'];
    $jenis_tiket = $_POST['jenis_tiket'];

    // Insert order data into the pemesanan table
    $sql_insert = "INSERT INTO pemesanan (concert_id, nama, nomor_telepon, email, jumlah_tiket, jenis_tiket) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    // Check if statement preparation is successful
    if ($stmt_insert === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt_insert->bind_param("isssis", $concert_id, $nama, $no_hp, $email, $jmltiket, $jenis_tiket);

    // Execute statement
    if ($stmt_insert->execute()) {
        echo "<script>alert('Pesanan berhasil dilakukan!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt_insert->error . "');</script>";
    }

    // Close the statement
    $stmt_insert->close();
}

// Fetch concert details
$concert_id = 1; // Replace with the actual concert ID or dynamic value
$sql = "SELECT * FROM konser WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $concert_id);
$stmt->execute();
$result = $stmt->get_result();
$konser = $result->fetch_assoc();

// Fetch ticket details
$sql_tiket = "SELECT * FROM tiket WHERE konser_id = ?";
$stmt_tiket = $conn->prepare($sql_tiket);
$stmt_tiket->bind_param("i", $concert_id);
$stmt_tiket->execute();
$result_tiket = $stmt_tiket->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pemesanan Tiket Konser</title>
  <link rel="stylesheet" href="pemesanan.css" />
</head>
<body>
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

<div class="container">
  <table>
    <tr>
      <td class="seating-plan-container">
        <img src="Gambar/planseating.png" alt="Seating Plan" class="seating-plan" />
      </td>
      <td class="ticket-info">
        <h3>Info Tiket Konser</h3>
        <p>Di sini Anda dapat memesan tiket konser. Silakan lengkapi formulir di bawah ini untuk memesan tiket.</p>
        <form method="post">
          <input type="text" name="nama" placeholder="Nama" required />
          <input type="tel" name="no_hp" placeholder="Nomor Telepon" required />
          <input type="email" name="email" placeholder="Email" required />
          <input type="number" name="jmltiket" placeholder="Jumlah Tiket" min="1" max="20" required />
          <select name="jenis_tiket" required>
            <option value="" disabled selected>Pilih Jenis Tiket</option>
            <?php while($tiket = $result_tiket->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($tiket['kategori']); ?>"><?php echo htmlspecialchars($tiket['kategori']); ?></option>
            <?php endwhile; ?>
          </select>
          <button type="reset" class="reset">Reset</button>
          <input type="submit" value="Submit" />
          <button type="button" class="back"><a href="detail.html">Back</a></button>
        </form>
        <div class="ticket-details">
          <h3>Detail Tiket:</h3>
          <p>Nama Acara: <?php echo htmlspecialchars($konser['judul_konser']); ?></p>
          <p>Tanggal: <?php echo htmlspecialchars($konser['tanggal']); ?></p>
          <p>Tempat: <?php echo htmlspecialchars($konser['lokasi']); ?></p>
        </div>
      </td>
    </tr>
  </table>
</div>

<footer>
  <div class="container">
      <div class="footer-info">
          <h2>HyperTIX</h2>
          <p>&copy; 2024 HyperTIX. Terima kasih.</p>
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
</body>
</html>

<?php
// Close the connection
$conn->close();
?>

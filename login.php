<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'cari');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Melakukan query untuk mendapatkan user
    $sql = "SELECT * FROM data_user WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No user found with that username and email";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <!-- <link rel="stylesheet" href="login.css"> -->
     <style>
        body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  color: black;
  background-color: #222831;
}

.login-container {
  width: 300px;
  margin: 100px auto;
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

h2 {
  text-align: center;
  font-size: 24px;
  margin: 0;
  padding: 0;
}

.form-group {
  margin-bottom: 15px;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

.form-inline {
  display: flex;
  align-items: center;
}

.form-inline input {
  margin-right: 10px;
}

button {
  background-color: #76abae;
  border-radius: 8px;
  font-size: 0.875rem;
  padding: 14px 20px;
  text-transform: capitalize !important;
  font-weight: 400;
  color: white;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  background-color: #eee;
  color: #111386;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
     </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php
        if (!empty($error)) {
            echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
        }
        ?>
        <form action="" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email" required>
        
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
        
            <button type="submit">Login</button>
        </form>
        <br>
        <p>Belum punya akun? Daftar <a href="regis.php">disini!</a></p>
    </div>
</body>
</html> 
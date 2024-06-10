<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $conn = new mysqli('localhost', 'root', '', 'cari');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Memeriksa apakah username atau email sudah digunakan sebelumnya
        $check_query = "SELECT * FROM data_user WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param('ss', $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $sql = "INSERT INTO data_user (username, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $username, $email, $hashed_password);

            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
                $error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $check_stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        <h2>Register</h2>
        <?php
        if (!empty($error)) {
            echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
        }
        ?>
        <form action="regis.php" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email" required>
        
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Konfirmasi password" required>
        
            <button type="submit">Register</button>
        </form>
        <br>
        <p>Sudah punya akun? Login <a href="login.php">disini!</a></p>
    </div>
</body>
</html>
<?php
require_once("config.php");

class UserManager {
    public static function registerUser($name, $email, $phone_number, $password, $username, $group_id) {
        // Implementasi registrasi pengguna
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $password = $_POST["password"];
    $username = $_POST["username"];
    $group_id = 3;
    
    // Periksa apakah nomor HP sudah ada dalam database
    $check_query = "SELECT * FROM users WHERE phone_number = '$phone_number'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        echo "Nomor HP sudah terdaftar. Silakan gunakan nomor HP lain.";
    } else {
        // Nomor HP belum ada dalam database, lanjutkan dengan pendaftaran
        $insert_query = "INSERT INTO users (name, email, phone_number, username, password, group_id) VALUES ('$name', '$email', '$phone_number', '$username', '$password', '$group_id')";
        
        if ($conn->query($insert_query) === TRUE) {
            // Registrasi berhasil, arahkan pengguna ke halaman login
            header("Location: login.php");
            exit;  // Penting untuk menghentikan eksekusi kode selanjutnya
        } else {
            echo "Gagal melakukan registrasi: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form method="post" action="">
        
        <label for="name">Nama:</label>
        <input type="text" name="name" required><br>
    
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone_number">No HP:</label>
        <input type="text" name="phone_number" required><br>

        <label for="username">username:</label>
        <input type="text" name="username" required><br> <!-- Perbaikan di sini, ubah dari "type=username" menjadi "type=text" -->

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="register">Register</button> <!-- Menambahkan name="register" pada tombol -->
    </form>
</body>
</html>

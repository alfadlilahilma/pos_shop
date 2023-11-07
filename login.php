<?php
session_start();
require_once("config.php");

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["id"];
        header("Location: index.php");
        exit; // Penting untuk menghentikan eksekusi kode selanjutnya
    } else {
        $error_message = "Autentikasi gagal. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
    </form>

    <?php
    if (isset($error_message)) {
        echo "<p>$error_message</p>";
    }
    ?>
</body>
</html>

<?php
session_start();

if (isset($_SESSION['auth'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($username === "admin" && $password === "MiniShop@03") {

        $_SESSION["auth"] = true;
        $_SESSION["username"] = "admin";

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Sai tài khoản hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>

<body>

<h2>Đăng nhập MiniShop</h2>

<?php if($error!=""): ?>
<p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">

<p>
Tên đăng nhập
<br>
<input type="text" name="username" required>
</p>

<p>
Mật khẩu
<br>
<input type="password" name="password" required>
</p>

<button type="submit">Đăng nhập</button>

</form>

</body>
</html>
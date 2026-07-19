<?php
session_start();

if (!isset($_SESSION["auth"])) {
    header("Location: login.php");
    exit;
}

require_once "data.php";

if (!isset($_SESSION["orders"])) {
    $_SESSION["orders"] = [];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sku = $_POST["sku"] ?? "";
    $qty = (int)($_POST["qty"] ?? 0);

    if ($qty > 0) {
        $_SESSION["orders"][] = [
            "sku" => $sku,
            "qty" => $qty
        ];
    }
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>MiniShop Dashboard</title>
</head>

<body>

<h2>Xin chào <?= htmlspecialchars($_SESSION["username"]) ?></h2>

<p>
    <a href="logout.php">Đăng xuất</a>
</p>

<table border="1" cellpadding="8" cellspacing="0">

    <tr>
        <th>SKU</th>
        <th>Tên</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
        <th>Mức tồn</th>
    </tr>

    <?php foreach ($productObjects as $p): ?>

    <tr>
        <td><?= htmlspecialchars($p->getSku()) ?></td>
        <td><?= htmlspecialchars($p->getName()) ?></td>
        <td><?= number_format($p->getPrice()) ?></td>
        <td><?= $p->getQty() ?></td>
        <td><?= number_format($p->lineTotal()) ?></td>
        <td><?= $p->stockLevel() ?></td>
    </tr>

    <?php
        $total += $p->lineTotal();
    ?>

    <?php endforeach; ?>

</table>

<h3>
    Tổng giá trị kho:
    <?= number_format($total) ?> VNĐ
</h3>

<hr>

<h3>Đặt thử sản phẩm</h3>

<form method="post">

    <label>Sản phẩm:</label>

    <select name="sku">

        <?php foreach ($productObjects as $p): ?>

        <option value="<?= htmlspecialchars($p->getSku()) ?>">
            <?= htmlspecialchars($p->getSku()) ?>
        </option>

        <?php endforeach; ?>

    </select>

    <label>Số lượng:</label>

    <input
        type="number"
        name="qty"
        min="1"
        required>

    <button type="submit">
        Đặt thử
    </button>

</form>

<hr>

<h3>Danh sách Order (Session)</h3>

<?php if (count($_SESSION["orders"]) == 0): ?>

<p>Chưa có order.</p>

<?php else: ?>

<ul>

<?php foreach ($_SESSION["orders"] as $order): ?>

<li>

SKU:
<?= htmlspecialchars($order["sku"]) ?>

-

SL:
<?= $order["qty"] ?>

</li>

<?php endforeach; ?>

</ul>

<?php endif; ?>

</body>
</html>
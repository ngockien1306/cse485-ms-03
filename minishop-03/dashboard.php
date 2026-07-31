<?php
session_start();

// Guard: chưa đăng nhập thì quay về login
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/data.php';

// Khởi tạo mảng order trong Session
if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [];
}

// Thêm order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sku = $_POST['sku'] ?? '';
    $qty = (int)($_POST['qty'] ?? 1);

    if ($sku !== '' && $qty > 0) {

        $_SESSION['orders'][] = [
            'sku' => $sku,
            'qty' => $qty
        ];
    }

    header("Location: dashboard.php");
    exit;
}

$tongKho = 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Dashboard - MiniShop</title>

<style>

body{
    font-family:Arial;
    margin:40px;
    background:#f5f5f5;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th,td{
    border:1px solid #ccc;
    padding:10px;
    text-align:center;
}

th{
    background:#0d6efd;
    color:white;
}

.summary{
    margin-top:20px;
    font-size:18px;
    font-weight:bold;
}

form{
    margin-top:30px;
}

.order{
    margin-top:30px;
    background:white;
    padding:20px;
}

.logout{
    float:right;
}

</style>

</head>

<body>

<h2>
MiniShop Dashboard
</h2>

<p>
Xin chào,
<b><?= htmlspecialchars($_SESSION['username']) ?></b>

<a class="logout" href="logout.php">
Đăng xuất
</a>

</p>

<table>

<tr>
<th>SKU</th>
<th>Tên</th>
<th>Danh mục</th>
<th>Giá</th>
<th>Số lượng</th>
<th>Thành tiền</th>
<th>Tồn kho</th>
</tr>

<?php foreach($productObjects as $p): ?>

<?php

$line = $p->lineTotal();

$tongKho += $line;

$tenDM = "";

foreach($categoryObjects as $c){

    if($c->getId()==$p->getCategoryId()){

        $tenDM = $c->getName();

        break;

    }

}

?>

<tr>

<td><?= htmlspecialchars($p->getSku()) ?></td>

<td><?= htmlspecialchars($p->getName()) ?></td>

<td><?= htmlspecialchars($tenDM) ?></td>

<td><?= $p->getPrice() ?></td>

<td><?= $p->getQty() ?></td>

<td><?= $line ?></td>

<td><?= $p->stockLevel() ?></td>

</tr>

<?php endforeach; ?>

</table>

<div class="summary">

<p>Tổng giá trị kho:
<b><?= $tongKho ?></b></p>

<p>Số sản phẩm:
<b><?= count($productObjects) ?></b></p>

</div>

<hr>

<h3>Đặt thử sản phẩm</h3>

<form method="post">

<select name="sku">

<?php foreach($productObjects as $p): ?>

<option value="<?= htmlspecialchars($p->getSku()) ?>">
<?= htmlspecialchars($p->getSku()) ?> -
<?= htmlspecialchars($p->getName()) ?>
</option>

<?php endforeach; ?>

</select>

<input
type="number"
name="qty"
min="1"
value="1">

<button type="submit">
Thêm Order
</button>

</form>

<div class="order">

<h3>Danh sách Order trong Session</h3>

<?php

if(empty($_SESSION['orders'])){

    echo "Chưa có order.";

}else{

    echo "<ul>";

    foreach($_SESSION['orders'] as $o){

        echo "<li>";

        echo htmlspecialchars($o['sku']);

        echo " - SL: ";

        echo (int)$o['qty'];

        echo "</li>";

    }

    echo "</ul>";

}

?>

</div>

</body>
</html>
<?php include './inc/handle.php'; ?>

<?php
include_once './classses/discount.php';
$discount = new discount();
$discountAmount = 0;
$coupon_code_applied = '';

// Xử lý khi áp dụng mã giảm giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_coupon'])) {
    $coupon_code = trim($_POST['coupon_code']);
    $check = $discount->checkCoupon($coupon_code);

    if ($check && $check->num_rows > 0) {
        $coupon = $check->fetch_assoc();
        $coupon_code_applied = $coupon['coupon_code'];
        if ($coupon['coupon_time'] > 0) {
            $cartItems = $cat->getProductCart(Session::get('user_id'));
            $subTotal = 0;
            while ($row = $cartItems->fetch_assoc()) {
                $subTotal += $row['price'] * $row['quantity'];
            }

            if ($coupon['coupon_conditions'] == 0) {
                $discountAmount = $subTotal * ($coupon['coupon_number'] / 100);
            } else {
                $discountAmount = $coupon['coupon_number'];
            }
        } else {
            echo "<script>alert('Mã này đã hết lượt sử dụng.');</script>";
        }
    } else {
        echo "<script>alert('Mã giảm giá không hợp lệ');</script>";
    }
}

// Xử lý đặt hàng
if (isset($_GET['orderid']) && $_GET['orderid'] == 'order') {
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    $date = date('Y-m-d H:i:s');
    $insertOrder = $order->insertOder(Session::get('user_id'), $date);
    $delCart = $cat->del_Cart(Session::get('user_id'));
    header('Location:donhang.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="assets/css/grid.css" />
    <link rel="stylesheet" href="assets/css/productCart.css" />
    <link rel="stylesheet" href="assets/css/base.css" />
    <link rel="stylesheet" href="assets/css/thanhtoan.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/font/themify-icons/themify-icons.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Giỏ hàng</title>
</head>
<body>
<div class="grid">
    <?php include 'inc/header.php'; ?>
</div>

<div class="grid" style="border-top: 1px solid #ccc;">
    <form action="" method="POST">
        <div class="grid">
            <div class="app">
                <div class="grid wide">
                    <div class="row">
                        <div class="col l-8" style="margin-top: 40px;">
                            <!-- Tiến trình thanh toán -->
                            <div class="col l-12">
                                <div class="checkout-process-bar block-border">
                                    <ul>
                                        <li><span>Giỏ hàng</span></li>
                                        <li class="active"><span>Đặt hàng</span></li>
                                        <li><span>Thanh toán</span></li>
                                        <li><span>Hoàn thành đơn</span></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Thông tin sản phẩm -->
                            <div class="col l-12" style="margin: 20px 0;">
                                <h3>Thông tin sản phẩm</h3>
                                <div style="border: 1px solid #e7e8e9;border-radius: 32px 0px 0px;">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Ảnh</th>
                                                <th>Tên</th>
                                                <th>Size</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>Số tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $getProductCat = $cat->getProductCart(Session::get('user_id'));
                                            if ($getProductCat) {
                                                $dem = 0;
                                                $subTotal = 0;
                                                $i = 0;
                                                while ($row = $getProductCat->fetch_assoc()) {
                                                    $total = $row['price'] * $row['quantity'];
                                                    $subTotal += $total;
                                                    $dem++;
                                            ?>
                                                    <tr>
                                                        <td><?= ++$i ?></td>
                                                        <td><img src="./admin/upload/<?= $row['image'] ?>" class="app_cart-img" /></td>
                                                        <td><?= $row['productName'] ?></td>
                                                        <td><?= $row['size'] ?></td>
                                                        <td><?= number_format($row['price'], 0, ',', '.') ?>đ</td>
                                                        <td><?= $row['quantity'] ?></td>
                                                        <td><?= number_format($total, 0, ',', '.') ?>đ</td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Địa chỉ giao hàng -->
                            <div class="col l-12" style="margin: 20px 0;">
                                <h3>Địa chỉ giao hàng</h3>
                                <div style="border: 1px solid #e7e8e9;border-radius: 32px 0px 0px;">
                                    <?php
                                    $userId = Session::get('user_id');
                                    $infor_user = $user->show_User($userId);
                                    if ($infor_user) {
                                        $u = $infor_user->fetch_assoc();
                                    ?>
                                        <table>
                                            <tr><td>Tên:</td><td><?= $u['name'] ?></td></tr>
                                            <tr><td>Email:</td><td><?= $u['email'] ?></td></tr>
                                            <tr><td>SĐT:</td><td><?= $u['sdt'] ?></td></tr>
                                            <tr><td>Địa chỉ:</td><td><?= $u['diaChi'] ?></td></tr>
                                            <tr><td colspan="2"><a href="account.php">Chỉnh sửa thông tin</a></td></tr>
                                        </table>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Thanh toán bên phải -->
                        <div class="col l-4">
                            <div class="cart-voucher">
                                <h3>Tóm tắt đơn hàng</h3>
                                <?php
                                $check_cart = $cat->checkCart(Session::get('user_id'));
                                if ($check_cart) {
                                ?>
                                    <div style="margin-bottom: 15px;">
                                        <label for="coupon_code">Mã giảm giá:</label>
                                        <input type="text" name="coupon_code" placeholder="Nhập mã..." />
                                        <button type="submit" name="apply_coupon">Áp dụng</button>
                                    </div>

                                    <div style="display: flex; justify-content: space-between;">
                                        <span>Tổng sản phẩm</span>
                                        <span><?= $dem ?></span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>Tổng tiền hàng</span>
                                        <span><?= number_format($subTotal, 0, ',', '.') ?>đ</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between;">
                                        <span>Thuế (VAT 10%)</span>
                                        <span>
                                            <?php $vat = $subTotal * 0.1; echo number_format($vat, 0, ',', '.') ?>đ
                                        </span>
                                    </div>
                                    <?php if ($discountAmount > 0) { ?>
                                        <div style="display: flex; justify-content: space-between;">
                                            <span>Giảm giá (<?= $coupon_code_applied ?>)</span>
                                            <span>-<?= number_format($discountAmount, 0, ',', '.') ?>đ</span>
                                        </div>
                                    <?php } ?>
                                    <div class="cart-purchase">
                                        <span>Thành tiền</span>
                                        <span style="font-weight: bold;">
                                            <?php
                                            $grand_Total = $subTotal + $vat - $discountAmount;
                                            echo number_format($grand_Total, 0, ',', '.') . "đ";
                                            ?>
                                        </span>
                                    </div>
                                    <div class="cart-purchase-button">
                                        <p class="btn"><a href="?orderid=order" onclick="return alert('Bạn đã đặt hàng thành công')">Xác nhận đặt hàng</a></p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php include './inc/footer.php'; ?>
</div>
</body>
</html>

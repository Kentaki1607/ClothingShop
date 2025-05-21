<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
    <link rel="stylesheet" href="assets/font/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="assets/css/sp.css">
    <title>Danh sách mã giảm giá</title>
</head>

<?php
include_once '../lib/session.php';
Session::init();

include_once '../classses/discount.php';
$discount = new discount();

// Xử lý xoá mã
if (isset($_GET['delcoupon_id'])) {
    $id = $_GET['delcoupon_id'];
    $delproduct = $discount->del_Discount($id);
}
?>

<body>
    <?php include './inc/sidebar.php'; ?>
    <div class="main-content">
        <?php include './inc/header.php'; ?>
        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <div class="activity-header">
                            <h3>Danh sách mã giảm giá</h3>
                            <div class="activity-more">
                                <span class="ti-plus"></span>
                                <a href="discountadd.php">Thêm mã giảm giá</a>
                            </div>
                        </div>

                        <?php
                        // Hiển thị thông báo nếu có
                        if (Session::get('discount_msg')) {
                            echo "<p style='color:green; font-weight:bold; padding: 10px 0;'>" . Session::get('discount_msg') . "</p>";
                            Session::set('discount_msg', null);
                        }
                        ?>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tên mã giảm</th>
                                        <th>Mã giảm giá</th>
                                        <th>Số lượng</th>
                                        <th>Điều kiện</th>
                                        <th>Giá trị</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $discountList = $discount->showDiscount();
                                    if ($discountList) {
                                        while ($result = $discountList->fetch_assoc()) {
                                            $isPercent = $result['coupon_conditions'] == 0;
                                            $valueText = $isPercent
                                                ? $result['coupon_number'] . '%'
                                                : number_format($result['coupon_number'], 0, ',', '.') . 'đ';
                                            $conditionText = $isPercent ? 'Giảm theo phần trăm' : 'Giảm theo tiền';
                                    ?>
                                            <tr>
                                                <td><?= $result['coupon_name'] ?></td>
                                                <td><?= $result['coupon_code'] ?></td>
                                                <td><?= $result['coupon_time'] ?></td>
                                                <td><?= $conditionText ?></td>
                                                <td><?= $valueText ?></td>
                                                <td>
                                                    <a onclick="return confirm('Bạn có chắc chắn muốn xóa?')" 
                                                       href="?delcoupon_id=<?= $result['coupon_id'] ?>">
                                                       Delete
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' style='text-align:center;'>Chưa có mã giảm giá nào.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

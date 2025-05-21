<?php
include '../classses/statistical.php';
include_once '../lib/session.php';
Session::init();

$statistical = new statistical();
$getTongDoanhThuTheoNGay = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $getTongDoanhThuTheoNGay = $statistical->gettongSPTheoNgay($_POST);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1" />
    <link rel="stylesheet" href="assets/font/themify-icons/themify-icons.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <title>Admin Dashboard</title>
</head>
<body>
<?php include './inc/sidebar.php'; ?>
<div class="main-content">
    <?php include './inc/header.php'; ?>

    <main>
        <h2 class="dash-title">Tổng Quan</h2>

        <div class="dash-cards">
            <!-- Tổng doanh thu -->
            <div class="card-single">
                <div class="card-body">
                    <span class="ti-briefcase"></span>
                    <div class="card-body-DT">
                        <?php
                        $getDoanhThu = $statistical->gettongDoanhThu();
                        $result = $getDoanhThu ? $getDoanhThu->fetch_assoc() : ['total_revenue' => 0];
                        $total0 = $result['total_revenue'];
                        ?>
                        <h5>Tổng doanh thu</h5>
                        <h4><?= number_format($total0, 0, ',', '.') ?>đ</h4>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Xem chi tiết</a>
                </div>
            </div>

            <!-- Tổng khách hàng -->
            <div class="card-single">
                <div class="card-body">
                    <span class="ti-reload"></span>
                    <div class="card-body-DT">
                        <?php
                        $getKH = $statistical->gettongKhachHang();
                        $demKH = $getKH ? mysqli_num_rows($getKH) : 0;
                        ?>
                        <h5>Số lượng khách hàng</h5>
                        <h4><?= $demKH ?></h4>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Xem chi tiết</a>
                </div>
            </div>

            <!-- Tổng sản phẩm -->
            <div class="card-single">
                <div class="card-body">
                    <span class="ti-check-box"></span>
                    <div class="card-body-DT">
                        <?php
                        $getSP = $statistical->gettongSP();
                        $demSP = $getSP ? mysqli_num_rows($getSP) : 0;
                        ?>
                        <h5>Số lượng sản phẩm</h5>
                        <h4><?= $demSP ?></h4>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="#">Xem chi tiết</a>
                </div>
            </div>
        </div>

        <!-- THỐNG KÊ THEO NGÀY -->
        <section class="recent">
            <div class="activity-grid">
                <div class="activity-card">
                    <h3>Thống kê sản phẩm theo ngày</h3>
                    <form method="POST" action="">
                        <div class="activity-card-calendar">
                            <div class="input-group mb-3">
                                <label>Từ ngày</label>
                                <input name="date1" type="date" value="<?= $_POST['date1'] ?? '' ?>" required />
                            </div>
                            <div class="input-group mb-3">
                                <label>Đến ngày</label>
                                <input name="date2" type="date" value="<?= $_POST['date2'] ?? '' ?>" required />
                            </div>
                            <div class="input-group1 mb-3">
                                <input type="submit" name="submit" value="Tìm kiếm" />
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Ảnh</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($getTongDoanhThuTheoNGay) && $getTongDoanhThuTheoNGay && mysqli_num_rows($getTongDoanhThuTheoNGay) > 0) {
                                        $totalQty = 0;
                                        $sumTotal = 0;
                                        while ($row = $getTongDoanhThuTheoNGay->fetch_assoc()) {
                                            $totalQty += $row['value_count'];
                                            $sumTotal += $row['value_sumTT'];
                                            ?>
                                            <tr>
                                                <td><?= $row['productName'] ?></td>
                                                <td><img src="upload/<?= $row['image'] ?>" width="100"></td>
                                                <td><?= number_format($row['price'], 0, ',', '.') ?>đ</td>
                                                <td><?= $row['value_count'] ?></td>
                                                <td><?= number_format($row['value_sumTT'], 0, ',', '.') ?>đ</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td><strong>Tổng SL: <?= $totalQty ?></strong></td>
                                            <td><strong>Tổng tiền: <?= number_format($sumTotal, 0, ',', '.') ?>đ</strong></td>
                                        </tr>
                                        <?php
                                    } else {
                                        echo '<tr><td colspan="5" style="text-align:center;">Không có sản phẩm trong khoảng thời gian này.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>

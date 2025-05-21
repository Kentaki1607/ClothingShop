<?php
include '../classses/statistical.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$statistical = new statistical();

// Tổng khách hàng
$getKH = $statistical->gettongKhachHang();
$demKH = $getKH ? mysqli_num_rows($getKH) : 0;

// Tổng sản phẩm
$getSP = $statistical->gettongSP();
$demSP = $getSP ? mysqli_num_rows($getSP) : 0;

// Tổng admin
$getAdmin = $statistical->gettongAdmin();
$resultAdmin = $getAdmin ? $getAdmin->fetch_assoc() : ['countadmin' => 0];
$countAdmin = $resultAdmin['countadmin'];

// Thống kê năm 2022
$tongSPNam2022 = $doanhThuNam2022 = 0;
$get2022 = $statistical->gettongSPTheoNam(2022);
if ($get2022 && $get2022->num_rows > 0) {
    $r2022 = $get2022->fetch_assoc();
    $tongSPNam2022 = $r2022['value_count'] ?? 0;
    $doanhThuNam2022 = $r2022['value_sumTT'] ?? 0;
}

// Thống kê năm 2025
$tongSPNam2025 = $doanhThuNam2025 = 0;
$get2025 = $statistical->gettongSPTheoNam(2025);
if ($get2025 && $get2025->num_rows > 0) {
    $r2025 = $get2025->fetch_assoc();
    $tongSPNam2025 = $r2025['value_count'] ?? 0;
    $doanhThuNam2025 = $r2025['value_sumTT'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thống kê</title>
    <link rel="stylesheet" href="assets/font/themify-icons/themify-icons.css" />
    <link rel="stylesheet" href="assets/css/grid.css" />
    <link rel="stylesheet" href="assets/css/statistical.css" />
    <link rel="stylesheet" href="https://cdn.oesmith.co.uk/morris-0.5.1.css" />
    <style>
        #stacked, #donut-chart {
            min-height: 300px;
            margin: 30px auto;
        }
    </style>
</head>
<body>
    <?php include './inc/sidebar.php'; ?>

    <div class="main-content">
        <div class="grid wide">
            <h2 class="text-center">Thống kê tổng quan</h2>

            <div class="row">
                <div class="col l-12 m-12 c-12 text-center">
                    <div id="donut-chart"></div>
                </div>
                <div class="col l-12 m-12 c-12 text-center">
                    <div id="stacked"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js"></script>
    <script>
        // Biểu đồ cột
        Morris.Bar({
            element: 'stacked',
            data: [
                { y: '2020', a: 80, b: 120 },
                { y: '2021', a: 100, b: 150 },
                { y: '2022', a: <?= $tongSPNam2022 ?>, b: <?= $doanhThuNam2022 ?> },
                { y: '2023', a: 145, b: 85 },
                { y: '2024', a: 160, b: 95 },
                { y: '2025', a: <?= $tongSPNam2025 ?>, b: <?= $doanhThuNam2025 ?> }
            ],
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Sản phẩm bán ra', 'Doanh thu'],
            barColors: ['#3498db', '#e74c3c'],
            hideHover: 'auto',
            resize: true,
            stacked: true
        });

        // Biểu đồ tròn
        Morris.Donut({
            element: 'donut-chart',
            data: [
                { label: "Sản phẩm", value: <?= $demSP ?> },
                { label: "Nhân viên", value: <?= $countAdmin ?> },
                { label: "Khách hàng", value: <?= $demKH ?> }
            ],
            colors: ['#1abc9c', '#9b59b6', '#f39c12'],
            resize: true
        });
    </script>
</body>
</html>
